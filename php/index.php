<?php

function delay($ms = 2) {
    return sleep($ms);
}

class IrAFrutaria { 
    public function frutaDisponivel(int $min = 0, int $max = 2) : int
    {
        return rand($min, $max);
    }
    
    public function comprarUva() {
        delay(7);
        echo "Joaquina consegui comprar uva" . PHP_EOL;
        return "uva";
    }

    public function comprarLaranja() {
        delay(6);
        echo "Joaquina consegui comprar laranja" . PHP_EOL;
        return "laranja";
    }

    public function naoTemFruta() {
        echo "Joaquina não conseguiu comprar fruta" . PHP_EOL;
        delay(4);
        return "banana";
    }
}

class IrAoMercado {
    public function comprarOvos() {
        echo "Pedro conseguiu comprar ovos" . PHP_EOL;
        delay(5);
        return "comprei ovos";
    }  
}

class FazerBolo {    
    public function enviarPedroAoMercado(): string
    {
        echo "Pedro foi enviado comprar ovos" . PHP_EOL;
        $pedro = new IrAoMercado();
        return $pedro->comprarOvos();
    }
    
    public function enviarJoaquinaAFrutaria(): string
    {
        echo  ("Joaquina foi enviada comprar fruta") . PHP_EOL;
        $joaquina = new IrAFrutaria();
        $fruta = $joaquina->frutaDisponivel();

        if($fruta === 0){
            return $joaquina->naoTemFruta();
        }

        if($fruta === 1){
            return $joaquina->comprarUva();
        }
        
        if($fruta === 2){
            return $joaquina->comprarLaranja();
        }
    }

    public function iniciarPreparo(): string
    {
        echo "Maria está começando a preparar a massa" . PHP_EOL;
        delay(3);
        return "Maria está esperando o Joaquina" . PHP_EOL;
    } 

    public function fazerBolo() : void 
    {
        $tarefaPedro = $this->enviarPedroAoMercado();
        $tarefaJoaquina = $this->enviarJoaquinaAFrutaria();

        $ovos = $tarefaPedro;
        echo "Pedro chegou" . PHP_EOL;
        
        echo $this->iniciarPreparo();

        $tipoBolo = $tarefaJoaquina;
        echo "Joaquina chegou" . PHP_EOL;

        $condimentos = [
            "ovos" => $ovos,
            "tipoBolo" => $tipoBolo 
        ];

        echo "Maria está indo fazer bolo de {$condimentos["tipoBolo"]}" . PHP_EOL;
    }
}

$inicio = microtime(true);
$maria = new FazerBolo();
$maria->fazerBolo();
$total = microtime(true) - $inicio;
echo 'Tempo de execução do script: ' . $total;