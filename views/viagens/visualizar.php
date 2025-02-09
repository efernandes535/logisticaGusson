<?php
require_once __DIR__ . '/../../includes/conexao.php';
require_once __DIR__ . '/../../controllers/ViagemController.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: ../index.php');
    exit;
}

$viagemController = new ViagemController($conexao);
$id = $_GET['id'];
$viagem = $viagemController->view($id);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Visualizar Viagem</title>
    <link rel="stylesheet" href="../../css/style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
    <h1>Visualizar Viagem</h1>
    <p><strong>Data:</strong> <?php echo date('d/m/Y', strtotime($viagem['data_viagem'])); ?></p>
    <p><strong>Destino:</strong> <?php echo htmlspecialchars($viagem['destino']); ?></p>
    <p><strong>Hora de Saída:</strong> <?php echo htmlspecialchars($viagem['hora_saida']); ?></p>
    <p><strong>Hora de Chegada:</strong> <?php echo htmlspecialchars($viagem['hora_chegada']); ?></p>
    <p><strong>Placa do Carro:</strong> <?php echo htmlspecialchars($viagem['carro_placa']); ?></p>
    <p><strong>KM Inicial:</strong> <?php echo htmlspecialchars(number_format($viagem['km_inicial'], 0, '', '')); ?></p>
    <p><strong>KM Final:</strong> <?php echo htmlspecialchars(number_format($viagem['km_final'], 0, '', '')); ?></p>
    <p><strong>Motorista:</strong> <?php echo htmlspecialchars($viagem['motorista']); ?></p>
    <p><strong>Observação:</strong> <?php echo htmlspecialchars($viagem['observacao']); ?></p>

    <a href="/">Voltar</a>
      <?php require_once __DIR__ . '/../layout/footer.php'; ?>
</body>
</html>