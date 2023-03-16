package main

import (
	"fmt"
	"sync"
	"time"
)

var wg sync.WaitGroup

func main() {
	fruta := make(chan string)
	ovos := make(chan string)

	go comprarOvo(ovos)
	go comprarFrutas(fruta)
	wg.Wait()
	fmt.Println("Maria vai fazer bolo de", <-fruta)
}

func comprarOvo(ovos chan string) {
	wg.Add(1)
	fmt.Println("Pedro chegou no mercado")
	time.Sleep(2 * time.Second)
	fmt.Println("Pedro comprou ovos")
	ovos <- "comprei ovos"
	wg.Done()
}

func comprarFrutas(fruta chan string) {
	wg.Add(1)
	fmt.Println("Joaquina chegou na frutaria")
	time.Sleep(4 * time.Second)
	fmt.Println("Joaquina comprou uva")
	fruta <- "uva"
	wg.Done()
}
