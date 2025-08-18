<?php
session_start();

// --- Conexão com o banco e início da sessão ---
require_once '../includes/config.php';

$conexao= new mysqli($host, $usuario, $senha, $banco);
if ($conexao->connect_error) {
    die("Falha na conexão: " . $conexao->connect_error);
}

if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit;
}

$id_usuario = $_SESSION['usuario'];

// Buscar o nome correto para exibir na tela
$stmt_nome = $conexao->prepare("SELECT nome FROM usuarios WHERE id = ?");
$stmt_nome->bind_param("i", $id_usuario);
$stmt_nome->execute();
$res_nome = $stmt_nome->get_result();
$usuario = $res_nome->fetch_assoc();

$nome_usuario = $usuario ? $usuario['nome'] : 'Usuário';

// --- INSERIR EVENTO ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['acao']) && $_POST['acao'] === 'inserir') {
    $titulo = $_POST['titulo'];
    $dia = $_POST['dia'];
    $horario = $_POST['horario'];
    $dataHora = $dia . ' ' . $horario;
    $descricao = $_POST['descricao'];
    $lugar = $_POST['lugar'];
    $categoria = $_POST['categoria'];

    $imagem = '';
    if ($_FILES['imagem']['name']) {
        $imagem = time() . '_' . basename($_FILES['imagem']['name']);
        move_uploaded_file($_FILES['imagem']['tmp_name'], '../assets/' . $imagem);
    }

$stmt = $conexao->prepare("INSERT INTO  eventos (id_usuario, titulo, dia, descricao, lugar, categoria, imagem) VALUES (?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("issssss", $id_usuario, $titulo, $dataHora, $descricao, $lugar, $categoria, $imagem);
$stmt->execute();
    header("Location: ../eventos/painel.php");
    exit;
}

// --- EXCLUIR EVENTO ---
if (isset($_GET['excluir'])) {
    $id_excluir = intval($_GET['excluir']);

    // Deleta imagem (se existir)
    $stmt_img = $conexao->prepare("SELECT imagem FROM eventos WHERE id = ? AND id_usuario = ?");
    $stmt_img->bind_param("ii", $id_excluir, $id_usuario);
    $stmt_img->execute();
    $res_img = $stmt_img->get_result();
    if ($res_img->num_rows > 0) {
        $evento = $res_img->fetch_assoc();
        if (!empty($evento['imagem']) && file_exists('../assets/' . $evento['imagem'])) {
            unlink('../assets/' . $evento['imagem']);
        }
    }

    // Deleta do banco
    $stmt = $conexao->prepare("DELETE FROM eventos WHERE id = ? AND id_usuario = ?");
    $stmt->bind_param("ii", $id_excluir, $id_usuario);
    $stmt->execute();
    header("Location: ../eventos/painel.php");
    exit;
}

// --- CARREGAR EVENTOS DO USUÁRIO ---
$stmt_eventos = $conexao->prepare("SELECT * FROM eventos WHERE id_usuario = ? ORDER BY dia DESC");
$stmt_eventos->bind_param("i", $id_usuario);
$stmt_eventos->execute();
$resultado = $stmt_eventos->get_result();?>


<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel de Eventos</title>
    <link rel="stylesheet" href="../assets/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Savate:ital,wght@0,200..900;1,200..900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

</head>
<body>
    <?php include_once("../includes/includeHeader.php"); ?>

    <h2>Bem-vindo</h2>

    <div class="fluid-container">
        <div class="inserir">
            <h3>Cadastrar Novo Evento</h3>
            <form method="POST" enctype="multipart/form-data">
                <input type="hidden" name="acao" value="inserir">
                <input type="text" name="titulo" placeholder="Título" required><br>
                <input type="date" name="dia" required><br>
                <input type="time" name="horario" required><br>
                <textarea name="descricao" placeholder="Descrição" required></textarea><br>
                <input type="text" name="lugar" placeholder="Lugar" required><br>
                <input type="text" name="categoria" placeholder="Categoria"><br>
                <input type="file" name="imagem"><br><br>
                <button type="submit">Inserir Evento</button>
            </form>
        </div>
    </div>
    
    <?php include_once("../includes/includeFooter.php"); ?>
</body>
</html>
