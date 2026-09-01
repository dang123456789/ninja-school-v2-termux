#!/data/data/com.termux/files/usr/bin/bash

set -e

BASE="$(cd "$(dirname "$0")" && pwd)"

echo "======================================"
echo "       NINJA TERMUX INSTALL"
echo "======================================"

echo "[1] Cài Java + MariaDB + PHP + Lighttpd..."

pkg update -y
pkg install -y openjdk-21 mariadb php lighttpd

echo "[2] Khởi tạo MariaDB..."

if [ ! -d "$PREFIX/var/lib/mysql/mysql" ]; then
    mariadb-install-db \
      --user="$(whoami)" \
      --basedir="$PREFIX" \
      --datadir="$PREFIX/var/lib/mysql"
fi

echo "[3] Khởi động MariaDB..."

mkdir -p "$PREFIX/var/run/mysqld"

if ! pgrep -x mariadbd >/dev/null 2>&1; then
    mariadbd-safe \
      --datadir="$PREFIX/var/lib/mysql" \
      --socket="$PREFIX/var/run/mysqld/mysqld.sock" \
      >/dev/null 2>&1 &

    sleep 5
fi

echo "[4] Tạo database nso..."

mariadb -u root <<'SQL'
CREATE DATABASE IF NOT EXISTS nso
CHARACTER SET utf8mb4
COLLATE utf8mb4_general_ci;
SQL

echo "[5] Restore database..."

mariadb -u root nso < "$BASE/database/nso.sql"

echo "[6] Khôi phục Ninja..."

rm -rf "$HOME/ninja"
cp -a "$BASE/ninja/dist" "$HOME/ninja"

echo "[7] Khôi phục web..."

rm -rf "$HOME/web"
mkdir -p "$HOME/web"
cp -a "$BASE/web/." "$HOME/web/"

echo
echo "======================================"
echo "       CÀI ĐẶT HOÀN TẤT"
echo "======================================"
echo
echo "Ninja: $HOME/ninja"
echo "Web:   $HOME/web"
echo
echo "Chạy:"
echo "bash start.sh"
