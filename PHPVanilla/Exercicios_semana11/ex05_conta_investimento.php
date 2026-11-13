<?php

declare(strict_types=1);

namespace Exercicio5;

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

class ContaInvestimento extends ContaBancaria
{
    public function __construct(
        string $numero,
        Titular $titular,
        private readonly ContaCorrente $contaCorrente,
        private readonly int $diasAplicacao,
        float $saldoInicial = 0.0
    ) {
        parent::__construct($numero, $titular, $saldoInicial);

        if ($titular->id !== $contaCorrente->getTitular()->id) {
            throw new OperacaoNaoPermitidaException("Titular diferente.");
        }

        if ($diasAplicacao < 0) {
            throw new ValorInvalidoException("Prazo inválido.");
        }
    }

    public function getTaxaAdministracao(): float
    {
        $saldo = $this->getSaldo();

        if ($saldo < 1000) {
            return 2.0;
        }

        if ($saldo < 5000) {
            return 1.0;
        }

        return 0.5;
    }

    public function sacar(float $valor): void
    {
        throw new OperacaoNaoPermitidaException("Utilize resgatar().");
    }

    public function resgatar(float $valor): void
    {
        if ($valor <= 0) {
            throw new ValorInvalidoException("Resgate inválido.");
        }

        if ($valor > $this->getSaldo()) {
            throw new SaldoInsuficienteException("Saldo insuficiente.");
        }

        $valores = $this->calcularResgate($valor);
        $this->debitar($valor);
        $this->contaCorrente->depositar($valores["liquido"]);
        $this->exibirValores($valores);
    }

    private function calcularResgate(float $valor): array
    {
        $rendimento = $valor * 0.05;
        $taxa = $valor * ($this->getTaxaAdministracao() / 100);
        $taxa *= $this->diasAplicacao / 365;
        $base = max(0.0, $rendimento - $taxa);
        $iof = $this->calcularIOF($base);
        $ir = $this->calcularIR(max(0.0, $base - $iof));

        return [
            "rendimento" => $rendimento,
            "taxa" => $taxa,
            "iof" => $iof,
            "ir" => $ir,
            "liquido" => $valor + $base - $iof - $ir
        ];
    }

    private function calcularIOF(float $rendimento): float
    {
        if ($this->diasAplicacao >= 30) {
            return 0.0;
        }

        return $rendimento * ((30 - $this->diasAplicacao) / 30);
    }

    private function calcularIR(float $rendimento): float
    {
        if ($this->diasAplicacao <= 180) {
            return $rendimento * 0.225;
        }

        if ($this->diasAplicacao <= 360) {
            return $rendimento * 0.20;
        }

        if ($this->diasAplicacao <= 720) {
            return $rendimento * 0.175;
        }

        return $rendimento * 0.15;
    }

    private function exibirValores(array $valores): void
    {
        foreach ($valores as $nome => $valor) {
            echo ucfirst($nome) . ": R$ "
                . number_format($valor, 2, ",", ".") . PHP_EOL;
        }
    }
}

// Testes
$titular = new Titular(1, "Pedro Costa");
$corrente = new ContaCorrente("006", $titular, 100.00);

$investimento = new ContaInvestimento(
    "005",
    $titular,
    $corrente,
    90,
    1000.00
);

echo "Taxa de administração: "
    . $investimento->getTaxaAdministracao() . "%" . PHP_EOL;

echo PHP_EOL . "REALIZANDO RESGATE" . PHP_EOL;

try {
    $investimento->resgatar(500.00);

    echo PHP_EOL . "Saldo do investimento: R$ "
        . number_format($investimento->getSaldo(), 2, ",", ".") . PHP_EOL;

    echo "Saldo da conta corrente: R$ "
        . number_format($corrente->getSaldo(), 2, ",", ".") . PHP_EOL;

} catch (\DomainException $erro) {
    echo "Erro: " . $erro->getMessage() . PHP_EOL;
}
