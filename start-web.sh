#!/data/data/com.termux/files/usr/bin/bash

PANEL="$HOME/web"

echo
echo "=========================================="
echo "              NRO PANEL"
echo "=========================================="
echo
echo "Web: http://127.0.0.1:8080"
echo
echo "Mở trình duyệt:"
echo "http://127.0.0.1:8080"
echo

cd "$HOME" || exit 1

exec php \
    -d opcache.enable=0 \
    -d opcache.enable_cli=0 \
    -S 127.0.0.1:8080 \
    -t "$PANEL"
