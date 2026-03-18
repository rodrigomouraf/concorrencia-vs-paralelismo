# Part 3 – Concurrency and Parallelism in Practice (Node.js and Go)

In this part, we will look at **asynchronous execution**, **concurrency**, and **parallelism** in real code, using **Node.js** and **Go (Golang)**.

---

## When to use Asynchrony, Concurrency, and Parallelism?

Imagine you are building a program that processes a sequence of operations. There are many instructions, but they must run in a defined order.

In this case:

- You usually do **not** need parallelism.  
- You might need **concurrency** or **asynchrony** to avoid blocking.

If you want one part of your program to **not wait** for another, many languages offer asynchronous features, such as:

- `async/await`,  
- Promises / Futures,  
- Callbacks,  
- Event loops, etc.

We can summarize:

- **Concurrency**: the program can handle multiple tasks at once (by scheduling them).  
- **Asynchrony**: a tool to perform operations without blocking the main flow.  
- **Parallelism**: actually using multiple cores to run tasks at the same time.

---

## Asynchrony in Node.js (Maria, Pedro, and Joaquina)

Let’s use an example story:

> Maria is the mother of Pedro and Joaquina.  
> She wants to bake a fruit cake.  
> She sends Pedro to the market to buy eggs (he will always succeed, to keep it simple).  
> She sends Joaquina to the fruit shop to buy oranges. If there are no oranges, Joaquina tries to buy grapes.  
> If Joaquina cannot find oranges or grapes, Maria will bake a banana cake.

We will write a simple Node.js implementation.  
This is **not** focused on clean OOP or TypeScript, just on understanding async behavior.

```js
function delay(ms = 1000) {
  return new Promise((resolve) => setTimeout(resolve, ms));
}

class GoToFruitShop {
  fruitAvailable(min = 0, max = 2) {
    this.min = Math.ceil(min);
    this.max = Math.floor(max);
    return Math.floor(Math.random() * (max - min + 1)) + min;
  }

  async buyGrapes() {
    await delay(7000);
    console.log("Joaquina managed to buy grapes");
    return "grapes";
  }

  async buyOranges() {
    await delay(6000);
    console.log("Joaquina managed to buy oranges");
    return "oranges";
  }

  async noFruit() {
    console.log("Joaquina couldn’t buy any fruit");
    await delay(4000);
    return "banana";
  }
}

class GoToMarket {
  async buyEggs() {
    console.log("Pedro managed to buy eggs");
    await delay(2000);
    return "bought eggs";
  }
}

class BakeCake {
  async sendPedroToMarket() {
    console.log("Pedro was sent to buy eggs");
    const pedro = new GoToMarket();
    return await pedro.buyEggs();
  }

  async sendJoaquinaToFruitShop() {
    console.log("Joaquina was sent to buy fruit");
    const joaquina = new GoToFruitShop();
    const fruit = joaquina.fruitAvailable();

    if (fruit === 0) {
      return await joaquina.noFruit();
    }

    if (fruit === 1) {
      return await joaquina.buyGrapes();
    }

    if (fruit === 2) {
      return await joaquina.buyOranges();
    }
  }

  async bakeCake() {
    const pedroTask = this.sendPedroToMarket();
    const joaquinaTask = this.sendJoaquinaToFruitShop();

    const eggs = await pedroTask;
    console.log("Pedro is back");
    const cakeType = await joaquinaTask;
    console.log("Joaquina is back");

    const ingredients = {
      eggs,
      cakeType,
    };

    console.log(`Maria is going to bake a ${ingredients.cakeType} cake`);
  }
}

const maria = new BakeCake();
maria.bakeCake();
```

Example terminal output:

```text
Pedro was sent to buy eggs
Pedro managed to buy eggs
Joaquina was sent to buy fruit
Joaquina managed to buy oranges
Pedro is back
Joaquina is back
Maria is going to bake a oranges cake
```

In this code:

- Total execution time depends mostly on Joaquina’s task (7, 6, or 4 seconds).  
- Pedro’s task (2 seconds) finishes earlier, so he waits.  
- Both tasks start **asynchronously**, and `await` is used only when we need the results.

If we removed the async behavior and ran everything one after the other (synchronously), the total time would be around **9–12 seconds**, because Maria would wait for each task to finish before starting the next one.

---

## Parallelism in practice (and why it is not always faster)

Remember: just because your program is parallelized does not mean it is always faster.

Why?

- When we use **parallel programming**, multiple cores need to share memory.  
- This introduces the need for synchronization (locks, atomics, etc.) to avoid race conditions.  
- Signals must travel between cores, and this takes time.

To really benefit from parallelism, you usually need:

- Heavy CPU-bound work, such as:  
  - large mathematical calculations,  
  - machine learning,  
  - email servers,  
  - database servers,  
  - messaging systems, etc.

### Note about `readFile()` in Node.js

When we use `fs.readFile()` in Node:

- Node uses the C++ library **libuv** under the hood.  
- libuv uses OS APIs and its own **thread pool** to perform I/O.  
- It manages threads for you.

So, for many I/O operations (reading and writing files), you do **not** need to manually create threads or processes to get parallel behavior. The runtime already does it.

It is always good to check what the language or runtime already parallelizes internally before adding your own complexity.

---

## Go (Golang)

As mentioned before, the first popular multi-core CPUs (like Dual Core) appeared around 2006. At that time, languages like Java, C#, C++, and Python already existed, but they were not designed from scratch with multi-core parallelism as a central idea.

Today, these languages have tools for parallel and concurrent programming, but often:

- The APIs are more complex;  
- The developer needs to manage many details manually;  
- It is easier to create bugs.

Google created **Go** around 2007 with the goal of:

- Making it easier to use the full power of modern CPUs;  
- Providing a simple model for concurrency and parallelism;  
- Letting the language and runtime manage many details for you.

---

## Goroutines

In Go, everything is built around **goroutines**.

- The `main` function runs in a goroutine.  
- Any function that you start with the `go` keyword runs as a goroutine.

Goroutines are:

- Very lightweight compared to OS threads;  
- Scheduled by the Go runtime;  
- A basic unit for concurrency in Go.

You can see how many goroutines are running with:

```go
runtime.NumGoroutine()
```

One important problem to avoid is **race conditions**:

> A race condition happens when two goroutines (or threads/processes) try to access and modify the same resource at the same time, causing unexpected behavior.

Go has a built-in tool to detect data races:

```bash
go run -race your_file.go
```

If there are race conditions, Go will show warnings when you run the program with `-race`.

To avoid race conditions, Go encourages the use of **channels**.

---

## Channels in Go

**Channels** in Go are used to communicate between goroutines and to synchronize them. They help prevent race conditions by controlling who can access which data and when.

Let’s reuse the same story we used in Node (Maria baking a cake) and implement it in Go, focusing on channels:

```go
package main

import (
    "fmt"
    "sync"
    "time"
)

var wg sync.WaitGroup

func main() {
    fruit := make(chan string)
    eggs := make(chan string)

    go buyEggs(eggs)
    go buyFruit(fruit)

    wg.Wait()
    fmt.Println("Maria will bake a", <-fruit, "cake")
}

func buyEggs(eggs chan string) {
    wg.Add(1)
    fmt.Println("Pedro arrived at the market")
    time.Sleep(2 * time.Second)
    fmt.Println("Pedro bought eggs")
    eggs <- "eggs"
    wg.Done()
}

func buyFruit(fruit chan string) {
    wg.Add(1)
    fmt.Println("Joaquina arrived at the fruit shop")
    time.Sleep(4 * time.Second)
    fmt.Println("Joaquina bought grapes")
    fruit <- "grapes"
    wg.Done()
}
```

Example output:

```text
Joaquina arrived at the fruit shop
Pedro arrived at the market
Pedro bought eggs
Joaquina bought grapes
Maria will bake a grapes cake
```

What happens here:

- `buyEggs` and `buyFruit` run concurrently as goroutines.  
- `fruit` and `eggs` are channels used to send results back.  
- `sync.WaitGroup` (`wg`) is used to wait for both goroutines to finish before printing the final message.  
- The main goroutine reads from the `fruit` channel to know which cake Maria will bake.

Channels and goroutines together give Go a very powerful and simple model for concurrency and parallelism.

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
