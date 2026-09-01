#!/data/data/com.termux/files/usr/bin/bash
set -e

BASE="$(cd "$(dirname "$0")" && pwd)"
SOCKET="$PREFIX/var/run/mysqld/mysqld.sock"

echo "======================================"
echo "       NINJA TERMUX INSTALL"
echo "======================================"

echo "[1] Cài package..."
pkg update -y
pkg install -y openjdk-21 mariadb php curl unzip

echo "[2] Khởi tạo MariaDB..."
mkdir -p "$PREFIX/var/run/mysqld"
mkdir -p "$HOME/mariadb-log"

if [ ! -d "$PREFIX/var/lib/mysql/mysql" ]; then
    mariadb-install-db \
        --user="$(whoami)" \
        --basedir="$PREFIX" \
        --datadir="$PREFIX/var/lib/mysql"
fi

echo "[3] Khởi động MariaDB..."

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

echo "[4] Tạo database nso..."

mariadb --socket="$SOCKET" -u root <<'SQL'
CREATE DATABASE IF NOT EXISTS nso
CHARACTER SET utf8mb4
COLLATE utf8mb4_general_ci;
SQL

echo "[5] Import database nso..."

mariadb --socket="$SOCKET" -u root nso < "$BASE/database/nso.sql"

echo "[6] Cài Ninja..."

rm -rf "$HOME/ninja"
mkdir -p "$HOME/ninja"
cp -a "$BASE/ninja/dist/." "$HOME/ninja/"

echo "[7] Cài lệnh start..."

cp "$BASE/start.sh" "$HOME/start.sh"
chmod +x "$HOME/start.sh"

if [ -f "$BASE/start-web.sh" ]; then
    cp "$BASE/start-web.sh" "$HOME/start-web.sh"
    chmod +x "$HOME/start-web.sh"
fi

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
