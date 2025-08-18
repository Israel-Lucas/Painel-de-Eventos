<?php
session_start();
require '../includes/config.php';

$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    $sql = "SELECT * FROM usuarios WHERE email = ?";
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $usuario = $result->fetch_assoc();

        if (password_verify($senha, $usuario['senha'])) {
            $_SESSION['usuario'] = [
                'id' => $usuario['id'],
                'nome' => $usuario['nome'],
                'email' => $usuario['email']
            ];
            header("Location: ../eventos/painel.php");
            exit();
        } else {
            $erro = "Senha incorreta.";
        }
    } else {
        $erro = "Usuário não encontrado.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="../assets/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Savate:ital,wght@0,200..900;1,200..900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

</head>
<body>

<?php include_once("../includes/includeHeader.php"); ?>

    <div class="fluid-container">
        <div class="form-container">
            <h1 class="text-center">Login</h2>

            <?php if ($erro): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($erro) ?></div>
            <?php endif; ?>

            <form action="" method="post">
        <div class="mb-3">
            <label for="email">E-mail:</label>
            <input type="email" name="email" id="email" required placeholder="E-mail:" class="form-control" value="<?= htmlspecialchars($email ?? '') ?>">
        </div>
        <div class="mb-3">
            <label for="senha">Senha:</label>
            <input type="password" name="senha" id="senha" required placeholder="Senha:" class="form-control">
        </div>
            <button type="submit" class="btn btn-primary w-100">Entrar</button>
        </form>
            <p>Não possui cadastro? <br>
        Cadastre-se em <a href="../eventos/cadastro_user.php">Cadastrar</a></p>
        </div>
        <footer>
          <p class="mb-0">&copy; <?php echo date("Y"); ?> - Mini Plataforma de Eventos Acadêmicos</p>
        </footer>
    </div>
</body>
</html>