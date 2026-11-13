<?php
declare(strict_types=1);

require_once "ex03_rbac_guard.php";
require_once "dao/UsuarioDAO.php";

// Exibe os registros para o administrador
verificarPermissao("ADMIN");

$dao = new UsuarioDAO();
$registros = $dao->listarAuditoria();
?>

<h1>Painel de Auditoria</h1>

<table border="1">
    <tr>
        <th>E-mail</th>
        <th>IP</th>
        <th>Navegador</th>
        <th>Sucesso</th>
        <th>Data</th>
    </tr>

    <?php foreach ($registros as $registro): ?>
        <tr>
            <td><?= htmlspecialchars($registro["email"]) ?></td>
            <td><?= htmlspecialchars($registro["ip"] ?? "") ?></td>
            <td><?= htmlspecialchars($registro["user_agent"] ?? "") ?></td>
            <td><?= $registro["sucesso"] ? "Sim" : "Não" ?></td>
            <td><?= htmlspecialchars($registro["data_hora"]) ?></td>
        </tr>
    <?php endforeach; ?>
</table>
