<?php
require '../includes/config.php';

session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: ../eventos/login.php");
    exit();
}

$id_usuario = $_SESSION['usuario'];
$id_evento = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id_evento <= 0) {
    echo "ID inválido.";
    exit();
}

// Buscar evento
$stmt = $conexao->prepare("SELECT * FROM eventos WHERE id = ? AND id_usuario = ?");
$stmt->bind_param("ii", $id_evento, $id_usuario);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows === 0) {
    echo "Evento não encontrado ou acesso negado.";
    exit();
}

$evento = $resultado->fetch_assoc();

if (!empty($evento['dia']) && $evento['dia'] != '0000-00-00 00:00:00') {
    $data_formatada = date('Y-m-d', strtotime($evento['dia']));  // formato para o input date
    $hora_formatada = date('H:i', strtotime($evento['dia']));     // formato para o input time
}


// Se formulário enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = $_POST['titulo'];
    $dia = $_POST['dia'];
    $horario = $_POST['horario'];
    $diaCompleto = $dia . ' ' . $horario . ':00';
    $descricao = $_POST['descricao'];
    $lugar = $_POST['lugar'];
    $categoria = $_POST['categoria'];

    $diaCompleto = $dia . ' ' . $horario . ':00';
    // Verifica se imagem foi enviada
    if ($_FILES['imagem']['name']) {
        if ($evento['imagem'] && file_exists('../assets/' . $evento['imagem'])) {
            unlink('../assets/' . $evento['imagem']);
        }

        $imagem = time() . '_' . $_FILES['imagem']['name'];
        move_uploaded_file($_FILES['imagem']['tmp_name'], '../assets/' . $imagem);
    } else {
        $imagem = $evento['imagem'];
    }

    // Atualizar evento
    $stmt = $conexao->prepare("UPDATE eventos SET titulo=?, dia=?, descricao=?, lugar=?, categoria=?, imagem=? WHERE id=? AND id_usuario=?");
    $stmt->bind_param("ssssssii", $titulo, $diaCompleto, $descricao, $lugar, $categoria, $imagem, $id_evento, $id_usuario);
    $stmt->execute();

    header("Location: ../eventos/painel.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Evento</title>
    <link rel="stylesheet" href="../assets/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Savate:ital,wght@0,200..900;1,200..900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<?php include_once("../includes/includeHeader.php"); ?>
<div class="container my-4">
    <h1 class="text-center mb-4">Editar Evento</h1>

    <div class="fluid-container">
        <div class="inserir">
            <form method="POST" enctype="multipart/form-data">
                <input type="text" name="titulo" value="<?= htmlspecialchars($evento['titulo']) ?>" required><br>
                <input type="date" name="dia" value="<?= htmlspecialchars($data_formatada) ?>" required><br>
                <input type="time" name="horario" value="<?= htmlspecialchars($hora_formatada) ?>" required><br>    <textarea name="descricao" required><?= htmlspecialchars($evento['descricao']) ?></textarea><br>
                <input type="text" name="lugar" value="<?= htmlspecialchars($evento['lugar']) ?>" required><br>
                <input type="text" name="categoria" value="<?= htmlspecialchars($evento['categoria']) ?>"><br>
                <label>Imagem:</label><br>
                <?php if ($evento['imagem']): ?>
                    <img src="../assets/<?= htmlspecialchars($evento['imagem']) ?>" width="250px"><br>
                <?php endif; ?>
                <input type="file" name="imagem"><br><br>
                <button type="submit">Salvar Alterações</button>
            </form>
        </div>
    </div>
    <br><br>
    <?php include_once("../includes/includeFooter.php"); ?>
</body>
</html>