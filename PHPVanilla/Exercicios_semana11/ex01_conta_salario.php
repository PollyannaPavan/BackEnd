<?php

declare(strict_types=1);

namespace Exercicio1;

class ValorInvalidoException extends \DomainException {}
class SaldoInsuficienteException extends \DomainException {}
class OperacaoNaoPermitidaException extends \DomainException {}

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

    public function getTitular(): Titular
    {
        return $this->titular;
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
            throw new ValorInvalidoException("Valor inválido.");
        }

        if ($valor > $this->saldo) {
            throw new SaldoInsuficienteException("Saldo insuficiente.");
        }

        $this->saldo -= $valor;
    }

    protected function creditarTransferencia(float $valor): void
    {
        $this->saldo += $valor;
    }

    public function transferir(float $valor, ContaBancaria $destino): void
    {
        if ($this->titular->id !== $destino->getTitular()->id) {
            throw new OperacaoNaoPermitidaException("Titular diferente.");
        }

        $this->debitar($valor);
        $destino->creditarTransferencia($valor);
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

class ContaSalario extends ContaBancaria
{
    private int $saquesMes = 0;
    private string $mesAtual;

    public function __construct(
        string $numero,
        Titular $titular,
        public readonly string $cnpjEmpregador,
        float $saldoInicial = 0.0
    ) {
        parent::__construct($numero, $titular, $saldoInicial);
        $this->mesAtual = date("Y-m");
    }

    public function depositarDoEmpregador(float $valor, string $cnpj): void
    {
        if ($cnpj !== $this->cnpjEmpregador) {
            throw new OperacaoNaoPermitidaException("Empregador não autorizado.");
        }

        parent::depositar($valor);
    }

    public function depositar(float $valor): void
    {
        throw new OperacaoNaoPermitidaException(
            "Depósitos somente pelo empregador."
        );
    }

    private function atualizarMes(): void
    {
        $mes = date("Y-m");

        if ($mes !== $this->mesAtual) {
            $this->saquesMes = 0;
            $this->mesAtual = $mes;
        }
    }

    public function sacar(float $valor): void
    {
        $this->atualizarMes();
        $taxa = $this->saquesMes >= 2 ? 4.00 : 0.00;
        $this->debitar($valor + $taxa);
        $this->saquesMes++;

        echo "Taxa: R$ " . number_format($taxa, 2, ",", ".") . PHP_EOL;
    }
}

// Testes
$titular = new Titular(1, "Maria Silva");
$outroTitular = new Titular(2, "João Silva");

$salario = new ContaSalario(
    "001",
    $titular,
    "12345678000199",
    1000.00
);

$corrente = new ContaCorrente("002", $titular, 200.00);
$terceiro = new ContaCorrente("003", $outroTitular);

try {
    $salario->depositarDoEmpregador(500.00, "12345678000199");

    $salario->sacar(100.00);
    $salario->sacar(100.00);
    $salario->sacar(100.00);

    $salario->transferir(100.00, $corrente);

    echo "Saldo salário: R$ "
        . number_format($salario->getSaldo(), 2, ",", ".") . PHP_EOL;

    echo "Saldo corrente: R$ "
        . number_format($corrente->getSaldo(), 2, ",", ".") . PHP_EOL;

    $salario->transferir(50.00, $terceiro);

} catch (\DomainException $erro) {
    echo "Operação bloqueada: " . $erro->getMessage() . PHP_EOL;
}
