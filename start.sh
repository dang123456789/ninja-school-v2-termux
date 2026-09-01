#!/data/data/com.termux/files/usr/bin/bash

set -e

BASE="$(cd "$(dirname "$0")" && pwd)"
NINJA="$HOME/ninja"

echo "======================================"
echo "       NINJA SERVER START"
echo "======================================"

echo "[1] Kiểm tra MariaDB..."

if ! pgrep -x mariadbd >/dev/null 2>&1; then
    mkdir -p "$PREFIX/var/run/mysqld"

    mariadbd-safe \
      --datadir="$PREFIX/var/lib/mysql" \
      --socket="$PREFIX/var/run/mysqld/mysqld.sock" \
      >/dev/null 2>&1 &

    sleep 5
fi

echo "[2] Kiểm tra database nso..."

mariadb -u root -e "USE nso; SELECT 1;" >/dev/null

echo "[3] Khởi động Ninja..."

cd "$NINJA"

exec java -cp "Ninja.jar:lib-old/*" server.Server
