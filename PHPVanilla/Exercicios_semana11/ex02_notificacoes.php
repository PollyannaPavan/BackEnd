<?php

declare(strict_types=1);

namespace Exercicio2;

class ValorInvalidoException extends \DomainException {}
class SaldoInsuficienteException extends \DomainException {}

class Titular
{
    public function __construct(
        public readonly int $id,
        public readonly string $nome,
        public readonly string $email,
        public readonly string $telefone
    ) {}
}

interface NotificadorInterface
{
    public function notificar(Titular $titular, string $mensagem): bool;
}

class NotificadorEmail implements NotificadorInterface
{
    public function notificar(Titular $titular, string $mensagem): bool
    {
        echo "E-mail para {$titular->email}: {$mensagem}" . PHP_EOL;
        return true;
    }
}

class NotificadorSMS implements NotificadorInterface
{
    public function notificar(Titular $titular, string $mensagem): bool
    {
        echo "SMS para {$titular->telefone}: {$mensagem}" . PHP_EOL;
        return true;
    }
}

class NotificadorWhatsApp implements NotificadorInterface
{
    public function notificar(Titular $titular, string $mensagem): bool
    {
        echo "WhatsApp para {$titular->telefone}: {$mensagem}" . PHP_EOL;
        return true;
    }
}

abstract class ContaBancaria
{
    private float $saldo;
    private array $notificadores = [];

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

    public function adicionarNotificador(
        NotificadorInterface $notificador
    ): void {
        $this->notificadores[] = $notificador;
    }

    protected function notificarTodos(string $mensagem): void
    {
        foreach ($this->notificadores as $notificador) {
            $notificador->notificar($this->titular, $mensagem);
        }
    }

    public function depositar(float $valor): void
    {
        if ($valor <= 0) {
            throw new ValorInvalidoException("Depósito inválido.");
        }

        $this->saldo += $valor;
        $mensagem = "Depósito de R$ " . number_format($valor, 2, ",", ".");
        $this->notificarTodos($mensagem);
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
        $mensagem = "Saque de R$ " . number_format($valor, 2, ",", ".");
        $this->notificarTodos($mensagem);
    }
}

// Testes
$titular = new Titular(
    1,
    "Maria Oliveira",
    "maria@email.com",
    "(19) 99999-9999"
);

$conta = new ContaCorrente("002", $titular, 1000.00);

$conta->adicionarNotificador(new NotificadorEmail());
$conta->adicionarNotificador(new NotificadorSMS());
$conta->adicionarNotificador(new NotificadorWhatsApp());

echo "REALIZANDO DEPÓSITO" . PHP_EOL;
$conta->depositar(300.00);

echo PHP_EOL . "REALIZANDO SAQUE" . PHP_EOL;
$conta->sacar(100.00);

echo PHP_EOL . "Saldo final: R$ "
    . number_format($conta->getSaldo(), 2, ",", ".") . PHP_EOL;
