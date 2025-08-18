<?php
session_start();
require '../includes/config.php';

if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

$id_usuario = $_SESSION['usuario']['id'];

if (isset($_GET['excluir']) && is_numeric($_GET['excluir'])) {
    $id_excluir = intval($_GET['excluir']);

    // Busca imagem associada (se houver)
    $stmt_img = $conexao->prepare("SELECT imagem FROM eventos WHERE id = ? AND id_usuario = ?");
    $stmt_img->bind_param("ii", $id_excluir, $id_usuario);
    $stmt_img->execute();
    $res_img = $stmt_img->get_result();

    if ($res_img->num_rows > 0) {
        $evento = $res_img->fetch_assoc();

        if (!empty($evento['imagem']) && file_exists('../assets/' . $evento['imagem'])) {
            unlink('../assets/' . $evento['imagem']);
        }

        // Deleta do banco
        $stmt = $conexao->prepare("DELETE FROM eventos WHERE id = ? AND id_usuario = ?");
        $stmt->bind_param("ii", $id_excluir, $id_usuario);
        $stmt->execute();

        header("Location: painel.php?msg=excluido");
        exit();
    } else {
        header("Location: painel.php?erro=evento_nao_encontrado");
        exit();
    }
} else {
    header("Location: painel.php?erro=id_invalido");
    exit();
}
