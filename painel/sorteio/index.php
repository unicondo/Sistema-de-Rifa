<?php
$conn = new mysqli("SERVIDOR", "USUÁRIO", "SENHA", "BANCO-DE-DADOS");

if ($conn->connect_error) {
    die("Erro na conexão com o banco de dados: " . $conn->connect_error);
}

$result = $conn->query("SELECT nome, numeros FROM rifa_compras");
$compras = [];

while ($row = $result->fetch_assoc()) {
    $numeros = explode(",", $row['numeros']);
    foreach ($numeros as $numero) {
        $compras[] = ['nome' => $row['nome'], 'numero' => trim($numero)];
    }
}

shuffle($compras);

$primeiro = $compras[0];
$segundo = $compras[1];
$terceiro = $compras[2];

echo "<strong>1º Lugar:</strong> " . htmlspecialchars($primeiro['nome']) . " - Número: " . htmlspecialchars($primeiro['numero']) . "<br>";
echo "<strong>2º Lugar:</strong> " . htmlspecialchars($segundo['nome']) . " - Número: " . htmlspecialchars($segundo['numero']) . "<br>";
echo "<strong>3º Lugar:</strong> " . htmlspecialchars($terceiro['nome']) . " - Número: " . htmlspecialchars($terceiro['numero']) . "<br>";

$conn->close();
?>
