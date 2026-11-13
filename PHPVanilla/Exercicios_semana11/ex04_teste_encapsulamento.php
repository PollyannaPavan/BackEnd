<?php

declare(strict_types=1);

namespace Exercicio4;

class ValorInvalidoException extends \DomainException {}
class SaldoInsuficienteException extends \DomainException {}

class Titular
{
    public function __construct(
        public readonly int $id,
        public readonly string $nome
    ) {}
}

abstract class ContaBancaria
{
    private float $saldo;
    private string $codigoInterno = "CONTA-PRIVADA";

    public function __construct(
        protected readonly string $numero,
        protected readonly Titular $titular,
        float $saldoInicial = 0.0
    ) {
        if ($saldoInicial < 0) {
            throw new ValorInvalidoException("Saldo inicial inválido.");
        }

        $this->saldo = $saldoInicial;
    }

    public function getSaldo(): float
    {
        return $this->saldo;
    }

    public function depositar(float $valor): void
    {
        if ($valor <= 0) {
            throw new ValorInvalidoException("Depósito inválido.");
        }

        $this->saldo += $valor;
    }

    protected function debitar(float $valor): void
    {
        if ($valor <= 0) {
            throw new ValorInvalidoException("Saque inválido.");
        }

        if ($valor > $this->saldo) {
            throw new SaldoInsuficienteException("Saldo insuficiente.");
        }

        $this->saldo -= $valor;
    }

    abstract public function sacar(float $valor): void;
}

class ContaCorrente extends ContaBancaria
{
    public function __construct(
        string $numero,
        Titular $titular,
        float $saldoInicial = 0.0
    ) {
        parent::__construct($numero, $titular, $saldoInicial);
    }

    public function sacar(float $valor): void
    {
        $this->debitar($valor);
    }
}

// Preparação dos testes
$titular = new Titular(1, "Maria Silva");
$conta = new ContaCorrente("004", $titular, 1000.00);
$resultados = [];

// Teste 1: alteração do saldo privado
try {
    $atributo = "saldo";
    $conta->{$atributo} = 5000.00;
    $resultados[] = ["Saldo privado", false];
} catch (\Error $erro) {
    $resultados[] = ["Saldo privado", true];
}

// Teste 2: alteração do código privado
try {
    $atributo = "codigoInterno";
    $conta->{$atributo} = "ALTERADO";
    $resultados[] = ["Código privado", false];
} catch (\Error $erro) {
    $resultados[] = ["Código privado", true];
}

// Teste 3: instanciação da classe abstrata
try {
    $classe = ContaBancaria::class;
    $contaAbstrata = new $classe("005", $titular);
    $resultados[] = ["Classe abstrata", false];
} catch (\Error $erro) {
    $resultados[] = ["Classe abstrata", true];
}

// Teste 4: depósito negativo
try {
    $conta->depositar(-100.00);
    $resultados[] = ["Depósito negativo", false];
} catch (ValorInvalidoException $erro) {
    $resultados[] = ["Depósito negativo", true];
}

// Relatório
$total = count($resultados);
$bloqueados = count(array_filter(
    $resultados,
    fn(array $teste): bool => $teste[1]
));

$porcentagem = ($bloqueados / $total) * 100;

echo "RELATÓRIO DE TESTES DE ENCAPSULAMENTO" . PHP_EOL;
echo str_repeat("-", 45) . PHP_EOL;

foreach ($resultados as [$nome, $sucesso]) {
    $status = $sucesso ? "BLOQUEADO" : "FALHOU";
    printf("%-22s | %s" . PHP_EOL, $nome, $status);
}

echo str_repeat("-", 45) . PHP_EOL;
echo "Testes realizados: {$total}" . PHP_EOL;
echo "Violações bloqueadas: {$bloqueados}" . PHP_EOL;
echo "Integridade preservada: "
    . number_format($porcentagem, 2, ",", ".") . "%" . PHP_EOL;

echo "Saldo final: R$ "
    . number_format($conta->getSaldo(), 2, ",", ".") . PHP_EOL;
