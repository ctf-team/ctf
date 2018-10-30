package main

/*
	#include <stdio.h>
	#include <stdlib.h>

	static void printme(char *test) {
		printf("%s", test);
	}
*/
import "C"
import "unsafe"

func main() {
	cs := C.CString("hey from C!")
	C.printme(cs)
	C.free(unsafe.Pointer(cs))
}
