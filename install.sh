#!/data/data/com.termux/files/usr/bin/bash
set -e

REPO_ZIP="https://github.com/dang123456789/Ninja-Termux/archive/refs/heads/main.zip"
TMP="$PREFIX/tmp/ninja-install"
BASE="$HOME/Ninja-Termux"

echo "======================================"
echo "       NINJA TERMUX INSTALL"
echo "======================================"

echo "[1] Cài package..."
pkg update -y
pkg install -y curl unzip openjdk-21 mariadb php

echo "[2] Tải Ninja từ GitHub..."

rm -rf "$TMP"
mkdir -p "$TMP"

curl -L "$REPO_ZIP" -o "$TMP/ninja.zip"
unzip -q "$TMP/ninja.zip" -d "$TMP"

rm -rf "$BASE"
mv "$TMP/Ninja-Termux-main" "$BASE"

echo "[3] Khởi tạo MariaDB..."

mkdir -p "$PREFIX/var/run/mysqld"

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
        --socket="$PREFIX/var/run/mysqld/mysqld.sock" \
        >/dev/null 2>&1 &
    sleep 5
fi

echo "[5] Tạo database nso..."

mariadb -u root <<'SQL'
CREATE DATABASE IF NOT EXISTS nso
CHARACTER SET utf8mb4
COLLATE utf8mb4_general_ci;
SQL

echo "[6] Import database..."

mariadb -u root nso < "$BASE/database/nso.sql"

echo "[7] Cài Ninja..."

rm -rf "$HOME/ninja"
mkdir -p "$HOME/ninja"
cp -a "$BASE/ninja/dist/." "$HOME/ninja/"

echo "[8] Cài Web..."

rm -rf "$HOME/web"
mkdir -p "$HOME/web"
cp -a "$BASE/web/." "$HOME/web/"

echo "[9] Tạo lệnh ninja..."

cat > "$PREFIX/bin/ninja" <<'NINJA'
#!/data/data/com.termux/files/usr/bin/bash

if pgrep -f 'java.*server.Server' >/dev/null 2>&1; then
    echo "Ninja Server đang chạy!"
    exit 0
fi

if ! pgrep -x mariadbd >/dev/null 2>&1; then
    mkdir -p "$PREFIX/var/run/mysqld"
    mariadbd-safe \
        --datadir="$PREFIX/var/lib/mysql" \
        --socket="$PREFIX/var/run/mysqld/mysqld.sock" \
        >/dev/null 2>&1 &
    sleep 5
fi

mariadb -u root -e "USE nso; SELECT 1;" >/dev/null

cd "$HOME/ninja"

echo "======================================"
echo "       NINJA SERVER START"
echo "======================================"
echo "Port: 14444"
echo "======================================"

exec java -cp "Ninja.jar:lib-old/*" server.Server
NINJA

chmod +x "$PREFIX/bin/ninja"

rm -rf "$TMP"

echo
echo "======================================"
echo "       CÀI ĐẶT HOÀN TẤT"
echo "======================================"
echo
echo "Chạy server:"
echo "ninja"
echo
echo "Port: 14444"
