package main

import (
	"ctf/internal/tcpserver"
	"fmt"
	"io/ioutil"
	"os"
	"os/exec"
	"path"
	"strings"
)

func main() {
	fmt.Println("Starting up")
	tcpserver.ListenPort("0.0.0.0:3333", handleRequest)
}

func handleRequest(t *tcpserver.TcpClient) {
	defer t.Close()
	output, _ := exec.Command("/bin/ls", "-al", "./assets/").Output()
	t.Write("Welcome! Here is a list of the files in the current directory:\n" + string(output) + "\nPlease type the name of the file you want to read:\n> ")
	for {
		if fileName, err := t.Read(); err == nil {
			fileName = strings.TrimRight(fileName, "\n")
			if fileName == "flag.txt" {
				t.Write("Sorry, that filename is blacklisted.\n> ")
			} else {
				base := path.Base(fileName)
				fmt.Println("User reading:", fileName)
				if _, err := os.Stat("./assets/" + base); os.IsNotExist(err) {
					t.Write("Sorry! The file " + base + " does not exist.\n> ")
				} else {
					dat, err := ioutil.ReadFile("./assets/" + base)
					if err != nil {
						t.Write("There was an error while reading your file.\n> ")
					} else {
						t.Write(string(dat) + "\n")
						return
					}
				}
			}
		} else {
			return
		}
	}
}
