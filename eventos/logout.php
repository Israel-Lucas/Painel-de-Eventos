<?php
session_start();
session_destroy();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <meta http-equiv="refresh" content="1;url=../eventos/mural.php">
    <link rel="stylesheet" href="../assets/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Savate:ital,wght@0,200..900;1,200..900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="d-flex align-items-center justify-content-center vh-100">
    
 <div class="text-center">
        <h1 class="mb-3">Logout</h1>
        <p class="mb-4">Você foi desconectado com sucesso!</p>
        <a href="../eventos/mural.php" class="btn btn-light">Ir para o mural</a>
</div>

</body>
</html>