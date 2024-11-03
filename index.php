<?php
$conn = new mysqli("SERVIDOR", "USUÁRIO", "SENHA", "BANCO-DE-DADOS");

$result = $conn->query("SELECT numeros FROM rifa_compras");
$comprados = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $comprados = array_merge($comprados, explode(",", $row['numeros']));
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <meta name="author" content="Bruno Ferreira Alves">
    <meta name="DC.rights" content="https://allvz.com.br/">
    <meta name="copyright" content="© 2024 Bruno Ferreira Alves">
    <meta name="description" content="Sistema de Rifa Online.">

    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="blue">
    <meta name="apple-mobile-web-app-title" content="Rifa da Fêh">
  <title>Rifa da Fernanda</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <meta property="og:title" content="Rifa da Fernanda">
    <meta property="og:description" content="Toque aqui para abrir e escolha seus números!">
    <meta property="og:image" content="Coloque aqui a imagem que você deseja exibir como thumbnail ao compartilhar sua rifa nas redes sociais.">
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://aminharifa.online/">
    
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Rifa da Fernanda">
    <meta name="twitter:description" content="Toque aqui para abrir e escolha seus números!">
    <meta name="twitter:image" content="Coloque aqui a imagem que você deseja exibir como thumbnail ao compartilhar sua rifa nas redes sociais.">
  <style>
    body {
      font-family: 'Inter', sans-serif;
    }
    #number-grid {
      display: grid;
      grid-template-columns: repeat(5, 1fr);
      gap: 10px;
      justify-items: center;
      padding: 20px;
    }
    .number-box {
      width: 60px;
      height: 60px;
      border: 1px solid #b08b28;
      text-align: center;
      line-height: 56px;
      border-radius: 50%;
      cursor: pointer;
      color: #b08b28;
      font-weight: bold;
      font-size: 1.2em;
    }
    .number-box.selected { background-color: #b08b28; color: #fff; }
    .number-box.disabled { background-color: #28a745; color: #fff; cursor: not-allowed; }
    .bottom-bar {
      position: fixed;
      bottom: 0;
      left: 0;
      right: 0;
      background-color: #f8f9fa;
      padding: 10px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      border-top: 1px solid #ddd;
      z-index: 1000;
      box-shadow: 0 -2px 10px rgba(0,0,0,0.1);
    }
  </style>
</head>
<body class="bg-light">
  
  <div class="container my-5">
    <!-- Carrossel -->
    <div id="carouselExample" class="carousel slide mb-4" data-bs-ride="carousel">
      <div class="carousel-inner">
        <div class="carousel-item active">
          <img src="Coloque aqui a imagem que você deseja exibir no slide" class="d-block w-100" alt="...">
          <div class="carousel-caption d-none d-md-block">
            <h5>Item 1</h5>
            <p>Descrição do Item 1.</p>
          </div>
        </div>
        <div class="carousel-item">
          <img src="Coloque aqui a imagem que você deseja exibir no slide" class="d-block w-100" alt="...">
          <div class="carousel-caption d-none d-md-block">
            <h5>Item 2</h5>
            <p>Descrição do Item 2.</p>
          </div>
        </div>
        <div class="carousel-item">
          <img src="Coloque aqui a imagem que você deseja exibir no slide" class="d-block w-100" alt="...">
          <div class="carousel-caption d-none d-md-block">
            <h5>Item 3</h5>
            <p>Descrição do Item 3.</p>
          </div>
        </div>
      </div>
      <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Anterior</span>
      </button>
      <button class="carousel-control-next" type="button" data-bs-target="#carouselExample" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Próximo</span>
      </button>
    </div>

    <h3 class="text-center mb-4">Selecione os números para participar e boa sorte! 💖</h3>
    <div id="number-grid" class="container">
      <?php
      for ($i = 1; $i <= 50; $i++) {
          $class = in_array($i, $comprados) ? 'number-box disabled' : 'number-box';
          echo "<div class='$class' data-number='$i'>$i</div>";
      }
      ?>
    </div>
  </div>
  <div class="bottom-bar" id="bottom-bar" style="display: none;">
    <span id="selected-numbers"></span>
    <span id="total-price"></span>
    <a id="buy-button" href="#" class="btn btn-success">Comprar</a>
  </div>
  <footer class="bg-light text-center text-lg-start mt-5">
    <div class="text-center p-3">
      <p>Esta rifa está sendo realizada por <strong>[Responsável pela Rifa]</strong>.</p>
      <p>Para mais informações ou para entrar em contato, clique no botão abaixo:</p>
      <a href="https://wa.me/55COLOQUE-SEU-NÚMERO-COM-DDD" class="btn btn-primary">Contato via WhatsApp</a>
    </div>
    <div class="text-center p-3 bg-light">
      <h5>Como comprar a rifa:</h5>
      <ol>
        <li>Selecione os números que deseja comprar clicando sobre eles.</li>
        <li>Os números selecionados aparecerão na parte inferior da página.</li>
        <li>Verifique o total a pagar e clique no botão "Comprar".</li>
        <li>Você será redirecionado para o WhatsApp, onde poderá confirmar a compra.</li>
        <li>Aguarde a confirmação da sua compra e boa sorte!</li>
      </ol>
    </div>
  </footer>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    const selectedNumbers = [];
    const pricePerNumber = 10;

    $(document).on('click', '.number-box:not(.disabled)', function () {
      const number = $(this).data('number');
      if ($(this).hasClass('selected')) {
        $(this).removeClass('selected');
        selectedNumbers.splice(selectedNumbers.indexOf(number), 1);
      } else {
        $(this).addClass('selected');
        selectedNumbers.push(number);
      }
      const totalPrice = selectedNumbers.length * pricePerNumber;

      if (selectedNumbers.length > 0) {
        $('#bottom-bar').show();
        $('#selected-numbers').text(`Números: ${selectedNumbers.join(', ')}`);
        $('#total-price').text(`Total: R$ ${totalPrice.toFixed(2)}`);
        $('#buy-button').attr('href', `https://wa.me/55COLOQUE-SEU-NÚMERO-COM-DDD?text=Quero%20comprar%20os%20números:%20${selectedNumbers.join(', ')}%20-%20Total:%20R$%20${totalPrice.toFixed(2)}`);
      } else {
        $('#bottom-bar').hide();
      }
    });
  </script>
</body>
</html>
