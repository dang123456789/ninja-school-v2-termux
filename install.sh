#!/data/data/com.termux/files/usr/bin/bash
set -e

REPO="https://github.com/dang123456789/Ninja-Termux/archive/refs/heads/main.zip"
WORK="$HOME/.ninja-install"
SOCKET="$PREFIX/var/run/mysqld/mysqld.sock"

echo "======================================"
echo "       NINJA TERMUX INSTALL"
echo "======================================"

echo "[1] Cài package..."
pkg update -y
pkg install -y openjdk-21 mariadb php curl unzip

echo "[2] Tải Ninja từ GitHub..."

rm -rf "$WORK"
mkdir -p "$WORK"

curl -L "$REPO" -o "$WORK/ninja.zip"
unzip -q "$WORK/ninja.zip" -d "$WORK"

SRC="$WORK/Ninja-Termux-main"

if [ ! -d "$SRC/database" ]; then
    echo "LỖI: Không tìm thấy thư mục database."
    exit 1
fi

echo "[3] Khởi tạo MariaDB..."

mkdir -p "$PREFIX/var/run/mysqld"
mkdir -p "$HOME/mariadb-log"

if [ ! -d "$PREFIX/var/lib/mysql/mysql" ]; then
    mariadb-install-db \
        --user="$(whoami)" \
        --basedir="$PREFIX" \
        --datadir="$PREFIX/var/lib/mysql"
fi

echo "[4] Khởi động MariaDB..."

if ! pgrep -x mariadbd >/dev/null 2>&1; then
    mariadbd-safe \
        --datadir="$PREFIX/var/lib/mysql" \
        --socket="$SOCKET" \
        --log-error="$HOME/mariadb-log/error.log" \
        >/dev/null 2>&1 &
fi

for i in $(seq 1 15); do
    if [ -S "$SOCKET" ]; then
        break
    fi
    sleep 1
done

if [ ! -S "$SOCKET" ]; then
    echo "LỖI: MariaDB không khởi động được."
    cat "$HOME/mariadb-log/error.log" 2>/dev/null || true
    exit 1
fi

echo "[5] Tạo database nso..."

mariadb --socket="$SOCKET" -u root <<'SQL'
CREATE DATABASE IF NOT EXISTS nso
CHARACTER SET utf8mb4
COLLATE utf8mb4_general_ci;
SQL

echo "[6] Import database nso..."

mariadb --socket="$SOCKET" -u root nso < "$SRC/database/nso.sql"

echo "[7] Cài Ninja..."

rm -rf "$HOME/ninja"
mkdir -p "$HOME/ninja"

cp -a "$SRC/ninja/dist/." "$HOME/ninja/"

echo "[8] Cài lệnh start..."

cp "$SRC/start.sh" "$HOME/start.sh"
chmod +x "$HOME/start.sh"

if [ -f "$SRC/start-web.sh" ]; then
    cp "$SRC/start-web.sh" "$HOME/start-web.sh"
    chmod +x "$HOME/start-web.sh"
fi

rm -rf "$WORK"

echo
echo "======================================"
echo "       CÀI ĐẶT HOÀN TẤT"
echo "======================================"
echo
echo "Ninja: $HOME/ninja"
echo "Database: nso"
echo
echo "Chạy server:"
echo "bash ~/start.sh"
echo
echo "Web:"
echo "bash ~/start-web.sh"
echo "======================================"
