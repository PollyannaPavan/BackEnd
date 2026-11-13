<?php
declare(strict_types=1);

require_once "ex03_rbac_guard.php";

// Protege o acesso aos relatórios
verificarPermissao("ADMIN");
?>

<h1>Relatórios Gerenciais</h1>
<p>Bem-vindo à área administrativa.</p>
