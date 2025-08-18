<?php
require_once '../includes/config.php';

$sql = "SELECT * FROM eventos ORDER BY dia DESC";
$result = mysqli_query($conexao, $sql);

if(!$result){
  die('Erro na consulta' . mysqli_error($conexao));
}
?>



<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Mural de Eventos</title>
  <link rel="stylesheet" href="../assets/style.css">
  <link href="https://fonts.googleapis.com/css2?family=Savate:ital,wght@0,200..900;1,200..900&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
 <?php include_once("../includes/includeHeader.php"); ?>
  <div class="container my-4">
      <h1 class="text-center mb-4">Mural de Eventos</h1>
      
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
          <?php while ($evento = mysqli_fetch_assoc($result)): ?>
            <div class="col">
              <div class="card h-100 shadow-sm">
                <?php if ($evento['imagem']): ?>
                  <img src="../assets/<?= htmlspecialchars($evento['imagem']) ?>" class="card-img-top" alt="<?= htmlspecialchars($evento['titulo']) ?>">
                <?php endif; ?>
                <div class="card-body">
                  <h5 class="card-title"><?= htmlspecialchars($evento['titulo']) ?></h5>
                  <p class="card-text"><?= htmlspecialchars(mb_strimwidth($evento['descricao'], 0, 100, '...')) ?></p>
                  <div class="text-end">
                    <a href="../eventos/evento.php?id=<?= $evento['id']?>" class="btn btn-outline-primary">Saiba mais</a>
                  </div>
                </div>
              </div>
            </div>
          <?php endwhile; ?>
        </div>
      </div>
  </div>
    
<?php include_once("../includes/includeFooter.php"); ?>
</body>
</html>