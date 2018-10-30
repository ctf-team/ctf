package main

import (
	"bytes"
	"fmt"
	"net"
	"os"
	"strings"
)

const (
	CONN_HOST = "0.0.0.0"
	CONN_PORT = "3333"
	CONN_TYPE = "tcp"
)

var challengeList map[string]string

func main() {
	challengeList = make(map[string]string)
	// challenge list here.
	challengeList["challenge-1"] = "abcdefghijklmnopqrstuvwxyz"

	// Listen for incoming connections.
	l, err := net.Listen(CONN_TYPE, CONN_HOST+":"+CONN_PORT)
	if err != nil {
		fmt.Println("Error listening:", err.Error())
		os.Exit(1)
	}
	// Close the listener when the application closes.
	defer l.Close()
	fmt.Println("Listening on " + CONN_HOST + ":" + CONN_PORT)
	for {
		// Listen for an incoming connection.
		conn, err := l.Accept()
		if err != nil {
			fmt.Println("Error accepting: ", err.Error())
			os.Exit(1)
		}
		// Handle connections in a new goroutine.
		go handleRequest(conn)
	}
}

func writeResponse(resp string, conn net.Conn) {
	writeResponseWithoutInput(resp+"\n> ", conn)
}

func writeResponseWithoutInput(resp string, conn net.Conn) {
	conn.Write([]byte(resp))
}

// Handles incoming requests.
func handleRequest(conn net.Conn) {
	defer conn.Close()

	writeResponse("To see the list of available commands, please type \"help\".\n", conn)
	// Close the connection when you're done with it.
	for {
		buf := make([]byte, 1024)
		reqLen, err := conn.Read(buf)
		if err != nil {
			fmt.Println("Error reading:", err.Error())
			break
		}
		if reqLen > 0 {
			// Parse available commands.
			buf = bytes.Trim(buf, "\x00")
			command := string(buf)
			command = strings.TrimRight(command, "\n")
			// Split by ' '
			possibleStrings := strings.Split(command, " ")
			switch possibleStrings[0] {
			case "list":
				var challenges string
				for key, _ := range challengeList {
					challenges += key + "\n"
				}
				writeResponse("Loaded challenges:\n"+challenges, conn)
				break
			case "solve":
				if len(possibleStrings) < 3 {
					writeResponse("Incorrect number of parameters.\n", conn)
					break
				}
				// Check if param 2 is in our list of possible challenges.
				for key, val := range challengeList {
					if key == possibleStrings[1] {
						// check val
						if val == possibleStrings[2] {
							writeResponse("Congratulations! That's the correct flag for '"+key+"'.\n", conn)
						} else {
							writeResponse("Sorry, that flag is not correct. Try harder!\n", conn)
						}
					} else {
						writeResponse("Sorry, that challenge does not exist.\n", conn)
					}
				}
				break
			case "help":
				writeResponse("===\nPossible commands:\nlist - List the challenges currently loaded.\nsolve <challenge> <flag> - "+
					"Attempt to solve the flag for a given challenge.\nhelp - Show this message.\nexit - Closes this terminal.\n===\n", conn)
				break
			case "exit":
				writeResponseWithoutInput("Bye!\n", conn)
				return
			}

		}
	}
}
