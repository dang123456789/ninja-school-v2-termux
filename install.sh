#!/data/data/com.termux/files/usr/bin/bash

set -e

REPO="https://github.com/dang123456789/Ninja-Termux/archive/refs/heads/main.zip"

WORK="$HOME/.ninja-install"
SOCKET="$PREFIX/var/run/mysqld/mysqld.sock"
MYSQL_DATA="$PREFIX/var/lib/mysql"
MYSQL_LOG="$HOME/mariadb-log/error.log"

echo "======================================"
echo "       NINJA TERMUX INSTALL"
echo "======================================"

echo "[1] Cài package..."

pkg update -y
pkg install -y curl unzip openjdk-21 mariadb php

echo "[2] Tải Ninja từ GitHub..."

rm -rf "$WORK"
mkdir -p "$WORK"

curl -L "$REPO" -o "$WORK/ninja.zip"

unzip -q "$WORK/ninja.zip" -d "$WORK"

SRC="$WORK/Ninja-Termux-main"

if [ ! -d "$SRC" ]; then
    echo "LỖI: Không tìm thấy source Ninja."
    exit 1
fi

echo "[3] Khởi tạo MariaDB..."

mkdir -p "$PREFIX/var/run/mysqld"
mkdir -p "$HOME/mariadb-log"

if [ ! -d "$MYSQL_DATA/mysql" ]; then
    mariadb-install-db \
        --user="$(whoami)" \
        --basedir="$PREFIX" \
        --datadir="$MYSQL_DATA"
fi

echo "[4] Khởi động MariaDB..."

# Kiểm tra MariaDB đã chạy chưa
if ! mariadb --socket="$SOCKET" -u root -e "SELECT 1;" >/dev/null 2>&1; then

    # Xóa socket cũ nếu không kết nối được
    rm -f "$SOCKET"

    mariadbd-safe \
        --datadir="$MYSQL_DATA" \
        --socket="$SOCKET" \
        --log-error="$MYSQL_LOG" \
        >/dev/null 2>&1 &

fi

echo "Đang chờ MariaDB..."

READY=0

for i in $(seq 1 20); do

    if mariadb --socket="$SOCKET" -u root -e "SELECT 1;" >/dev/null 2>&1; then
        READY=1
        break
    fi

    sleep 1

done

if [ "$READY" -ne 1 ]; then

    echo
    echo "======================================"
    echo "   LỖI: MARIA DB KHÔNG KHỞI ĐỘNG"
    echo "======================================"
    echo

    if [ -f "$MYSQL_LOG" ]; then
        tail -50 "$MYSQL_LOG"
    fi

    exit 1
fi

echo "MariaDB: OK"

echo "[5] Tạo database nso..."

mariadb \
    --socket="$SOCKET" \
    -u root <<'SQL'

CREATE DATABASE IF NOT EXISTS nso
CHARACTER SET utf8mb4
COLLATE utf8mb4_general_ci;

SQL

echo "[6] Import database nso..."

SQL_FILE="$SRC/database/nso.sql"

if [ ! -f "$SQL_FILE" ]; then
    echo "LỖI: Không tìm thấy database/nso.sql"
    exit 1
fi

# Loại dòng rác "Enter password:" nếu có
CLEAN_SQL="$WORK/nso_clean.sql"

grep -v '^Enter password:$' "$SQL_FILE" > "$CLEAN_SQL"

mariadb \
    --socket="$SOCKET" \
    -u root \
    nso < "$CLEAN_SQL"

echo "Database nso: OK"

echo "[7] Cài Ninja..."

rm -rf "$HOME/ninja"

mkdir -p "$HOME/ninja"

if [ -d "$SRC/ninja/dist" ]; then

    cp -a "$SRC/ninja/dist/." "$HOME/ninja/"

else

    echo "LỖI: Không tìm thấy ninja/dist."
    exit 1

fi

echo "[8] Cài lệnh start..."

if [ -f "$SRC/start.sh" ]; then

    cp "$SRC/start.sh" "$HOME/start.sh"
    chmod +x "$HOME/start.sh"

else

    echo "LỖI: Không tìm thấy start.sh."
    exit 1

fi

if [ -f "$SRC/start-web.sh" ]; then

    cp "$SRC/start-web.sh" "$HOME/start-web.sh"
    chmod +x "$HOME/start-web.sh"

else

    echo "CẢNH BÁO: Không tìm thấy start-web.sh."

fi

rm -rf "$WORK"

echo
echo "======================================"
echo "       CÀI ĐẶT HOÀN TẤT"
echo "======================================"
echo
echo "Ninja    : $HOME/ninja"
echo "Database : nso"
echo
echo "▶ CHẠY SERVER:"
echo "bash ~/start.sh"
echo
echo "▶ CHẠY WEB:"
echo "bash ~/start-web.sh"
echo
echo "======================================"
