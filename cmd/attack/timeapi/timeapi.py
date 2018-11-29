# FLAG-ChBOFWmBRUlif26eZt5R
import cmd
import socket
import threading
import shlex
import atexit
import sys

bind_ip = "0.0.0.0"
bind_port = 3001

server = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
server.setsockopt(socket.SOL_SOCKET, socket.SO_REUSEADDR, 1)
server.bind((bind_ip, bind_port))
server.listen(1000)

print('Listening on {}:{}.'.format(bind_ip, bind_port))


def parse_args(pargs):
    nargs = shlex.split(pargs)
    print(nargs)
    return cmd.switcher(nargs)


def handle_connection(client_socket):
    req = client_socket.recv(1024)
    print(req)
    # req is our data.
    client_socket.send(parse_args(req) + "\n")
    client_socket.close()


def close_socket():
    server.close()


atexit.register(close_socket)
while True:
    try:
        client_socket, addr = server.accept()
        print("Accepted connection from {}:{}".format(addr[0], addr[1]))
        client_handler = threading.Thread(
            target=handle_connection,
            args=(client_socket,)
        )
        client_handler.start()
    except KeyboardInterrupt:
        print("Keyboard interruption...")
        sys.exit(0)

