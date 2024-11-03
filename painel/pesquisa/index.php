<?php
$conn = new mysqli("SERVIDOR", "USUÁRIO", "SENHA", "BANCO-DE-DADOS");

if ($conn->connect_error) {
    die("Erro na conexão com o banco de dados: " . $conn->connect_error);
}

$termo = isset($_GET['termo']) ? $conn->real_escape_string($_GET['termo']) : '';

$query = "SELECT nome, numeros FROM rifa_compras WHERE nome LIKE '%$termo%' OR numeros LIKE '%$termo%'";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    echo "<table class='table table-striped'><thead><tr><th>Nome</th><th>Números Comprados</th></tr></thead><tbody>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr><td>" . htmlspecialchars($row['nome']) . "</td><td>" . htmlspecialchars($row['numeros']) . "</td></tr>";
    }
    echo "</tbody></table>";
} else {
    echo "<p class='text-center'>Nenhum resultado encontrado.</p>";
}

$conn->close();
?>
