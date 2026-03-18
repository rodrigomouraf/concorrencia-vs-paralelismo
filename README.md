Copiar

# Concurrency vs Parallelism Notes

This repository contains my personal study notes about **concurrency**, **parallelism**, and how they connect to real-world programming.

The goal is to go beyond simple definitions and show how these ideas relate to:

- How **CPUs** actually work (cores, threads, cache)
- The difference between **processes** and **threads**
- **Synchronous vs asynchronous** execution
- **Concurrency vs parallelism** in practice
- Real examples in **Node.js** (async/await, libuv) and **Go** (goroutines, channels)

The content is split into three parts to make it easier to read and reuse:

- [Part 1 – Concurrency vs Parallelism (the big picture)](./part-1-concurrency-vs-parallelism.md)  
  High-level concepts, mental models, and simple analogies (coffee, house cleaning, etc.).

- [Part 2 – CPUs, processes, threads, and cache memory](./part-2-cpu-threads-cache.md)  
  How the hardware and the OS influence concurrency: cores, logical vs physical processors, process creation cost on different OSes, and cache (L1/L2/L3).

- [Part 3 – Concurrency and parallelism in practice (Node.js and Go)](./part-3-concurrency-node-go.md)  
  Asynchronous code in Node.js, when you really need parallelism, and how Go uses goroutines and channels to make concurrency easier and safer.

These notes are not meant to be a formal textbook.  
They are the way I organized what I’ve learned, with examples, analogies, and references that helped me understand the topic more deeply.

Feel free to read, fork, or open issues if you want to discuss any of the ideas.

---

## References

Some sources that helped build these notes:

- Talks and videos on concurrency and parallelism (including Rob Pike’s talks)  
- Articles on CPU cores, threads, and how processors are built  
- Posts and videos explaining cache memory (L1, L2, L3)  
- Articles on multithreading and how it uses or does not use multiple cores  
- Documentation and talks on Node.js, libuv, and its event loop  
- Tutorials and blog posts on Go, goroutines, and channels  
- Articles comparing async vs multithreading and concurrency in modern languages

Concurrency
https://www.youtube.com/watch?v=SgNrDqdNVG4
https://www.youtube.com/watch?v=oV9rvDllKEg

CPU
https://www.tomshardware.com/news/cpu-core-definition,37658.html
https://www.meupositivo.com.br/doseujeito/tecnologia/o-que-e-processador/
https://thunderboltlaptop.com/what-is-a-multi-threaded-cpu/
https://www.youtube.com/watch?v=CiMnb06C4po
https://www.youtube.com/watch?v=NU5DSgPeQlY
https://www.youtube.com/watch?v=lpxidDBi3GI&list=PLOPhmNgGl9gQTycQXcv2ytnkcWUSUG2IZ&index=10
https://itigic.com/pt/how-is-a-processor-made-intel-explains/

GoLang
https://aprendagolang.com.br/2021/11/12/entenda-a-diferenca-entre-concorrencia-e-paralelismo/
https://aprendagolang.com.br/2021/11/19/o-que-sao-e-como-funcionam-as-goroutines/
https://www.youtube.com/watch?v=unofca9ooS4&list=PLCKpcjBB_VlBsxJ9IseNxFllf-UFEXOdg&index=125
https://stackoverflow.com/questions/13596186/whats-the-point-of-one-way-channels-in-go

Cache memory
https://www.youtube.com/watch?v=UxHM7_BaMTY
https://www.techtudo.com.br/noticias/2016/10/o-que-e-memoria-cache-entenda-sua-importancia-para-o-pc.ghtml

Multithreading
https://itigic.com/multi-threaded-execution-on-cpu-how-it-works-in-performance/
https://www.quora.com/Does-multithreading-use-multiple-cores?share=1

Node
https://www.youtube.com/watch?v=AGLq2stqAyYs

Parallelism
https://www.mailgun.com/blog/it-and-engineering/how-use-parallel-programming/

Threads
https://www.geeksforgeeks.org/what-are-threads-in-computer-processor-or-cpu/
https://www.youtube.com/watch?v=xNBMNKjpJzM

Others
https://www.linuxdescomplicado.com.br/2016/11/dirty-cow-o-quao-prejudicial-vulnerabilidades-como-essa-podem-ser.html
https://www.apriorit.com/dev-blog/652-virtualization-golang-c-java-for-building-microservices
https://www.treinaweb.com.br/blog/concorrencia-paralelismo-processos-threads-programacao-sincrona-e-assincrona/
https://deepu.tech/concurrency-in-modern-languages/
https://www.baeldung.com/cs/async-vs-multi-threading
https://www.techtarget.com/searchstorage/definition/race-condition
