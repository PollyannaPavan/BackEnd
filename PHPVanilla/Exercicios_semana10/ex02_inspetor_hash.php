<?php
declare(strict_types=1);

// Recebe uma senha para gerar o hash
echo "=== INSPETOR DE HASH ===\n";
$senha = readline("Digite uma senha: ");

if ($senha === "") {
    echo "Senha inválida!\n";
    exit;
}

// Gera o hash e separa suas partes
$hash = password_hash($senha, PASSWORD_ARGON2ID);
$partes = explode("$", $hash);

// Separa os parâmetros de custo
$custos = explode(",", $partes[3]);

echo "\nHASH COMPLETO:\n";
echo $hash . "\n";

echo "\n=== DECOMPOSIÇÃO ===\n";
echo "Algoritmo: {$partes[1]}\n";
echo "Versão: {$partes[2]}\n";
echo "Custo de memória: {$custos[0]} KiB\n";
echo "Custo de tempo: {$custos[1]}\n";
echo "Paralelismo: {$custos[2]}\n";
echo "Salt codificado: {$partes[4]}\n";
echo "Hash final: {$partes[5]}\n";
