package main

/*
 #cgo CFLAGS: -D_FORTIFY_SOURCE=0 -fno-stack-protector
 #include <stdlib.h>
 #include "main.h"
*/
import "C"
import (
	"ctf/internal/tcpserver"
	"fmt"
	"unsafe"
)

func main() {
	fmt.Println("Starting...")
	tcpserver.ListenPort("0.0.0.0:3333", handleRequest)
}

func handleRequest(t *tcpserver.TcpClient) {
	defer t.Close()
	t.Write("Hello! What's your name?\n> ")
	if name, err := t.Read(); err == nil {
		cs := C.CString(name)
		defer C.free(unsafe.Pointer(cs))
		str := C.getflag(cs)
		gostr := C.GoString(str)
		t.Write("Nice to meet you, " + gostr + "\n")
	}
}
