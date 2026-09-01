#!/data/data/com.termux/files/usr/bin/bash
set -e

SOCKET="$PREFIX/var/run/mysqld/mysqld.sock"
NINJA="$HOME/ninja"

echo "======================================"
echo "       NINJA SERVER START"
echo "======================================"

echo "[1] Kiểm tra MariaDB..."

mkdir -p "$PREFIX/var/run/mysqld"
mkdir -p "$HOME/mariadb-log"

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
    echo "LỖI: Không tìm thấy MariaDB socket."
    exit 1
fi

echo "[2] Kiểm tra database nso..."

mariadb --socket="$SOCKET" -u root -e "USE nso; SELECT 1;" >/dev/null

echo "[3] Khởi động Ninja..."

cd "$NINJA"

exec java -cp "Ninja.jar:lib-old/*" server.Server
