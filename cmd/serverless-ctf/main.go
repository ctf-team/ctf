package main

import (
	"fmt"
	"github.com/gorilla/mux"
	"log"
	"net/http"
)

func main() {
	fmt.Println("Starting up...")
	router := mux.NewRouter()
	fs := http.FileServer(http.Dir("./assets"))

	router.PathPrefix("/").Handler(fs)

	log.Fatal(http.ListenAndServe("0.0.0.0:3334", router))
}
