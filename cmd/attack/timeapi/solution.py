import socket
import re

HOST = '127.0.0.1'
PORT = 3002

s = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
s.connect((HOST, PORT))
s.sendall(b'token\n')
data = s.recv(1024)
s.close()
# data should contain our token, so we have to parse it.
output = data.decode("utf-8")
match = re.findall(r"'(.+?)'", output, re.MULTILINE)
if match:
    key = match[0]
    print("Got key! " + key)
    s = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
    s.connect((HOST, PORT))
    s.sendall(b'secret ' + bytes(key, "utf-8") + b'\n')
    data = s.recv(1024)
    print(data.decode("utf-8"))
else:
    print("Could not match: "+data.decode("utf-8"))

s.close()