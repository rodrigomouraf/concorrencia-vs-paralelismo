package main

// programa da Ellen Korbes -> https://github.com/vkorbes/aprendago/blob/master/c%C3%B3digo/18_concorrencia/05_race_condition/main.go
// analisar se um programa tem race condition -> go run -race nome_arquivo

import (
	"fmt"
	"runtime"
	"sync"
)

func main() {

	fmt.Println("CPUs:", runtime.NumCPU())
	fmt.Println("Goroutines:", runtime.NumGoroutine())

	contador := 0
	totaldegoroutines := 1000

	var wg sync.WaitGroup
	wg.Add(totaldegoroutines)

	for i := 0; i < totaldegoroutines; i++ {
		go func() {
			v := contador
			runtime.Gosched()
			v++
			contador = v
			wg.Done()
		}()
		fmt.Println("Goroutines:", runtime.NumGoroutine())
	}

	wg.Wait()
	fmt.Println("Goroutines:", runtime.NumGoroutine())
	fmt.Println("Valor final:", contador)

}
