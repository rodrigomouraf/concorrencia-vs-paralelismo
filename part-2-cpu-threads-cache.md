# Part 2 – CPUs, Processes, Threads, and Cache Memory

In this part, we will look at the **mechanics** behind concurrency and parallelism: how the CPU is built, how processes and threads work, and how cache memory helps performance.

---

## CPU and Cores

A CPU (Central Processing Unit) today usually has several **cores**.

A **core** is basically a physical processor inside the CPU. For example, the Intel Core 2 Duo is a CPU with **two cores** inside one chip.

We can say:

- A **physical core** is a real, physical processing unit.  
- A **logical core** (logical processor) is a virtual execution unit that the OS can schedule on.  

Many CPUs use technologies like Hyper-Threading, so:

- Number of physical processors = number of physical cores.  
- Number of logical processors is usually **double** the number of physical cores.

---

## Processes

Creating new **processes** is often simpler conceptually than using threads. With processes, you generally do not need to manually manage mutexes, race conditions, or deadlocks inside the same memory space, because each process has its own memory.

However, creating a process is **more expensive** than creating a thread.

Different operating systems handle process creation in different ways:

- On **Linux**, creating a process is relatively cheap.  
- On **macOS**, it is more expensive than on Linux.  
- On **Windows**, process creation can be an order of magnitude more expensive than on Linux (AKITANDO #43).

On Linux:

- A child process points to the memory of the parent process.  
- New resources created by that child are restricted to the child’s scope.

On Windows:

- The child process performs a **copy** of the parent’s memory.  
- This makes it more expensive.

Because of this, many applications try to avoid creating too many processes.

---

## Threads

A **thread** is a sequence of instructions that a process must execute.

Threads share the same memory space inside a process, which makes communication between them fast, but also brings the risk of race conditions and the need for synchronization.

Threads are **cheaper** to create than processes, but they still have a cost:

- Memory allocation for the thread stack.  
- Context switching between threads.  
- Transitions between user-land and kernel-land.

A physical core can support one or two **hardware threads** (logical threads) at the same time.

Why usually not more than two?

Because adding more hardware threads increases the complexity and physical size of the core. This can increase the distance that the electrical signal must travel, and in practice this may force the CPU to use a lower clock speed to remain stable.

---

## Threads vs Processes

When should we choose threads or processes? There is no one correct answer. Programming is always a trade-off between **isolation**, **performance**, and **complexity**.

A good real-world example is the **Google Chrome** browser.

In older browsers, many tabs might share one process. If one tab crashed or had a serious problem, it could break all the other tabs in that window. You could lose everything you had open there.

Modern Chrome usually creates a **separate process for each tab**. What is the benefit?

- If one tab crashes, you can close only that tab and the others keep working.

The cost:

- More processes mean more memory (RAM) usage.  
- That is one of the reasons Chrome is known to use a lot of memory.

So:

- **Processes**: better isolation and stability, but heavier.  
- **Threads**: lighter and faster to create, but with shared memory and more risk of bugs.

---

## Cache Memory

To avoid conflicts between cores, there is a part of the CPU dedicated to **memory control**. Cores send or request information from this unit, and it communicates with the main memory (RAM).

**Cache memory** is memory located **inside** the CPU. It is used to avoid going to RAM all the time, because:

- Accessing RAM is slower.  
- Data must travel through the memory bus.

Cache is usually divided into levels:

- **L1** – closest to the core, smallest and fastest.  
- **L2** – larger than L1, a bit slower, still quite fast.  
- **L3** – larger and shared between cores, slower than L1/L2 but faster than RAM.

### Two-level design

In a two-level design:

- L1 is close to the core and is usually split into:
  - an instruction cache (for instructions), and  
  - a data cache (for data).  
- L2 stores preloaded information from RAM.

### Three-level design

In a three-level design:

- L1 is the closest cache, with separate instruction and data caches.  
- L3 is a larger shared cache for several cores (similar to L2 in the two-level design).  
- L2 works as a “mirror” or a faster layer dedicated to each core between L1 and L3.

### Example: baking a cake

Imagine you are going to bake a cake:

1. You read the recipe and see the ingredients: eggs, flour, sugar, etc.  
2. You go to the cupboard and take all these items.  
3. You put them on a table next to you.

This table is like the **cache**.

If you had to go to the cupboard every time you needed a spoon of sugar or a bit of flour, it would be much more work. The **path to the cupboard** is like the **memory bus**, and the cupboard is like **RAM**.

---

## Running Programs with One Thread

Let’s continue with the **Dual Core** example, but imagine the CPU has only one **hardware thread** per core.

When a core executes its tasks, it does not run all tasks at the same exact time. Instead, it switches between them quickly. The computer is not stopped doing only one thing.

In old operating systems like **DOS**, the OS executed just **one program** at a time. That meant:

- Either you used the text editor,  
- Or you used the calculator,  
- But not both.

Modern systems brought the concept of **concurrent task execution**. The scheduler allows a thread to:

- Run a bit of task A,  
- Then a bit of task B,  
- Then a bit of task C,  
- And then back to task A, and so on.

To do this, the system must **save** the state of each task (registers, program counter, etc.) in memory and **restore** it later. This is context switching.

### House cleaning example (again)

You need to wash the dishes and clean the driveway. Your sink is slow to drain, so you decide to do both tasks in an interleaved way.

With only **one thread**, you must:

- Start washing dishes,  
- Stop and prepare the driveway cleaning tools,  
- Stop and put away tools,  
- Return to dishes,  
- And repeat…

You can see that constantly **preparing and putting away** tools is expensive. This is like heavy context switching.

---

## Running Programs with Two Threads per Core

The idea behind **two hardware threads per core** (like Hyper-Threading) is to optimize the example above.

Now, the core can handle two “streams” of work at the same time internally. You still do **one physical instruction at a time per execution unit**, but the CPU can:

- Keep resources and data ready for both threads,  
- Reduce idle time when one thread is waiting for data,  
- Use the pipeline more efficiently.

In the house cleaning example, this means:

- You can keep tools for both tasks already separated and close to you.  
- When one task is “waiting” (for example, water draining), you can progress on the other.  
- You reduce the overhead of putting things away and taking them out again.

Modern CPUs also use **pipelining**:

- One part of the CPU fetches the next instruction.  
- Another part decodes it.  
- Another part executes it.

This, together with cache (especially **L1**), improves performance even when you only have one core and are using concurrency instead of real parallelism.
