<?php
declare(strict_types=1);

require_once "dao/UsuarioDAO.php";

// Recebe os dados pelo terminal
$nome = readline("Nome: ");
$email = readline("E-mail: ");
$senha = readline("Senha: ");

// Valida a complexidade da senha
$valida = strlen($senha) >= 8
    && preg_match('/[A-Z]/', $senha)
    && preg_match('/[a-z]/', $senha)
    && preg_match('/[0-9]/', $senha)
    && preg_match('/[^a-zA-Z0-9]/', $senha);

if (!$valida) {
    echo "Senha não atende aos requisitos!\n";
    exit;
}

// Cria o hash antes de salvar
$hash = password_hash($senha, PASSWORD_ARGON2ID);

try {
    $dao = new UsuarioDAO();
    $dao->cadastrar($nome, $email, $hash);

    echo "Usuário cadastrado com sucesso!\n";
} catch (PDOException $erro) {
    echo "Não foi possível cadastrar o usuário.\n";
}
