package main

import (
	"ctf/internal/tcpserver"
	"strings"
)

const (
	CONN_HOST = "0.0.0.0"
	CONN_PORT = "3333"
)

var challengeList map[string]string

func main() {
	challengeList = make(map[string]string)
	// challenge list here.
	challengeList["rtlo"] = "FLAG-G3hTUoGN9OG1edz53nKi"

	tcpserver.ListenPort(CONN_HOST+":"+CONN_PORT, handleRequest)
}

// Handles incoming requests.
func handleRequest(t *tcpserver.TcpClient) {
	defer t.Close()

	t.Write("To see the list of available commands, please type \"help\".\n")
	// Close the connection when you're done with it.
	for {
		if command, err := t.Read(); err != nil {
			return
		} else {
			command = strings.TrimRight(command, "\n")
			// Split by ' '
			possibleStrings := strings.Split(command, " ")
			switch possibleStrings[0] {
			case "list":
				var challenges string
				for key, _ := range challengeList {
					challenges += key + "\n"
				}
				t.Write("Loaded challenges:\n\n> " + challenges)
				break
			case "solve":
				if len(possibleStrings) < 3 {
					t.Write("Incorrect number of parameters.\n\n> ")
					break
				}
				// Check if param 2 is in our list of possible challenges.
				for key, val := range challengeList {
					if key == possibleStrings[1] {
						// check val
						if val == possibleStrings[2] {
							t.Write("Congratulations! That's the correct flag for '" + key + "'.\n\n> ")
						} else {
							t.Write("Sorry, that flag is not correct. Try harder!\n\n> ")
						}
					} else {
						t.Write("Sorry, that challenge does not exist.\n\n> ")
					}
				}
				break
			case "help":
				t.Write("===\nPossible commands:\nlist - List the challenges currently loaded.\nsolve <challenge> <flag> - " +
					"Attempt to solve the flag for a given challenge.\nhelp - Show this message.\nexit - Closes this terminal.\n===\n\n> ")
				break
			case "exit":
				t.Write("Bye!\n")
				return
			}
		}
	}
}
