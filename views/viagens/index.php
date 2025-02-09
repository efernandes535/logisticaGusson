<?php
session_start();

if (!isset($_SESSION['usuario_logado']) || $_SESSION['usuario_logado'] !== true) {
    header('Location: ../login.php');
    exit;
}
require_once __DIR__ . '/../../includes/conexao.php';
require_once __DIR__ . '/../../controllers/ViagemController.php';
$viagemController = new ViagemController($conexao);

$motoristaSelecionado = '';
$dataInicio = '';
$dataFim = '';
$viagens = [];

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['filtrar'])) {
    $motoristaSelecionado = isset($_GET['motorista']) ? filter_var($_GET['motorista'], FILTER_SANITIZE_STRING) : '';
    $dataInicio = isset($_GET['data_inicio']) ? filter_var($_GET['data_inicio'], FILTER_SANITIZE_STRING) : '';
    $dataFim = isset($_GET['data_fim']) ? filter_var($_GET['data_fim'], FILTER_SANITIZE_STRING) : '';
    $viagens =  $viagemController->index($motoristaSelecionado, $dataInicio, $dataFim);
} else {
      $viagens =  $viagemController->index("", "", "");
}
$motoristas = $viagemController->listDistinctMotoristas();
$motoristasStatus = $viagemController->getMotoristaDisponibilidade();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Controle de Viagens</title>
    <link rel="stylesheet" href="../../css/style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
    <div style="display: flex; justify-content: space-between; align-items: center;">
          <h1>Lista de Viagens Gusson Equipamentos Médicos e Hospitalares LTDA</h1>
          <a href="../../logout.php" style=" padding: 8px 12px; background-color: #f44336; color: white; border-radius: 4px; text-decoration: none;">Sair</a>
    </div>
    <a href="adicionar.php">Adicionar Viagem</a>
    <a href="../relatorio_km.php">Relatório de KM por Placa</a>
    <a href="../gerenciar_placas.php">Gerenciar Placas</a>
    <a href="../../views/gerenciar_motoristas.php">Gerenciar Motoristas</a>
    <a href="relatorio_geral.php">Relatório Geral de Viagens</a>
    <?php if($_SESSION['usuario_tipo'] == 'admin'): ?>
    <form action="" method="GET">
        <label for="motorista">Motorista:</label>
          <select name="motorista">
                <option value="">Todos</option>
                <?php foreach ($motoristas as $motorista): ?>
                    <option value="<?php echo htmlspecialchars($motorista); ?>" <?php if(isset($motoristaSelecionado) && $motorista == $motoristaSelecionado) echo 'selected'; ?>><?php echo htmlspecialchars($motorista); ?>
                     <?php if(isset($motoristasStatus[$motorista])): ?>
                         <span class="<?php echo $motoristasStatus[$motorista] == 'disponivel' ? 'status-disponivel' : 'status-viajando'; ?>"></span>
                         <?php endif; ?>
                    </option>
                <?php endforeach; ?>
            </select><br>

        <label for="data_inicio">Data de Início:</label>
        <input type="date" name="data_inicio" value="<?php echo htmlspecialchars($dataInicio); ?>"><br>

        <label for="data_fim">Data de Fim:</label>
        <input type="date" name="data_fim" value="<?php echo htmlspecialchars($dataFim); ?>"><br>

        <input type="submit" name="filtrar" value="Filtrar">
    </form>
    <?php endif; ?>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Data</th>
                <th>Destino</th>
                <th>Saída</th>
                <th>Chegada</th>
                <th>Placa</th>
                <th>KM Inicial</th>
                <th>KM Final</th>
                <th>KM Rodado</th>
                <th>Motorista</th>
                 <th>Observação</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($viagens) > 0): ?>
                <?php $i = 1; foreach ($viagens as $viagem): ?>
                <tr>
                     <td><?php echo $i++; ?></td>
                    <td><?php echo date('d/m/Y', strtotime($viagem['data_viagem'])); ?></td>
                    <td><?php echo htmlspecialchars($viagem['destino']); ?></td>
                    <td><?php echo htmlspecialchars($viagem['hora_saida']); ?></td>
                    <td><?php echo htmlspecialchars($viagem['hora_chegada']); ?></td>
                    <td><?php echo htmlspecialchars($viagem['carro_placa']); ?></td>
                     <td><?php echo htmlspecialchars(number_format($viagem['km_inicial'], 0, '', '')); ?></td>
                    <td><?php echo htmlspecialchars(number_format($viagem['km_final'], 0, '', '')); ?></td>
                     <td><?php
                        $kmInicial = floatval($viagem['km_inicial']);
                        $kmFinal = floatval($viagem['km_final']);
                         $kmRodado = $kmFinal - $kmInicial;
                       echo htmlspecialchars(number_format($kmRodado, 0, '', ''));
                     ?></td>
                    <td><?php echo htmlspecialchars($viagem['motorista']); ?></td>
                      <td><?php echo htmlspecialchars($viagem['observacao']); ?></td>
                    <td class="acoes">
                        <a href="visualizar.php?id=<?php echo $viagem['id']; ?>">Visualizar</a>
                        <a href="editar.php?id=<?php echo $viagem['id']; ?>">Editar</a>
                          <?php if($_SESSION['usuario_tipo'] == 'admin'): ?>
                          <a href="../../deletar.php?id=<?php echo $viagem['id']; ?>" class="botao-deletar" onclick="return confirm('Tem certeza que deseja deletar esta viagem?')">Deletar</a>
                           <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="12">Nenhuma viagem registrada.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
      <?php include __DIR__ . '/../layout/footer.php'; ?>
</body>
</html>