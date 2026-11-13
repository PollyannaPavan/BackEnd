<?php
declare(strict_types=1);

require_once __DIR__ . "/../config/conexao.php";

class UsuarioDAO
{
    private PDO $conexao;

    // Cria a conexão com o banco
    public function __construct()
    {
        $this->conexao = conectarBanco();
    }

    // Cadastra um novo usuário
    public function cadastrar(string $nome, string $email, string $hash): bool
    {
        $sql = "INSERT INTO usuarios (nome, email, senha)
                VALUES (:nome, :email, :senha)";

        $stmt = $this->conexao->prepare($sql);

        return $stmt->execute([
            ":nome" => $nome,
            ":email" => $email,
            ":senha" => $hash
        ]);
    }

    // Busca um usuário pelo e-mail
    public function buscarPorEmail(string $email): array|false
    {
        $sql = "SELECT * FROM usuarios WHERE email = :email";

        $stmt = $this->conexao->prepare($sql);
        $stmt->execute([":email" => $email]);

        return $stmt->fetch();
    }

    // Registra uma tentativa de login
    public function registrarAuditoria(
        string $email,
        string $ip,
        string $navegador,
        bool $sucesso
    ): void {
        $sql = "INSERT INTO auditoria_logins
                (email, ip, user_agent, sucesso)
                VALUES (:email, :ip, :navegador, :sucesso)";

        $stmt = $this->conexao->prepare($sql);
        $stmt->bindValue(":email", $email);
        $stmt->bindValue(":ip", $ip);
        $stmt->bindValue(":navegador", $navegador);
        $stmt->bindValue(":sucesso", $sucesso, PDO::PARAM_BOOL);
        $stmt->execute();
    }

    // Lista os últimos registros de acesso
    public function listarAuditoria(): array
    {
        $sql = "SELECT * FROM auditoria_logins
                ORDER BY data_hora DESC LIMIT 20";

        return $this->conexao->query($sql)->fetchAll();
    }
}
