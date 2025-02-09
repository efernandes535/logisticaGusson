<?php
require_once __DIR__ . '/../config/config.php';
try {
    $conexao = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Erro de conexão: " . $e->getMessage();
    die();
}
?>