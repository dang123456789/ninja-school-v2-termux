import subprocess
file_path = "ip.txt"
file = open(file_path, "w")
port = 14444
command = f"netstat -nao | findstr :{port}"
output = subprocess.check_output(command, shell=True).decode("utf-8")
ip_sockets = {}
lines = output.splitlines()
for line in lines:
    parts = line.split()
    if len(parts) >= 5 and parts[1].endswith(f":{port}"):
        ip_address = parts[2].split(":")[0]
        socket_id = parts[4]
        if ip_address not in ip_sockets:
            ip_sockets[ip_address] = []
        ip_sockets[ip_address].append(socket_id)
for ip, sockets in ip_sockets.items():
    file.write(f"IP: {ip}\n")
    file.write(f"Sockets: {len(sockets)}\n\n")
file.close()
print(f"Đã ghi địa chỉ IP và số socket vào tệp {file_path}")
