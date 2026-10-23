
<?php
declare(strict_types=1);

// Importa os arquivos necessários
require_once __DIR__ . "/src/ConexaoBanco.php";
require_once __DIR__ . "/src/AlmoxarifadoDAO.php";

// Caminho do arquivo de configuração
const CONFIG_PATH = __DIR__ . "/config/database.ini";

// Classe para cadastrar e listar ferramentas
final class ex01_cli_ferramentas
{
    // Guarda a conexão com o banco
    private PDO $pdo;

    // Recebe a conexão
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    // Cadastra uma ferramenta
    public function cadastrar(array $dados): bool
    {
        $sql = "INSERT INTO ferramentas
                (nome, descricao, categoria, quantidade, preco_unitario)
                VALUES (:nome, :descricao, :categoria, :quantidade, :preco_unitario)";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            ":nome" => trim($dados["nome"]),
            ":descricao" => trim($dados["descricao"]),
            ":categoria" => trim($dados["categoria"]),
            ":quantidade" => (int) $dados["quantidade"],
            ":preco_unitario" => (float) $dados["preco_unitario"]
        ]);
    }

    // Lista as ferramentas ativas
    public function buscarAtivo(): array
    {
        $sql = "SELECT * FROM ferramentas WHERE ativo = TRUE";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

// Cria o objeto para usar as funções
$ferramentas = new ex01_cli_ferramentas($pdo);

// Opção inicial do menu
$opcao = 0;

// Repete o menu até escolher sair
while ($opcao != 3) {
    echo "\n===== MENU DE FERRAMENTAS =====\n";
    echo "1 - Cadastrar ferramenta\n";
    echo "2 - Listar ferramentas ativas\n";
    echo "3 - Sair\n";

    $opcao = (int) readline("Escolha uma opção: ");

    // Cadastra uma ferramenta
    if ($opcao == 1) {
        $dados = [];

        $dados["nome"] = readline("Nome: ");
        $dados["descricao"] = readline("Descrição: ");
        $dados["categoria"] = readline("Categoria: ");
        $dados["quantidade"] = readline("Quantidade: ");
        $dados["preco_unitario"] = readline("Preço unitário: ");

        if ($ferramentas->cadastrar($dados)) {
            echo "Ferramenta cadastrada com sucesso!\n";
        } else {
            echo "Erro ao cadastrar ferramenta!\n";
        }

    // Lista as ferramentas cadastradas
    } elseif ($opcao == 2) {
        $resultado = $ferramentas->buscarAtivo();

        // Mostra os dados de cada ferramenta
        foreach ($resultado as $ferramenta) {
            echo "\nNome: " . $ferramenta["nome"] . "\n";
            echo "Descrição: " . $ferramenta["descricao"] . "\n";
            echo "Categoria: " . $ferramenta["categoria"] . "\n";
            echo "Quantidade: " . $ferramenta["quantidade"] . "\n";
            echo "Preço: R$ " . $ferramenta["preco_unitario"] . "\n";
        }

        // Avisa se não houver ferramentas
        if (count($resultado) == 0) {
            echo "Nenhuma ferramenta ativa encontrada.\n";
        }

    // Sai do programa
    } elseif ($opcao == 3) {
        echo "Encerrando o programa...\n";

    // Avisa se a opção for inválida
    } else {
        echo "Opção inválida! Tente novamente.\n";
    }
}
