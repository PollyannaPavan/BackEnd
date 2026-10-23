<?php
declare(strict_types=1);

// Protege os textos exibidos na página
function e(string $valor): string
{
    return htmlspecialchars($valor, ENT_QUOTES, 'UTF-8');
}

// Conecta ao banco
$pdo = new PDO(
    "pgsql:host=localhost;dbname=almoxarifado_senai",
    "postgres",
    "postgres"
);

// Pega o número da página
$pagina = (int) ($_GET['p'] ?? 1);

// Evita números menores que 1
if ($pagina < 1) {
    $pagina = 1;
}

// Calcula o início da consulta
$offset = ($pagina - 1) * 5;

// Busca as peças da página
$sql = "SELECT * FROM pecas_industriais
        ORDER BY id
        LIMIT :limite OFFSET :offset";

$stmt = $pdo->prepare($sql);

// Define os valores da consulta
$stmt->bindValue(':limite', 5, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

$stmt->execute();

// Mostra o título
echo "<h1>Catálogo de peças industriais</h1>";

// Mostra as peças encontradas
while ($peca = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo "ID: " . e((string) $peca['id']) . "<br>";
    echo "Código SKU: " . e((string) $peca['codigo_sku']) . "<br>";
    echo "Descrição: " . e((string) $peca['descricao']) . "<br>";
    echo "Categoria: " . e((string) $peca['categoria']) . "<br>";
    echo "<hr>";
}

// Cria o link para a próxima página
echo '<a href="?p=' . ($pagina + 1) . '">Próxima página</a>';

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Catálogo de Peças</title>
</head>
<body>

    <h1>Catálogo de Peças Industriais</h1>

    <form action="ex03_catalogo_paginado.php" method="GET">
        <label for="p">Número da página:</label>
        <input type="number" id="p" name="p" min="1" value="1" required>

        <button type="submit">Consultar peças</button>
    </form>

</body>
</html>