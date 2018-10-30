package main

import (
	"github.com/gorilla/mux"
	"log"
	"net/http"
)

func main() {
	router := mux.NewRouter()
	fs := http.FileServer(http.Dir("./assets/"))
	router.Handle("/", fs)

	log.Fatal(http.ListenAndServe("0.0.0.0:3334", router))
}
