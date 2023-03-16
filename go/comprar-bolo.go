package main

//Apresentar com Pedro a 2 e 9 segundos

import (
	"fmt"
	"sync"
	"time"
	"runtime"
)

var wg sync.WaitGroup

func main() {
	start := time.Now()

	fmt.Println("Número de processadores inicio: ", runtime.NumCPU())
	fmt.Println("Número de coroutine inicio: ", runtime.NumGoroutine())

	buscarFruta := make(chan string)
	buscarOvos := make(chan string)

	go comprarOvos(buscarOvos)	
	go comprarFrutas(buscarFruta)	

	fmt.Println("Número de coroutine meio: ", runtime.NumGoroutine())

	select {
		case <-buscarOvos:
			iniciarPreparo()
    }

	fmt.Println("Maria vai fazer bolo de", <-buscarFruta)

	elapsed := time.Since(start)
    fmt.Printf("Tempo decorrido: %s\n", elapsed)	
}

func iniciarPreparo(){
    fmt.Println("Maria está começando a preparar a massa");
    time.Sleep(3 * time.Second)
    fmt.Println("Maria precisa do ingrediente da Joaquina");
}

func comprarOvos(buscarOvos chan string) {
	fmt.Println("Pedro chegou no mercado")
	time.Sleep(2 * time.Second)
	fmt.Println("Pedro comprou ovos")
	buscarOvos <- "comprei ovos"
}

func comprarFrutas(buscarFruta chan string) {
	fmt.Println("Joaquina chegou na frutaria")
	time.Sleep(4 * time.Second)
	fmt.Println("Joaquina comprou uva")
	buscarFruta <- "uva"
}
