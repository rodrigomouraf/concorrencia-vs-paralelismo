

# Concorrência vs Paralelismo

## Definição

Concorrência é basicamente a capacidade de lidar com várias coisas de uma só vez, enquanto paralelismo é a capacidade de lidar com várias coisas ao mesmo tempo. - *Rob Pike*

Um exemplo para deixar um pouco mais claro sobre concorrência seria eu montando esse artigo e parando para tomar um café depois retornando para digitar o artigo parando para dar mais uma goladinha no café e retornando pro artigo.

Um exemplo de paralelismo é eu escrevendo esse arquivo e uma chaleira de água esquentando no fogão. 

Por mais que pareça que paralelismo é a melhor opção, nem sempre isso se torna verdadeiro. Veja que  eu no exemplo do paralelismo preciso sempre estar atento a água no fogão, verificando se já ferveu, se eu não coloquei fogo na casa sem querer.

## Um pouco sobre a mecânica

### CPU

Primeiro vamos analisar o computador em sua essência. Uma CPU tem hoje em dia diversos cores que são os processadores, ou seja, pegando o primeiro Intel com mais de um processador, "popular" como exemplo,  temos o Core 2 Duo, e o que é esse CPU? ele nada mais é que uma bolacha (e não biscoito), com dois processadores lá dentro, referenciando um pouco mais o processador que é o mesmo que core que seria a própria ULA.

### Thread

Agora vamos falar um pouco sobre Thread, um processador pode ter uma ou duas Threds, por que não mais que isso? porque isso aumenta o tamanho do processador que aumenta o tempo do sinal elétrico ir de um canto a outro do processador, isso faria que tivéssemos que diminuir o clock do cpu. 

As Threads são as sequencias de cálculos que deverão ser realizados.

### Memória Cache

Para evitar conflitos entre os cores existe uma parte na CPU dedicada a controlar o acesso a memória, os cores pedem ou enviam informação para essa unidade de controle e esse módulo de gerenciamento de memória que comunica efetivamente a informações com a memória principal.

Cache é uma memória localizada dentro do CPU, serve para evitar que o core precise se comunicar com a RAM a todo momento, isso é necessário para evitar o custo de deslocamento da informação percorrendo o barramento.

A memória cache pode ser divida arquiteturalmente em duas formas, em uma se trabalha com dois níveis de memória e em outra com três níveis. É definida como L1, L2 e L3, a mais perto do core leva o nome de L1 e a mais longe L2 ou L3.

O Design em dois níveis é em L1 (a memória mais perto do processador), repartido em duas memórias cache menores, sendo uma para execução e outra para os dados do core e o segundo nível, L2, são as informações pré carregadas do módulo de gerenciamento de memória da CPU que foram carregadas da RAM. 

O Design em três níveis continua com L1 sendo da mesma forma explicada acima, e o L2 passa a ser o L3 mas o L2 passa a ser um espelho das informações do de L3 que é o L2 do exemplo acima, a diferença é que L2 para três níveis fica dedicado ao core. 

Um exemplo do motivo de usar cache seria uma pessoa indo fazer um bolo, provavelmente a pessoa lerá o que será preciso para preparar o bolo, ovo, farinha, etc, pegará esses itens e os colocará em uma mesa perto dela para o preparo, essa mesa no caso seria a memória cache. Caso a pessoa ficasse indo toda a hora buscar os itens no armário e voltando isso seria o exemplo de não utilizar memória cache e os barramentos seriam os caminhos que ela teria que percorrer.

### Executando programas apenas com uma Thread

Vamos continuar com o exemplo do Dual Core, essa CPU não tem duas threads e sim uma, o que ocorre quando um processador dessa CPU executa suas tarefas são uma alternância entre elas, o computador não fica parado realizando apenas uma tarefa, o DOS era assim, realizava apenas uma tarefa por vez, o que significa que ou você usava o bloco de notas ou você usava a calculadora. 

Depois que os SO's vieram com esse conceito de execução de tarefas concorrentes isso faz com que a thread execute um pouco de uma tarefa e um pouco de outra e um pouco de outra e daí volte para a primeira assim sucessivamente.

Essa thread é chaveada para processar um pouco de uma task e um pouco de outra, assim sucessivamente até a task terminar, para isso ocorrer precisa ser salvo na memória os resultados pré processados para voltar a os consumir quando chegar a vez da task na fila.

Um exemplo seria você limpando a casa, digamos que você precisa lavar a louça e a calçada, mas sua pia está com defeito e demora o escoamento, então para adiantar você resolve fazer as duas tarefas ao mesmo tempo.

No exemplo acima utilizando apenas um thread você teria que começar pegando a esponja colocando detergente, lavar alguns pratos, guardar a esponja e o detergente ir pegar o balde, pegar a vassoura e o rodo, lavar um pouco da calçada , guardar tudo que você tinha pego e voltar para a louça, pegar novamente a esponja e o detergente... Nota como isso é custoso, eu tenho que ficar guardando os materiais e pegando novamente.

### Executando programas com duas Threads

O conceito de duas threads dentro de um core vem para otimizar o último exemplo demonstrado no tema acima. Agora o core do processador dividi para mim as tarefas em duas threads, eu ainda tenho que fazer uma de cada vez, concorrentemente, mas eu consigo deixar os materiais já separados para as duas tarefas. 

Claro que hoje existem ainda mais conceitos importantes, como o pipeline de processamento em que dividimos a unidade de controle de CPU utilizando unidade de busca de instrução e unidade de execução de instruções onde enquanto uma task está executando alguma instrução a outra já consegue ir em busca da instrução e a própria utilização da memória cache L1. 

### Multithreading

É a capacidade de um programa conseguir ser executado utilizando não apenas um processador mas também outros, é aqui que entra o conceito de paralelismo.

Em um único processador, multithreading dá a ilusão de execução em paralelo. Na realidade, o processador está trocando usando um algoritmo de escalonamento. Ou é a comutação baseada em uma combinação de entradas externas (interrupções) e como os threads foram priorizados.

## Quando usar Assincronismo, Concorrência e Paralelismo?

Digamos que você esteja montando um programa que processa execuções sequenciais, mesmo sendo várias instruções você não precisa utilizar paralelismo e sim concorrência, caso você queira que uma instrução não espere outra execução, muitas linguagens dão a oportunidade de você utilizar assincronismo. 

### Assincronismo

O modelo assíncrono permite que várias coisas ocorram ao mesmo tempo, você consegue dividir sua execução e ele rodara como mais uma task dentro do seu core, seria um multithreading dentro do seu processo.

Vamos montar alguns exemplos de assincronismo em três linguagens distintas para entendermos:

Problema proposto:

Maria, mãe de Pedro e Joaquina, quer fazer um bolo de fruta, para isso ela mandou Pedro ao mercado comprar ovos (para simplificar o código ele vai conseguir comprar os ovos). Ela também mandou Joaquina a frutaria para comprar laranja e caso não tenha laranja uva. Se Joaquina não conseguir comprar nem laranja e nem uva Maria vai fazer um bolo de banana.

Vamos as implementações:

Abaixo temos a implementação do problema de uma forma bem simples, não utilizamos os conceitos importantes de POO e também não utilizamos typescript.

```js
function delay(ms = 1000) {
    return new Promise((resolve) => setTimeout(resolve, ms));
}

class IrAFrutaria { 
    frutaDisponivel(min = 0, max = 2){
        this.min = Math.ceil(min);
        this.max = Math.floor(max);
        return Math.floor(Math.random() * (max - min + 1)) + min;
    }
    
    async comprarUva() {
        await delay(7000);
        console.log("Joaquina consegui comprar uva");
        return "uva";
    }

    async comprarLaranja() {
        await delay(6000);
        console.log("Joaquina consegui comprar laranja");
        return "laranja";
    }

    async naoTemFruta() {
        console.log("Joaquina não conseguiu comprar fruta");
        await delay(4000);
        return "banana";
    }
}

class IrAoMercado {
    async comprarOvos() {
        console.log("Pedro conseguiu comprar ovos");
        await delay(2000);
        return "comprei ovos";
    }  
}

class FazerBolo {    
    async enviarPedroAoMercado()  {
        console.log("Pedro foi enviado comprar ovos");
        const pedro = new IrAoMercado();
        return await pedro.comprarOvos();
    }
    
    async enviarJoaquinaAFrutaria()  {
        console.log("Joaquina foi enviada comprar fruta");
        const joaquina = new IrAFrutaria();
        const fruta = joaquina.frutaDisponivel();

        if(fruta === 0){
            return await joaquina.naoTemFruta();
        }

        if(fruta === 1){
            return await joaquina.comprarUva();
        }
        
        if(fruta === 2){
            return await joaquina.comprarLaranja();
        }
    }

    async fazerBolo(){
        const tarefaPedro = this.enviarPedroAoMercado();
        const tarefaJoaquina = this.enviarJoaquinaAFrutaria();

        const ovos = await tarefaPedro;
        console.log("Pedro chegou");
        const tipoBolo = await tarefaJoaquina;
        console.log("Joaquina chegou");
        
        const condimentos = {
            ovos,
            tipoBolo 
        }

        console.log(`Maria está indo fazer bolo de ${condimentos.tipoBolo}`);
    }
}

const maria = new FazerBolo();
maria.fazerBolo();

```

saída terminal

```bash
Pedro foi enviado comprar ovos
Pedro conseguiu comprar ovos
Joaquina foi enviada comprar fruta
Joaquina consegui comprar laranja
Pedro chegou
Joaquina chegou
Maria está indo fazer bolo de laranja
```



No código acima o tempo de execução fica dependendo apenas do retorno da  função da Joaquina, 7, 6 ou 4 segundos, pois ela tem os tempos mais longos que o do Pedro em determinados casos, note que se pegarmos e arrancarmos os métodos assíncronos os tempos da aplicação passarão a rodar em volta de 9 à 12 segundos em tempo de resposta em média.

### Paralelismo

Lembrando do exemplo que demos lá no inicio do artigo, não é porque seu programa está paralelizado que ele está rodando mais rapidamente.

Mas porque isso pode não ser efetivo? Quando usamos a programação em paralelo o core precisa compartilhar a memória com outros cores, isso gera uma necessidade de gerenciamento de possíveis erros e também temos o fato de a corrente elétrica ter que caminhar de um core a outro para trocar informações.

Teríamos que montar um programa muito mais inteligente para ser necessário a utilização de paralelismo, mas um bom motivo de utilizarmos paralelismo são para realizarmos cálculos muito extensos, machine learning, fazer um servidor de emails, ou um servidor de banco de dados, ou mesmo trabalhar com sistemas de mensageria.

Obs:

Quando utilizamos um readFile() dentro do node, ele faz uma requisição para a biblioteca libuv do c++ e executa APIs do próprio sistema operacional para realizar essa leitura, a própria libuv vai iniciar as threads, resumindo, sempre é bom dar uma analisada no que queremos paralelizar, funções de leitura e escrita de arquivos não precisamos nos preocupar porque elas mesmos se encarregam dessa função.

segue link muito interessante de como criar paralelismo no node: https://www.youtube.com/watch?v=AGLq2stqAyY

### Concorrência

Goroutines

Basicamente tudo em Go funciona com goroutines, inclusive a execução principal do programa. Por isso, qualquer chamada de uma nova goroutine acontece dentro de uma goroutine.

2006 processador dual core

2007 criação linguagem go

https://www.tomshardware.com/news/cpu-core-definition,37658.html

https://aprendagolang.com.br/2021/11/12/entenda-a-diferenca-entre-concorrencia-e-paralelismo/

https://www.youtube.com/watch?v=oV9rvDllKEg

https://www.apriorit.com/dev-blog/652-virtualization-golang-c-java-for-building-microservices

https://www.youtube.com/watch?v=CiMnb06C4po

https://www.meupositivo.com.br/doseujeito/tecnologia/o-que-e-processador/

https://www.geeksforgeeks.org/what-are-threads-in-computer-processor-or-cpu/

https://www.treinaweb.com.br/blog/concorrencia-paralelismo-processos-threads-programacao-sincrona-e-assincrona/

https://thunderboltlaptop.com/what-is-a-multi-threaded-cpu/

https://itigic.com/multi-threaded-execution-on-cpu-how-it-works-in-performance/

https://deepu.tech/concurrency-in-modern-languages/

https://www.youtube.com/watch?v=unofca9ooS4&list=PLCKpcjBB_VlBsxJ9IseNxFllf-UFEXOdg&index=125

https://www.mailgun.com/blog/it-and-engineering/how-use-parallel-programming/

https://www.youtube.com/watch?v=UxHM7_BaMTY

https://www.techtudo.com.br/noticias/2016/10/o-que-e-memoria-cache-entenda-sua-importancia-para-o-pc.ghtml

https://www.youtube.com/watch?v=NU5DSgPeQlY

https://www.youtube.com/watch?v=xNBMNKjpJzM

https://www.youtube.com/watch?v=AGLq2stqAyYs

https://www.baeldung.com/cs/async-vs-multi-threading

https://www.youtube.com/watch?v=SgNrDqdNVG4

https://aprendagolang.com.br/2021/11/19/o-que-sao-e-como-funcionam-as-goroutines/

https://stackoverflow.com/questions/30261032/how-to-implement-an-abstract-class-in-go