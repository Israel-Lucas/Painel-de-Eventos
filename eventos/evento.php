<?php
include '../includes/config.php'; // Conecta ao banco de dados

// Verifica se o ID foi passado na URL
if (isset($_GET['id'])) {
  $id = intval($_GET['id']); // Garante que o ID é um número inteiro

  // Consulta SQL
  $conexao = mysqli_connect('localhost', 'root', '', 'eventos_academicos');
if (!$conexao) {
    die('Erro ao conectar ao banco de dados: ' . mysqli_connect_error());
}

$sql = "SELECT eventos.*, usuarios.nome AS nome_responsavel FROM eventos INNER JOIN usuarios ON eventos.id_usuario = usuarios.id
      WHERE eventos.id = $id";

  $resultado = mysqli_query($conexao, $sql);

  // Verifica se a consulta foi bem-sucedida
  if (!$resultado) {
    die ("Erro na consulta SQL: " . mysqli_error($conexao));
    exit;
  }

  // Verifica se achou o evento
  if (mysqli_num_rows($resultado) == 0) {
    echo "Evento não encontrado.";
    exit();
  }

  $evento = mysqli_fetch_assoc($resultado);

if ($evento) {
    $titulo     = $evento['titulo'] ?? '';
    $local      = $evento['lugar'] ?? '';
    $data       = $evento['dia'] ?? '';
    $categoria  = $evento['categoria'] ?? '';
    $descricao  = $evento['descricao'] ?? '';
    $responsavel = $evento['nome_responsavel'] ?? '';
    $imagem = $evento['imagem'] ?? '';
} else {
    echo "Evento não encontrado ou erro ao carregar os dados.";
    // Você pode definir valores padrão para evitar warnings abaixo
    $titulo = $local = $data = $horario = $categoria = $descricao = $responsavel = '';
}
if (!empty($data) && $data != '0000-00-00 00:00:00') {
    $dataFormatada = date('d/m/Y', strtotime($data));    // Exemplo: 16/06/2025
    $horario = date('H:i', strtotime($data));            // Exemplo: 14:30
} else {
    $dataFormatada = '';
    $horario = '';
}
} else {
  echo "ID do evento não foi informado.";
  exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title><?= htmlspecialchars($titulo) ?></title>
    <link rel="stylesheet" href="../assets/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Savate:ital,wght@0,200..900;1,200..900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
 <?php include_once("../includes/includeHeader.php"); ?>

  <div class="saiba-mais">
    <div class="container py-5">
      <h1 class="mb-4"><?= htmlspecialchars($titulo) ?></h1>
        <?php if (!empty($imagem)) : ?>
          <img src="../assets/<?= htmlspecialchars($imagem) ?>" class="img-fluid mb-4 rounded" width="350px" alt="<?= htmlspecialchars($titulo)?>">
        <?php endif; ?>
          <p><strong>Local:</strong> <?= htmlspecialchars($local) ?></p>
          <p><strong>Data:</strong> <?= !empty($data) && $data != '0000-00-00' ? date('d/m/Y', strtotime($data)) : '' ?></p>
          <p><strong>Horário:</strong> <?= htmlspecialchars($horario) ?></p>
          <p><strong>Categoria:</strong> <?= htmlspecialchars($categoria) ?></p>
          <p><strong>Responsável:</strong> <?= htmlspecialchars($responsavel) ?></p>
          <p><strong>Descrição:</strong> <?= nl2br(htmlspecialchars($descricao)) ?></p>
      <a href="../eventos/mural.php" class="btn btn-secondary mt-3">Voltar ao mural</a>
    </div>
  </div>
  
  <?php include_once("../includes/includeFooter.php"); ?>

</body>
</html>