# Concurrency vs Parallelism (Big Picture)

## Definition

> Concurrency is about dealing with lots of things at once.  
> Parallelism is about doing lots of things at once.  
> — Rob Pike

A simple example of **concurrency**:

I am writing this article. Then I stop to drink some coffee. Then I return to the article. Then I take another sip of coffee and go back again to writing.

I am not doing both actions at the exact same time, but I am **handling** both activities and making progress on each one. This is concurrency.

An example of **parallelism**:

I am writing this article while a kettle with water is heating on the stove. The two tasks are really happening at the same time. This is parallelism.

At first, it looks like parallelism is always better. But this is not always true. In the parallelism example, I must pay attention to the water on the stove. I need to check if it already boiled or if I am not starting a fire in the house. Parallelism brings more complexity and more things to manage.

---

## Synchronous, Asynchronous, Concurrency, and Parallelism

We can think about these concepts in simple terms:

- **Synchronous**: one instruction waits for the previous one to finish.  
- **Asynchronous**: an instruction can start and then let the program continue, and later we get the result.  
- **Concurrency**: the system can deal with several tasks at once, usually by switching between them.  
- **Parallelism**: several tasks are actually executed at the same time, using multiple CPU cores.

Concurrency is mostly about **structure** and **scheduling**.  
Parallelism is about **hardware** and using more cores.

Asynchrony is a tool to **avoid blocking** and to enable concurrency.  
It does not automatically mean “parallel execution”.

---

## Example: cleaning the house

A simple story to understand concurrency and context switching:

You need to wash the dishes and clean the driveway. Your sink is slow to drain, so you decide to work on both tasks “at the same time” to save time.

With only one “worker” (one thread), you might do:

1. Pick up the sponge and detergent.  
2. Wash some plates.  
3. Put away the sponge and detergent.  
4. Get the bucket, broom, and squeegee.  
5. Clean part of the driveway.  
6. Put away the bucket, broom, and squeegee.  
7. Go back to the dishes and pick up everything again…  

This is concurrent work with a lot of **switching** between tasks. Every time you switch, you need to “save” and “restore” your tools. This is similar to **context switching** in a CPU.

If you had more resources (for example, two people helping you, or better organization of tools), you could reduce the overhead of switching and maybe even do some things in parallel.

---

## Why does this difference matter?

Understanding the difference between concurrency and parallelism helps you when you are:

- Choosing between **threads** and **processes**.  
- Deciding if you just need **async code**, or if you really need to use **multiple cores**.  
- Designing systems that are easier to reason about and to debug.  
- Avoiding over-engineering (adding parallelism where async concurrency is enough).

In the next parts, we will see:

- How CPUs, processes, threads, and cache memory are related to concurrency.  
- How to use concurrency and parallelism in practice with Node.js (async) and Go (goroutines and channels).
