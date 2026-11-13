<?php
declare(strict_types=1);

// Inicia a sessão se ainda não estiver ativa
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

// Verifica o nível de permissão do usuário
function verificarPermissao(string $perfilMinimo): void
{
    $niveis = [
        "OPERADOR" => 1,
        "ADMIN" => 2
    ];

    $perfil = $_SESSION["perfil"] ?? "OPERADOR";

    if (($niveis[$perfil] ?? 0) < ($niveis[$perfilMinimo] ?? 0)) {
        http_response_code(403);
        exit("Acesso negado! Você não tem permissão.");
    }
}
