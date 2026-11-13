<?php
declare(strict_types=1);

// Configura a conexão com PostgreSQL
function conectarBanco(): PDO
{
    $host = "127.0.0.1";
    $banco = "semana10";
    $usuario = "postgres";
    $senha = "SUA_SENHA";

    $dsn = "pgsql:host=$host;port=5432;dbname=$banco";

    return new PDO($dsn, $usuario, $senha, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
}
