#!/data/data/com.termux/files/usr/bin/bash

set -e

echo "======================================"
echo "          NINJA WEB"
echo "======================================"

cd "$HOME/web"

echo "Web: http://127.0.0.1:8080"

php -S 127.0.0.1:8080 -t "$HOME/web"
