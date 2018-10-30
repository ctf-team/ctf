package main

/*
	#include <stdlib.h>
	#include "test.h"
*/
import "C"
import (
	"fmt"
	"unsafe"
)

func main() {
	cs := C.CString("hey from C!")
	C.printme(cs)
	C.free(unsafe.Pointer(cs))
	fmt.Println("Hey from Golang!")
}
