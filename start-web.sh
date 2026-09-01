#!/data/data/com.termux/files/usr/bin/bash

WEB="$HOME/web"

echo "======================================"
echo "          NINJA WEB"
echo "======================================"
echo "Web: http://127.0.0.1:8080"
echo "======================================"

if [ ! -d "$WEB" ]; then
    echo "LỖI: Không tìm thấy thư mục $WEB"
    exit 1
fi

cd "$WEB" || exit 1

php -S 127.0.0.1:8080 -t "$WEB"
