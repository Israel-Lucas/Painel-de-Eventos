<?php
session_start();

if(!isset($_SESSION['usuario'])){
  header("location: ../eventos/login.php");
  exit();
}

require_once '../includes/config.php';

$id_usuario = $_SESSION['usuario'];

$sql = "SELECT * FROM eventos WHERE id_usuario = ?";
$stmt = $conexao->prepare($sql);

if(!$stmt){
  die("Erro ao preparar a consulta: " . $conexao->error);
}

$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$resultado = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Meus Eventos</title>
  <link rel="stylesheet" href="../assets/style.css">
  <link href="https://fonts.googleapis.com/css2?family=Savate:ital,wght@0,200..900;1,200..900&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include_once("../includes/includeHeader.php"); ?>

<div class="container my-4">
  <h2 class="mb-4">Seus Eventos</h2>
  <a href="../eventos/criar_eventos.php" class="btn btn-success mb-3">+ Novo Evento</a>

  <?php if ($resultado && $resultado->num_rows > 0): ?>
    <div class="table-responsive">
      <table class="table table-bordered">
        <thead>
          <tr>
            <th>Título</th>
            <th>Data</th>
            <th>Categoria</th>
            <th>Ações</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($e = $resultado->fetch_assoc()): ?>
            <?php $dataFormatada = date('d/m/Y', strtotime($e['dia'])); ?>
            <tr>
              <td><?= htmlspecialchars($e['titulo']) ?></td>
              <td><?= $dataFormatada ?></td>
              <td><?= htmlspecialchars($e['categoria']) ?></td>
              <td>
                <a href="../eventos/editar_eventos.php?id=<?= $e['id'] ?>" class="btn btn-warning btn-sm">Editar</a>
                <a href="../eventos/delete_eventos.php?excluir=<?= $e['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Tem certeza que deseja excluir este evento?');">Excluir</a>
            </td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  <?php else: ?>
    <p class="text-muted">Nenhum evento encontrado.</p>
  <?php endif; ?>
</div>

<?php include_once("../includes/includeFooter.php"); ?>
</body>
</html>
