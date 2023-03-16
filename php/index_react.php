<?php

require_once 'vendor/autoload.php';

$inicio = microtime(true);

function delay($ms = 2) {
    React\Async\await(React\Promise\Timer\sleep($ms));
}

function frutaDisponivel(int $min = 0, int $max = 2) : int
{
    return rand($min, $max);
}

function comprarUva() {
    delay(7);
    echo "Joaquina consegui comprar uva" . PHP_EOL;
    return "uva";
}

function comprarLaranja() {
    delay(6);
    echo "Joaquina consegui comprar laranja" . PHP_EOL;
    return "laranja";
}

function naoTemFruta() {
    echo "Joaquina não conseguiu comprar fruta" . PHP_EOL;
    delay(4);
    return "banana";
}

function comprarOvos() {
    echo "Pedro conseguiu comprar ovos" . PHP_EOL;
    delay(5);
    return "comprei ovos";
} 


$enviarPedroAoMercado = React\Async\async(static function (): string {
    echo "Pedro foi enviado comprar ovos" . PHP_EOL;
    return comprarOvos();
})(); 

$enviarJoaquinaAFrutaria = React\Async\async(static function (): string {
    echo  ("Joaquina foi enviada comprar fruta") . PHP_EOL;
    $fruta = frutaDisponivel();

    if($fruta === 0){
        return naoTemFruta();
    }

    if($fruta === 1){
        return comprarUva();
    }
    
    if($fruta === 2){
        return comprarLaranja();
    }
})(); 

function iniciarPreparo(): string
{
    echo "Maria está começando a preparar a massa" . PHP_EOL;
    delay(3);
    return "Maria está esperando a Joaquina" . PHP_EOL;
}

React\Async\await($enviarPedroAoMercado);
$enviarPedroAoMercado->then(function (string $tarefaPedro) {
    echo $tarefaPedro . PHP_EOL;
    echo iniciarPreparo();
}, function (Exception $e) {
    echo 'Error: ' . $e->getMessage() . PHP_EOL;
});

$enviarJoaquinaAFrutaria->then(function (string $tarefaJoaquina) {
    echo "Joaquina conseguiu comprar " . $tarefaJoaquina . PHP_EOL;
    echo "Joaquina chegou" . PHP_EOL;
    echo "Maria está indo fazer bolo de {$tarefaJoaquina}" . PHP_EOL;
    
}, function (Exception $e) {
    echo 'Error: ' . $e->getMessage() . PHP_EOL;
});

$total = microtime(true) - $inicio;
echo 'Tempo de execução do script: ' . $total;