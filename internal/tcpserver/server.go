package tcpserver

import (
	"bytes"
	"fmt"
	"net"
	"os"
)

type TcpClient struct {
	Conn net.Conn
}

type Fn func(t *TcpClient)

func ListenPort(conn string, callback Fn) {
	// Listen for incoming connections.
	l, err := net.Listen("tcp", conn)
	if err != nil {
		fmt.Println("Error listening:", err.Error())
		os.Exit(1)
	}
	// Close the listener when the application closes.
	defer l.Close()
	fmt.Println("Listening on " + conn)
	for {
		// Listen for an incoming connection.
		conn, err := l.Accept()
		if err != nil {
			fmt.Println("Error accepting: ", err.Error())
			os.Exit(1)
		}
		// Handle connections in a new goroutine.
		go callback(&TcpClient{conn})
	}
}

func (t *TcpClient) Read() (string, error) {
	for {
		buf := make([]byte, 1024)
		reqLen, err := t.Conn.Read(buf)
		if err != nil {
			fmt.Println("Error reading:", err.Error())
			return "", err
		}
		if reqLen > 0 {
			buf = bytes.Trim(buf, "\x00")
			return string(buf), nil
		}
	}
}

func (t *TcpClient) Write(buf string) error {
	_, err := t.Conn.Write([]byte(buf))
	return err
}

func (t *TcpClient) Close() {
	t.Conn.Close()
}
