<?php

declare(strict_types=1);

namespace Exercicio3;

class ValorInvalidoException extends \DomainException {}
class SaldoInsuficienteException extends \DomainException {}

class Titular
{
    public function __construct(
        public readonly int $id,
        public readonly string $nome
    ) {}
}

class Transacao
{
    public function __construct(
        public readonly string $tipo,
        public readonly float $valor,
        public readonly \DateTimeImmutable $dataHora,
        public readonly bool $sucesso
    ) {}
}

abstract class ContaBancaria
{
    private float $saldo;
    protected array $historico = [];

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

    public function getExtrato(): array
    {
        return $this->historico;
    }

    protected function registrarTransacao(
        string $tipo,
        float $valor,
        bool $sucesso
    ): void {
        $this->historico[] = new Transacao(
            $tipo,
            $valor,
            new \DateTimeImmutable(),
            $sucesso
        );
    }

    public function depositar(float $valor): void
    {
        if ($valor <= 0) {
            $this->registrarTransacao("Depósito", $valor, false);
            throw new ValorInvalidoException("Depósito inválido.");
        }

        $this->saldo += $valor;
        $this->registrarTransacao("Depósito", $valor, true);
    }

    protected function debitar(float $valor): void
    {
        if ($valor <= 0) {
            $this->registrarTransacao("Saque", $valor, false);
            throw new ValorInvalidoException("Saque inválido.");
        }

        if ($valor > $this->saldo) {
            $this->registrarTransacao("Saque", $valor, false);
            throw new SaldoInsuficienteException("Saldo insuficiente.");
        }

        $this->saldo -= $valor;
        $this->registrarTransacao("Saque", $valor, true);
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

// Testes
$titular = new Titular(1, "Carlos Santos");
$conta = new ContaCorrente("003", $titular, 500.00);

$conta->depositar(200.00);
$conta->sacar(100.00);
$conta->depositar(300.00);

try {
    $conta->depositar(-50.00);
} catch (ValorInvalidoException $erro) {
    echo "Depósito bloqueado: " . $erro->getMessage() . PHP_EOL;
}

try {
    $conta->sacar(5000.00);
} catch (SaldoInsuficienteException $erro) {
    echo "Saque bloqueado: " . $erro->getMessage() . PHP_EOL;
}

// Relatório
echo PHP_EOL . "EXTRATO BANCÁRIO" . PHP_EOL;
echo str_repeat("-", 75) . PHP_EOL;

printf(
    "%-15s %-12s %-22s %-10s\n",
    "TIPO",
    "VALOR",
    "DATA E HORA",
    "STATUS"
);

foreach ($conta->getExtrato() as $transacao) {
    printf(
        "%-15s R$ %-9.2f %-22s %-10s\n",
        $transacao->tipo,
        $transacao->valor,
        $transacao->dataHora->format("d/m/Y H:i:s"),
        $transacao->sucesso ? "Sucesso" : "Falhou"
    );
}

echo str_repeat("-", 75) . PHP_EOL;
echo "Saldo consolidado: R$ "
    . number_format($conta->getSaldo(), 2, ",", ".") . PHP_EOL;
