<?php
require_once __DIR__ . '/../includes/conexao.php';
require_once __DIR__ . '/../controllers/RelatorioKmController.php';


$placaSelecionada = '';
$dataInicio = '';
$dataFim = '';
$resultados = [];

$relatorioKmController = new RelatorioKmController($conexao);

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['filtrar'])) {
    $placaSelecionada = $_GET['placa'];
    $dataInicio = $_GET['data_inicio'];
    $dataFim = $_GET['data_fim'];
    $resultados =  $relatorioKmController->index($placaSelecionada, $dataInicio, $dataFim);
}
$placas = $relatorioKmController->listDistinctPlacas();


?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Relatório de KM por Placa</title>
    <link rel="stylesheet" href="../css/style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
    <h1>Relatório de KM por Placa</h1>
    <a href="../index.php">Voltar</a>

    <form action="relatorio_km.php" method="GET">

    <label for="placa">Placa do Veículo:</label>
        <select name="placa">
            <option value="">Todas</option>
            <?php foreach ($placas as $placa): ?>
                 <option value="<?php echo htmlspecialchars($placa); ?>" <?php if($placa == $placaSelecionada) echo 'selected'; ?>><?php echo htmlspecialchars($placa); ?></option>
             <?php endforeach; ?>
        </select><br>

        <label for="data_inicio">Data de Início:</label>
        <input type="date" name="data_inicio" value="<?php echo htmlspecialchars($dataInicio); ?>"><br>

        <label for="data_fim">Data de Fim:</label>
        <input type="date" name="data_fim" value="<?php echo htmlspecialchars($dataFim); ?>"><br>

        <input type="submit" name="filtrar" value="Filtrar">
    </form>

    <table>
        <thead>
            <tr>
                <th>Placa</th>
                <th>Total KM Rodado</th>
            </tr>
        </thead>
         <tbody>
           <?php if(count($resultados) > 0): ?>
            <?php foreach ($resultados as $resultado): ?>
                 <tr>
                    <td><?php echo htmlspecialchars($resultado['carro_placa']); ?></td>
                    <td><?php echo htmlspecialchars(number_format($resultado['total_km_rodado'], 0, '', '')); ?></td>
                </tr>
              <?php endforeach; ?>
          <?php else: ?>
               <tr>
                    <td colspan="2">Nenhum resultado encontrado.</td>
                </tr>
           <?php endif; ?>
        </tbody>
    </table>
        <?php include __DIR__ . '/../includes/rodape.php'; ?>
</body>
</html>