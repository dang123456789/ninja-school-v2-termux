#!/data/data/com.termux/files/usr/bin/bash
set -e

REPO="https://github.com/dangnguyen1982a/ninja-school-v2-termux"
INSTALL_DIR="$HOME/nso-v2"
SRC="$INSTALL_DIR/NsoC"

echo "=== NINJA SCHOOL V2 ==="

pkg update -y
pkg install -y git ant openjdk-21 mariadb

echo "[1] Tải source..."
rm -rf "$INSTALL_DIR"
git clone --depth 1 "$REPO" "$INSTALL_DIR"

echo "[2] Kiểm tra source..."
if [ ! -d "$SRC" ]; then
    echo "LỖI: Không tìm thấy NsoC"
    exit 1
fi

echo "[3] Build server..."
cd "$SRC"
ant clean jar

if [ ! -f "dist/Monter.jar" ]; then
    echo "LỖI: Không tạo được dist/Monter.jar"
    exit 1
fi

echo "[4] Tạo lệnh chạy..."
cat > "$HOME/start-ninja.sh" <<'RUN'
#!/data/data/com.termux/files/usr/bin/bash
set -e

cd "$HOME/nso-v2/NsoC"

if [ ! -f "dist/Monter.jar" ]; then
    echo "Chưa có JAR, đang build..."
    ant jar
fi

echo "=== NINJA SCHOOL V2 ==="
echo "Starting server..."
echo

exec java -cp "dist/Monter.jar:lib/*" server.NinjaSchool
RUN

chmod +x "$HOME/start-ninja.sh"

echo
echo "================================"
echo " CÀI ĐẶT THÀNH CÔNG"
echo "================================"
echo
echo "Chạy server:"
echo "  bash ~/start-ninja.sh"
echo
