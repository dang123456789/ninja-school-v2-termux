#!/data/data/com.termux/files/usr/bin/bash

set -u

REPO="https://github.com/dangnguyen1982a/ninja-school-v2-termux"
ZIP_URL="https://github.com/dangnguyen1982a/ninja-school-v2-termux/archive/refs/heads/main.zip"

HOME_DIR="$HOME"
PREFIX_DIR="$PREFIX"
WORK="$HOME_DIR/.ninja-termux-install"
NINJA_DIR="$HOME_DIR/ninja"
SOCKET="$PREFIX_DIR/var/run/mysqld/mysqld.sock"
LOG_DIR="$HOME_DIR/mariadb-log"
DB_NAME="nso"

echo
echo "=========================================="
echo "     NINJA SCHOOL V2 - TERMUX INSTALL"
echo "=========================================="
echo

echo "[1] Cài các package cần thiết..."

pkg update -y
pkg install -y curl unzip mariadb php openjdk-21

echo
echo "✓ Đã cài package."
echo

echo "[2] Chuẩn bị thư mục..."

rm -rf "$WORK"
mkdir -p "$WORK"

echo "✓ Đã chuẩn bị."
echo

echo "[3] Tải Ninja từ GitHub..."

cd "$WORK" || exit 1

curl -L --fail --retry 3 \
    -o ninja.zip \
    "$ZIP_URL"

echo
echo "✓ Đã tải source."
echo

echo "[4] Giải nén source..."

unzip -q -o ninja.zip

SOURCE_ROOT="$(find "$WORK" -maxdepth 1 -mindepth 1 -type d -name 'ninja-school-v2-termux-*' | head -1)"

if [ -z "$SOURCE_ROOT" ] || [ ! -d "$SOURCE_ROOT" ]; then
    echo "LỖI: Không tìm thấy source."
    exit 1
fi

echo "✓ Source:"
echo "  $SOURCE_ROOT"
echo

echo "[5] Tìm source Ninja..."

NINJA_SOURCE="$(find "$SOURCE_ROOT" -type d -path '*/ninja/dist' | head -1)"

if [ -z "$NINJA_SOURCE" ]; then
    echo "LỖI: Không tìm thấy ninja/dist."
    exit 1
fi

echo "✓ Ninja source:"
echo "  $NINJA_SOURCE"
echo

echo "[6] Tìm database nso.sql..."

DB_FILE="$(find "$SOURCE_ROOT" -type f -path '*/database/nso.sql' | head -1)"

if [ -z "$DB_FILE" ]; then
    DB_FILE="$(find "$SOURCE_ROOT" -type f -name 'nso.sql' | head -1)"
fi

if [ -z "$DB_FILE" ]; then
    echo "LỖI: Không tìm thấy database/nso.sql."
    exit 1
fi

echo "✓ Database:"
echo "  $DB_FILE"
echo

echo "[7] Chuẩn bị MariaDB..."

mkdir -p "$PREFIX_DIR/var/run/mysqld"
mkdir -p "$LOG_DIR"

if [ ! -d "$PREFIX_DIR/var/lib/mysql/mysql" ]; then
    echo "→ Khởi tạo database system..."

    mariadb-install-db \
        --datadir="$PREFIX_DIR/var/lib/mysql" \
        --auth-root-authentication-method=normal \
        >/dev/null 2>&1 || true
fi

echo "✓ MariaDB đã sẵn sàng."
echo

echo "[8] Khởi động MariaDB..."

if ! mariadb-admin \
    --socket="$SOCKET" \
    -u root \
    ping >/dev/null 2>&1; then

    echo "→ MariaDB chưa chạy, đang khởi động..."

    mariadbd-safe \
        --datadir="$PREFIX_DIR/var/lib/mysql" \
        --socket="$SOCKET" \
        --log-error="$LOG_DIR/error.log" \
        >/dev/null 2>&1 &

    READY=0

    for i in $(seq 1 30); do
        if mariadb-admin \
            --socket="$SOCKET" \
            -u root \
            ping >/dev/null 2>&1; then
            READY=1
            break
        fi
        sleep 1
    done

    if [ "$READY" -ne 1 ]; then
        echo "LỖI: Không thể khởi động MariaDB."
        tail -50 "$LOG_DIR/error.log" 2>/dev/null || true
        exit 1
    fi
fi

echo "✓ MariaDB đang chạy."
echo

echo "[9] Tạo database $DB_NAME..."

mariadb \
    --socket="$SOCKET" \
    -u root \
    -e "CREATE DATABASE IF NOT EXISTS \`$DB_NAME\` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;" \
    || {
        echo "LỖI: Không tạo được database."
        exit 1
    }

echo "✓ Database $DB_NAME đã sẵn sàng."
echo

echo "[10] Làm sạch SQL..."

CLEAN_SQL="$HOME_DIR/nso_clean.sql"

# Loại bỏ mọi dòng Enter password:
sed '/Enter password:/d' "$DB_FILE" > "$CLEAN_SQL"

# Loại bỏ BOM UTF-8 nếu có
sed -i '1s/^\xEF\xBB\xBF//' "$CLEAN_SQL"

if grep -q "Enter password:" "$CLEAN_SQL"; then
    echo "LỖI: SQL vẫn còn dòng Enter password."
    rm -f "$CLEAN_SQL"
    exit 1
fi

echo "✓ SQL đã được làm sạch."
echo

echo "[11] Import database..."

if ! mariadb \
    --socket="$SOCKET" \
    -u root \
    "$DB_NAME" < "$CLEAN_SQL"; then

    echo
    echo "LỖI: Import database thất bại."
    rm -f "$CLEAN_SQL"
    exit 1
fi

rm -f "$CLEAN_SQL"

echo "✓ Import database thành công."
echo

echo "[12] Cài Ninja..."

rm -rf "$NINJA_DIR"
mkdir -p "$NINJA_DIR"

cp -a "$NINJA_SOURCE"/. "$NINJA_DIR"/

if [ ! -f "$NINJA_DIR/Ninja.jar" ]; then
    echo "LỖI: Không tìm thấy Ninja.jar."
    exit 1
fi

echo "✓ Ninja đã được cài vào:"
echo "  $NINJA_DIR"
echo

echo "[13] Tạo script chạy Ninja..."

cat > "$HOME_DIR/start-ninja.sh" <<'RUNNINJA'
#!/data/data/com.termux/files/usr/bin/bash

SOCKET="$PREFIX/var/run/mysqld/mysqld.sock"

echo "=========================================="
echo "           NINJA SCHOOL V2"
echo "=========================================="
echo

if ! mariadb-admin \
    --socket="$SOCKET" \
    -u root \
    ping >/dev/null 2>&1; then

    echo "→ MariaDB chưa chạy..."

    mkdir -p "$PREFIX/var/run/mysqld"
    mkdir -p "$HOME/mariadb-log"

    mariadbd-safe \
        --datadir="$PREFIX/var/lib/mysql" \
        --socket="$SOCKET" \
        --log-error="$HOME/mariadb-log/error.log" \
        >/dev/null 2>&1 &

    sleep 5
fi

cd "$HOME/ninja" || exit 1

echo "→ Starting Ninja Server..."
echo

exec java -cp "Ninja.jar:lib-old/*" server.Server
RUNNINJA

chmod +x "$HOME_DIR/start-ninja.sh"

echo "✓ Đã tạo:"
echo "  ~/start-ninja.sh"
echo

echo "[14] Tạo script chạy Web Panel..."

if [ -d "$SOURCE_ROOT/web" ]; then
    rm -rf "$HOME_DIR/web"
    cp -a "$SOURCE_ROOT/web" "$HOME_DIR/web"
fi

if [ -f "$HOME_DIR/web/index.php" ]; then

    cat > "$HOME_DIR/start-web.sh" <<'RUNWEB'
#!/data/data/com.termux/files/usr/bin/bash

PANEL="$HOME/web"

echo "=========================================="
echo "              NSO VIP PANEL"
echo "=========================================="
echo
echo "Web: http://127.0.0.1:8080"
echo

cd "$HOME" || exit 1

exec php \
    -d opcache.enable=0 \
    -d opcache.enable_cli=0 \
    -S 127.0.0.1:8080 \
    -t "$PANEL"
RUNWEB

    chmod +x "$HOME_DIR/start-web.sh"

    echo "✓ Web Panel:"
    echo "  ~/web"
    echo
    echo "✓ Đã tạo:"
    echo "  ~/start-web.sh"
else
    echo "→ Không tìm thấy web panel, bỏ qua."
fi

echo
echo "=========================================="
echo "             CÀI ĐẶT HOÀN TẤT"
echo "=========================================="
echo
echo "📁 Ninja:"
echo "   $NINJA_DIR"
echo
echo "🗄️ Database:"
echo "   $DB_NAME"
echo
echo "▶ Chạy Ninja:"
echo "   bash ~/start-ninja.sh"
echo
echo "🌐 Chạy Web Panel:"
echo "   bash ~/start-web.sh"
echo
echo "🌐 Panel:"
echo "   http://127.0.0.1:8080"
echo
echo "=========================================="
