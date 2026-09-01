#!/data/data/com.termux/files/usr/bin/bash
set -e

REPO="https://github.com/dang123456789/Ninja-Termux.git"
BASE="$HOME/Ninja-Termux"

echo "======================================"
echo "       NINJA TERMUX INSTALL"
echo "======================================"

echo "[1] Cài package..."
pkg update -y
pkg install -y git openjdk-21 mariadb php

echo "[2] Tải/cập nhật Ninja-Termux..."

if [ -d "$BASE/.git" ]; then
    cd "$BASE"
    git pull --ff-only
else
    rm -rf "$BASE"
    git clone "$REPO" "$BASE"
    cd "$BASE"
fi

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
cd "$HOME/Ninja-Termux"
bash start.sh
NINJA

chmod +x "$PREFIX/bin/ninja"

echo
echo "======================================"
echo "       CÀI ĐẶT HOÀN TẤT"
echo "======================================"
echo
echo "Chạy server bằng:"
echo
echo "    ninja"
echo
echo "Port: 14444"
echo
