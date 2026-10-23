<?php

declare(strict_types=1);

// Conecta ao banco de dados
function conectar(): PDO {
    return new PDO(
        "pgsql:host=localhost;dbname=almoxarifado_senai",
        "postgres",
        "postgres"
    );
}

// Faz uma busca sem proteção
function buscarVulneravel(string $termo): void {
    $pdo = conectar();
    $sql = "SELECT * FROM usuarios WHERE nome = '$termo'";

    echo "\nBusca vulnerável:\n";
    $stmt = $pdo->query($sql);

    while ($resultado = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "Registro encontrado: " . $resultado['nome'] . "\n";
    }
}

// Faz uma busca protegida
function buscarProtegido(string $termo): void {
    $pdo = conectar();
    $sql = "SELECT * FROM usuarios WHERE nome = :nome";

    echo "\nBusca protegida:\n";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['nome' => $termo]);

    if ($stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "Registro encontrado.\n";
    } else {
        echo "Nenhum registro encontrado.\n";
    }
}

// Testa as duas buscas
$termo = "' OR '1'='1";

buscarVulneravel($termo);
buscarProtegido($termo);

?>