#!/data/data/com.termux/files/usr/bin/bash

set -e

# =========================================================
# Ninja School V2 - Termux Installer
# =========================================================

REPO="https://github.com/dang123456789/Ninja-Termux/archive/refs/heads/main.zip"

HOME_DIR="$HOME"
ZIP="$HOME_DIR/ninja-termux.zip"
WORK="$HOME_DIR/.ninja-termux-install"
NINJA_DIR="$HOME_DIR/ninja"

PREFIX_DIR="$PREFIX"
SOCKET="$PREFIX_DIR/var/run/mysqld/mysqld.sock"
DB_NAME="nso"

echo
echo "=========================================="
echo "     NINJA SCHOOL V2 - TERMUX INSTALL"
echo "=========================================="
echo

# ---------------------------------------------------------
# 1. Kiểm tra Termux
# ---------------------------------------------------------

if [ -z "$PREFIX" ]; then
    echo "LỖI: Script này phải chạy trong Termux."
    exit 1
fi

echo "[1] Cài các package cần thiết..."

pkg update -y

pkg install -y \
    curl \
    unzip \
    mariadb \
    php \
    openjdk-21

echo
echo "✓ Đã cài package."
echo

# ---------------------------------------------------------
# 2. Tải source từ GitHub
# ---------------------------------------------------------

echo "[2] Tải Ninja từ GitHub..."

rm -rf "$WORK"
mkdir -p "$WORK"

rm -f "$ZIP"

curl -L \
    --fail \
    --retry 3 \
    "$REPO" \
    -o "$ZIP"

echo "✓ Đã tải source."

# ---------------------------------------------------------
# 3. Giải nén
# ---------------------------------------------------------

echo
echo "[3] Giải nén source..."

unzip -q "$ZIP" -d "$WORK"

echo "✓ Đã giải nén."

# ---------------------------------------------------------
# 4. Tự tìm Ninja.jar
# ---------------------------------------------------------

echo
echo "[4] Tìm source Ninja..."

NINJA_JAR="$(find "$WORK" -type f -name "Ninja.jar" -print -quit)"

if [ -z "$NINJA_JAR" ]; then
    echo
    echo "LỖI: Không tìm thấy Ninja.jar."
    echo
    echo "Các file tìm được:"
    find "$WORK" -maxdepth 6 -type f | head -100
    exit 1
fi

NINJA_SRC="$(dirname "$NINJA_JAR")"

echo "✓ Ninja source:"
echo "  $NINJA_SRC"

# ---------------------------------------------------------
# 5. Tự tìm database nso.sql
# ---------------------------------------------------------

echo
echo "[5] Tìm database nso.sql..."

DB_FILE="$(find "$WORK" -type f -name "nso.sql" -print -quit)"

if [ -z "$DB_FILE" ]; then
    echo
    echo "LỖI: Không tìm thấy database/nso.sql."
    exit 1
fi

echo "✓ Database:"
echo "  $DB_FILE"

# ---------------------------------------------------------
# 6. Chuẩn bị MariaDB
# ---------------------------------------------------------

echo
echo "[6] Khởi tạo MariaDB..."

mkdir -p "$PREFIX_DIR/var/run/mysqld"

# Nếu MariaDB chưa được khởi tạo
if [ ! -d "$PREFIX_DIR/var/lib/mysql/mysql" ]; then

    echo "→ Khởi tạo database system..."

    mariadb-install-db \
        --datadir="$PREFIX_DIR/var/lib/mysql" \
        --auth-root-authentication-method=normal \
        >/dev/null 2>&1 || true

fi

# ---------------------------------------------------------
# 7. Khởi động MariaDB
# ---------------------------------------------------------

echo
echo "[7] Khởi động MariaDB..."

mkdir -p "$HOME_DIR/mariadb-log"
mkdir -p "$PREFIX_DIR/var/run/mysqld"

if ! mariadb-admin \
    --socket="$SOCKET" \
    -u root \
    ping >/dev/null 2>&1
then

    echo "→ MariaDB chưa chạy, đang khởi động..."

    rm -f "$SOCKET"

    mariadbd-safe \
        --datadir="$PREFIX_DIR/var/lib/mysql" \
        --socket="$SOCKET" \
        --log-error="$HOME_DIR/mariadb-log/error.log" \
        >/dev/null 2>&1 &

fi

# Chờ MariaDB tối đa 30 giây

READY=0

for i in $(seq 1 30); do

    if mariadb-admin \
        --socket="$SOCKET" \
        -u root \
        ping >/dev/null 2>&1
    then
        READY=1
        break
    fi

    sleep 1

done

if [ "$READY" != "1" ]; then

    echo
    echo "LỖI: MariaDB không khởi động được."
    echo
    echo "Log:"
    tail -50 "$HOME_DIR/mariadb-log/error.log" 2>/dev/null || true

    exit 1

fi

echo "✓ MariaDB đang chạy."

# ---------------------------------------------------------
# 8. Tạo database
# ---------------------------------------------------------

echo
echo "[8] Tạo database $DB_NAME..."

mariadb \
    --socket="$SOCKET" \
    -u root \
    -e "CREATE DATABASE IF NOT EXISTS \`$DB_NAME\` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;"

echo "✓ Database $DB_NAME đã sẵn sàng."

# ---------------------------------------------------------
# 9. Import database
# ---------------------------------------------------------

echo
echo "[9] Import database..."

CLEAN_SQL="$HOME_DIR/nso_clean.sql"

# Xóa những dòng "Enter password:" nếu file SQL có bị chèn nhầm

sed '/^Enter password:$/d' "$DB_FILE" > "$CLEAN_SQL"

mariadb \
    --socket="$SOCKET" \
    -u root \
    "$DB_NAME" < "$CLEAN_SQL"

rm -f "$CLEAN_SQL"

echo "✓ Import database thành công."

# ---------------------------------------------------------
# 10. Cài Ninja
# ---------------------------------------------------------

echo
echo "[10] Cài Ninja..."

rm -rf "$NINJA_DIR"

mkdir -p "$NINJA_DIR"

cp -a "$NINJA_SRC"/. "$NINJA_DIR"/

echo "✓ Đã copy Ninja."

# ---------------------------------------------------------
# 11. Kiểm tra Ninja.jar
# ---------------------------------------------------------

if [ ! -f "$NINJA_DIR/Ninja.jar" ]; then

    echo
    echo "LỖI: Không có Ninja.jar trong:"
    echo "$NINJA_DIR"

    exit 1

fi

# ---------------------------------------------------------
# 12. Kiểm tra resource
# ---------------------------------------------------------

echo
echo "[11] Kiểm tra resource..."

if [ -d "$NINJA_DIR/res" ]; then

    echo "✓ Đã có thư mục res."

else

    echo "⚠ CẢNH BÁO: Không tìm thấy thư mục res."

fi

# Kiểm tra file lỗi trước đó

if [ -f "$NINJA_DIR/res/icon/4/2683.png" ]; then

    echo "✓ Đã có res/icon/4/2683.png"

else

    echo "⚠ Không có res/icon/4/2683.png"

fi

# ---------------------------------------------------------
# 13. Tạo start.sh
# ---------------------------------------------------------

echo
echo "[12] Tạo start.sh..."

cat > "$HOME_DIR/start.sh" <<'EOF'
#!/data/data/com.termux/files/usr/bin/bash

set -e

SOCKET="$PREFIX/var/run/mysqld/mysqld.sock"
NINJA_DIR="$HOME/ninja"

echo
echo "=========================================="
echo "        NINJA SCHOOL V2 SERVER"
echo "=========================================="
echo

# Tạo thư mục socket
mkdir -p "$PREFIX/var/run/mysqld"
mkdir -p "$HOME/mariadb-log"

# Kiểm tra MariaDB
if ! mariadb-admin \
    --socket="$SOCKET" \
    -u root \
    ping >/dev/null 2>&1
then

    echo "→ MariaDB chưa chạy."
    echo "→ Đang khởi động MariaDB..."

    rm -f "$SOCKET"

    mariadbd-safe \
        --datadir="$PREFIX/var/lib/mysql" \
        --socket="$SOCKET" \
        --log-error="$HOME/mariadb-log/error.log" \
        >/dev/null 2>&1 &

    READY=0

    for i in $(seq 1 30); do

        if mariadb-admin \
            --socket="$SOCKET" \
            -u root \
            ping >/dev/null 2>&1
        then
            READY=1
            break
        fi

        sleep 1

    done

    if [ "$READY" != "1" ]; then
        echo "LỖI: Không thể khởi động MariaDB."
        exit 1
    fi

fi

echo "✓ MariaDB OK"
echo
echo "Server: $NINJA_DIR"
echo "Port: 14444"
echo
echo "Đang chạy Ninja..."
echo

cd "$NINJA_DIR"

exec java -cp "Ninja.jar:lib-old/*" server.Server
EOF

chmod +x "$HOME_DIR/start.sh"

# ---------------------------------------------------------
# 14. Tạo start-web.sh
# ---------------------------------------------------------

echo
echo "[13] Tạo start-web.sh..."

cat > "$HOME_DIR/start-web.sh" <<'EOF'
#!/data/data/com.termux/files/usr/bin/bash

NINJA_DIR="$HOME/ninja"

echo
echo "=========================================="
echo "              NINJA WEB"
echo "=========================================="
echo
echo "Web: http://127.0.0.1:8080"
echo
echo "Mở trình duyệt và truy cập:"
echo "http://127.0.0.1:8080"
echo

cd "$HOME"

export TMPDIR="$HOME"

exec php \
    -S 127.0.0.1:8080 \
    -t "$NINJA_DIR"
EOF

chmod +x "$HOME_DIR/start-web.sh"

# ---------------------------------------------------------
# 15. Dọn file tạm
# ---------------------------------------------------------

rm -f "$ZIP"
rm -rf "$WORK"

# ---------------------------------------------------------
# 16. Hoàn tất
# ---------------------------------------------------------

echo
echo "=========================================="
echo "          CÀI ĐẶT THÀNH CÔNG!"
echo "=========================================="
echo

echo "Ninja:"
echo "  $NINJA_DIR"

echo

echo "Database:"
echo "  $DB_NAME"

echo

echo "MariaDB socket:"
echo "  $SOCKET"

echo
echo "=========================================="
echo "▶ Chạy server:"
echo "  bash ~/start.sh"
echo
echo "▶ Chạy web:"
echo "  bash ~/start-web.sh"
echo "=========================================="
echo
