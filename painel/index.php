<?php
session_start();

$senhaCorreta = "SENHA-AQUI";

if (isset($_POST['senha'])) {
    if ($_POST['senha'] === $senhaCorreta) {
        $_SESSION['autenticado'] = true;
    } else {
        $erro = "Senha incorreta!";
    }
}

$conn = new mysqli("SERVIDOR", "USUÁRIO", "SENHA", "BANCO-DE-DADOS");

if ($conn->connect_error) {
    die("Erro na conexão com o banco de dados: " . $conn->connect_error);
}

if (isset($_SESSION['autenticado']) && $_SESSION['autenticado']) {
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action'])) {
        if ($_POST['action'] == 'save') {
            $nome = $_POST['nome'];
            $numeros = implode(",", $_POST['numeros']);

            $stmt = $conn->prepare("INSERT INTO rifa_compras (nome, numeros) VALUES (?, ?)");
            if (!$stmt) {
                die("Erro na preparação do comando SQL: " . $conn->error);
            }
            $stmt->bind_param("ss", $nome, $numeros);
            if ($stmt->execute()) {
                echo "<div class='alert alert-success text-center'>Compra salva com sucesso!</div>";
            } else {
                echo "<div class='alert alert-danger text-center'>Erro ao salvar a compra: " . $stmt->error . "</div>";
            }
            $stmt->close();
        } elseif ($_POST['action'] == 'delete') {
            $numerosExcluir = $_POST['numeros_excluir'];
            $stmt = $conn->prepare("UPDATE rifa_compras SET numeros = TRIM(BOTH ',' FROM REPLACE(CONCAT(',', numeros, ','), ?, ',')) WHERE FIND_IN_SET(?, numeros)");
            if (!$stmt) {
                die("Erro na preparação do comando SQL para exclusão: " . $conn->error);
            }
            $numerosComVirgulas = ',' . $numerosExcluir . ',';
            $stmt->bind_param("ss", $numerosComVirgulas, $numerosExcluir);
            if ($stmt->execute()) {
                echo "<div class='alert alert-success text-center'>Número(s) excluído(s) com sucesso!</div>";
            } else {
                echo "<div class='alert alert-danger text-center'>Erro ao excluir o(s) número(s): " . $stmt->error . "</div>";
            }
            $stmt->close();
        }
    }
} else {
    ?>
    <!DOCTYPE html>
    <html lang="pt-br">
    <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Login - Rifa da Fernanda</title>
      <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
      <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
      <style>
        body {
          font-family: 'Inter', sans-serif;
          background-color: #f8f9fa;
        }
        .container {
          max-width: 400px;
          margin-top: 100px;
          background-color: #ffffff;
          border-radius: 8px;
          box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.1);
          padding: 30px;
        }
      </style>
    </head>
    <body>
      <div class="container">
        <h3 class="text-center mb-4">Acesso Restrito</h3>
        <?php if (isset($erro)) { echo "<div class='alert alert-danger text-center'>$erro</div>"; } ?>
        <form method="POST" action="">
          <div class="mb-3">
            <label for="senha" class="form-label">Senha</label>
            <input type="password" name="senha" class="form-control" id="senha" required>
          </div>
          <button type="submit" class="btn btn-primary">Entrar</button>
        </form>
      </div>
    </body>
    </html>
    <?php
    exit;
}

$limite = 5;
$pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$inicio = ($pagina > 1) ? ($pagina * $limite) - $limite : 0;

$totalCompradores = $conn->query("SELECT COUNT(*) AS total FROM rifa_compras")->fetch_assoc()['total'];
$totalPaginas = ceil($totalCompradores / $limite);

$result = $conn->query("SELECT nome, numeros FROM rifa_compras ORDER BY nome ASC LIMIT $inicio, $limite");
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Administração - Rifa da Fernanda</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Inter', sans-serif;
      background-color: #f8f9fa;
    }
    .container {
      max-width: 800px;
      margin-top: 30px;
      background-color: #ffffff;
      border-radius: 8px;
      box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.1);
      padding: 30px;
    }
    .form-label {
      font-weight: 500;
    }
    .btn-primary, .btn-danger, .btn-success {
      width: 100%;
      font-weight: 600;
      margin-top: 10px;
    }
    .alert {
      margin-top: 20px;
    }
  </style>
</head>
<body>
  <div class="container">
    <h3 class="text-center mb-4">Registrar Compra de Rifa</h3>
    <form method="POST" action="">
      <input type="hidden" name="action" value="save">
      <div class="mb-3">
        <label for="nome" class="form-label">Nome do Comprador</label>
        <input type="text" name="nome" class="form-control" id="nome" required>
      </div>
      <div class="mb-3">
        <label for="numeros" class="form-label">Números Comprados (separe por vírgula)</label>
        <input type="text" name="numeros[]" class="form-control" id="numeros" required>
      </div>
      <button type="submit" class="btn btn-primary">Salvar Compra</button>
    </form>

    <h3 class="text-center mt-5 mb-4">Excluir Número(s) da Rifa</h3>
    <form method="POST" action="">
      <input type="hidden" name="action" value="delete">
      <div class="mb-3">
        <label for="numeros_excluir" class="form-label">Número(s) para Excluir (separe por vírgula)</label>
        <input type="text" name="numeros_excluir" class="form-control" id="numeros_excluir" required>
      </div>
      <button type="submit" class="btn btn-danger">Excluir Número(s)</button>
    </form>

    <div class="mt-5 mb-4">
      <label for="pesquisa" class="form-label">Pesquisar Comprador ou Número</label>
      <input type="text" id="pesquisa" class="form-control" placeholder="Digite o nome ou número">
      <button class="btn btn-secondary mt-2" onclick="pesquisar()">Pesquisar</button>
    </div>

    <div class="modal fade" id="resultadoModal" tabindex="-1" aria-labelledby="resultadoModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="resultadoModalLabel">Resultado da Pesquisa</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body" id="resultadoPesquisa">
            Resultados aparecerão aqui.
          </div>
        </div>
      </div>
    </div>

    <h3 class="text-center mb-4">Lista de Compradores</h3>
    <table class="table table-striped">
      <thead>
        <tr>
          <th>Nome</th>
          <th>Números Comprados</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($row = $result->fetch_assoc()) { ?>
          <tr>
            <td><?php echo htmlspecialchars($row['nome']); ?></td>
            <td><?php echo htmlspecialchars($row['numeros']); ?></td>
          </tr>
        <?php } ?>
      </tbody>
    </table>

    <nav>
      <ul class="pagination justify-content-center">
        <?php for ($i = 1; $i <= $totalPaginas; $i++) { ?>
          <li class="page-item <?php if ($i == $pagina) echo 'active'; ?>">
            <a class="page-link" href="?pagina=<?php echo $i; ?>"><?php echo $i; ?></a>
          </li>
        <?php } ?>
      </ul>
    </nav>

    <h3 class="text-center mt-5 mb-4">Sorteio</h3>
    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#sorteioModal">Sortear</button>
    <div class="modal fade" id="sorteioModal" tabindex="-1" aria-labelledby="sorteioModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="sorteioModalLabel">Resultado do Sorteio</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body" id="resultadoSorteio">
            Clique em "Sortear" para ver os resultados.
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-primary" onclick="realizarSorteio()">Sortear</button>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script>
    function realizarSorteio() {
      fetch('/sorteio')
        .then(response => response.text())
        .then(data => {
          document.getElementById('resultadoSorteio').innerHTML = data;
        });
    }

    function pesquisar() {
      const termo = document.getElementById('pesquisa').value;
      fetch('/pesquisa?termo=' + encodeURIComponent(termo))
        .then(response => response.text())
        .then(data => {
          document.getElementById('resultadoPesquisa').innerHTML = data;
          new bootstrap.Modal(document.getElementById('resultadoModal')).show();
        });
    }
  </script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php $conn->close(); ?>
