<?php
session_start();
require '../includes/config.php';

$erro = '';
$sucesso = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'] ?? '';
    $email = $_POST['email'] ?? '';
    $senha = $_POST['senha'] ?? '';
    $senha = password_hash($senha, PASSWORD_DEFAULT);

    // Verifica se o e-mail já está cadastrado
    $verifica = $conexao->prepare("SELECT id FROM usuarios WHERE email = ?");
    $verifica->bind_param("s", $email);
    $verifica->execute();
    $verifica->store_result();

    if ($verifica->num_rows > 0) {
        $erro = "Este e-mail já está cadastrado.";
    } else {
        // Insere novo usuário
        $sql = "INSERT INTO usuarios (nome, email, senha) VALUES (?, ?, ?)";
        $stmt = $conexao->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("sss", $nome, $email, $senha);
            $stmt->execute();

            $_SESSION['usuario'] = [
                'id' => $stmt->insert_id,
                'nome' => $nome
            ];

            header("location: ../eventos/login.php"); // Redireciona em 2 segundos
        } else {
            $erro = "Erro ao cadastrar usuário. Tente novamente.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro</title>
    <link rel="stylesheet" href="../assets/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Savate:ital,wght@0,200..900;1,200..900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<?php include_once("../includes/includeHeader.php"); ?>

<div class="fluid-container">
    <div class="form-container">
        <h1 class="text-center">Cadastro</h1>

        <!-- Exibe mensagem de erro -->
        <?php if ($erro): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($erro) ?></div>
        <?php endif; ?>

        <form action="cadastro_user.php" method="post">
            <div class="mb-3">
                <label for="nome">Nome Completo:</label>
                <input type="text" name="nome" id="nome" required placeholder="Nome Completo:" class="form-control" value="<?= htmlspecialchars($nome ?? '') ?>">
            </div>
            <div class="mb-3">
                <label for="email">E-mail:</label>
                <input type="email" name="email" id="email" required placeholder="E-mail:" class="form-control" value="<?= htmlspecialchars($email ?? '') ?>">
            </div>
            <div class="mb-3">
                <label for="senha">Senha:</label>
                <input type="password" name="senha" id="senha" required placeholder="Senha:" class="form-control">
            </div>
            <button type="submit" class="btn btn-primary w-100">Cadastrar</button>
        </form>
    </div>
    <footer>
        <p class="mb-0">&copy; <?= date("Y") ?> - Mini Plataforma de Eventos Acadêmicos</p>
    </footer>
</div>
</body>
</html>