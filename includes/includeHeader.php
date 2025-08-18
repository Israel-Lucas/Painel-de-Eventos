<?php
// Inicia a sessão em todas as páginas que importarem este header
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$usuario_logado = $_SESSION['usuario'] ?? null;
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Mini Plataforma de Eventos</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://fonts.googleapis.com/css2?family=Savate:ital,wght@0,200..900;1,200..900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
      <a class="navbar-brand" href="../eventos/mural.php">Eventos Acadêmicos</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          <?php if (isset($_SESSION['usuario'])): ?>
            <li class="nav-item">
              <a class="nav-link" href="../eventos/painel.php">Painel</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="../eventos/logout.php">Sair</a>
            </li>
          <?php else: ?>
            <li class="nav-item">
              <a class="nav-link" href="../eventos/login.php">Entrar</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="../eventos/cadastro_user.php">Cadastro</a>
            </li>
          <?php endif; ?>
        </ul>
      </div>
    </div>
  </nav>
  <main class="container my-4">
</body>
</html>