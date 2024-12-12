<?php
// Configurações do banco de dados
$host = 'localhost:3306';  // Endereço do servidor MySQL
$dbname = 'academiaDJ'; // Nome do banco de dados
$username = 'root';   // Usuário do MySQL
$password = 'cimatec';       // Senha do MySQL

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Configura para exibir erros
} catch (PDOException $e) {
    echo "Erro na conexão: " . $e->getMessage();
    exit;
}
?>