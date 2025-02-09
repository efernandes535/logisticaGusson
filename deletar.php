<?php
require_once __DIR__ . '/includes/conexao.php';
require_once __DIR__ . '/controllers/ViagemController.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: ../views/viagens/index.php');
    exit;
}

$id = $_GET['id'];

try {
    $viagemController = new ViagemController($conexao);
    $success = $viagemController->delete($id);
    header('Location: ../index.php');
    exit;
} catch (PDOException $e) {
    echo "Erro ao deletar viagem: " . $e->getMessage();
}