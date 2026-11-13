<?php
declare(strict_types=1);

session_start();

require_once "dao/UsuarioDAO.php";

$dao = new UsuarioDAO();
$mensagem = "";

// Controla as tentativas de login
$_SESSION["tentativas_login"] ??= 0;
$_SESSION["bloqueado_ate"] ??= 0;

$tempoRestante = $_SESSION["bloqueado_ate"] - time();

if ($tempoRestante > 0) {
    $mensagem = "Aguarde $tempoRestante segundos para tentar novamente.";
} else {
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $email = $_POST["email"] ?? "";
        $senha = $_POST["senha"] ?? "";

        $usuario = $dao->buscarPorEmail($email);

        if ($usuario && password_verify($senha, $usuario["senha"])) {
            $_SESSION["tentativas_login"] = 0;
            $_SESSION["bloqueado_ate"] = 0;
            $_SESSION["perfil"] = $usuario["perfil"];

            $mensagem = "Login realizado com sucesso!";
        } else {
            $_SESSION["tentativas_login"]++;

            if ($_SESSION["tentativas_login"] >= 3) {
                $_SESSION["bloqueado_ate"] = time() + 60;
                $mensagem = "Login bloqueado por 60 segundos!";
            } else {
                $mensagem = "Dados incorretos!";
            }
        }
    }
}
?>

<h1>Login com bloqueio</h1>

<p><?= htmlspecialchars($mensagem) ?></p>

<?php if (time() >= $_SESSION["bloqueado_ate"]): ?>
    <form method="POST">
        <input type="email" name="email" placeholder="E-mail" required>
        <input type="password" name="senha" placeholder="Senha" required>
        <button type="submit">Entrar</button>
    </form>
<?php endif; ?>
