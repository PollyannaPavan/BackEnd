<?php
declare(strict_types=1);

session_start();

require_once "dao/UsuarioDAO.php";

$dao = new UsuarioDAO();
$mensagem = "";

// Registra o resultado da autenticação
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST["email"] ?? "";
    $senha = $_POST["senha"] ?? "";

    $usuario = $dao->buscarPorEmail($email);

    $sucesso = $usuario !== false
        && password_verify($senha, $usuario["senha"]);

    $ip = $_SERVER["REMOTE_ADDR"] ?? "Não identificado";
    $navegador = $_SERVER["HTTP_USER_AGENT"] ?? "Não identificado";

    $dao->registrarAuditoria($email, $ip, $navegador, $sucesso);

    if ($sucesso) {
        $_SESSION["perfil"] = $usuario["perfil"];
        $mensagem = "Login realizado com sucesso!";
    } else {
        $mensagem = "E-mail ou senha incorretos!";
    }
}
?>

<h1>Login com auditoria</h1>

<p><?= htmlspecialchars($mensagem) ?></p>

<form method="POST">
    <input type="email" name="email" placeholder="E-mail" required>
    <input type="password" name="senha" placeholder="Senha" required>
    <button type="submit">Entrar</button>
</form>
