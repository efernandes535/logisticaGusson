<?php
require_once __DIR__ . '/../../includes/conexao.php';
require_once __DIR__ . '/../../controllers/ViagemController.php';
$viagemController = new ViagemController($conexao);
$id = $_GET['id'];
$viagem = $viagemController->edit($id);

try {
     $stmtPlacas = $conexao->query("SELECT * FROM carros");
        $placas = $stmtPlacas->fetchAll(PDO::FETCH_ASSOC);

         $stmtMotoristas = $conexao->query("SELECT * FROM motoristas");
        $motoristas = $stmtMotoristas->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Erro ao buscar viagem: " . $e->getMessage();
    exit;
        $placas = [];
         $motoristas = [];
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
       $success = $viagemController->update($id);
        if($success){
          header('Location: index.php');
          exit;
       }else{
            echo "Erro ao editar viagem";
       }
    } catch (PDOException $e) {
        echo "Erro ao editar viagem: " . $e->getMessage();
    }
}
?>
<?php require_once __DIR__ . '/../layout/header.php'; ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <h1>Editar Viagem</h1>
    <form action="editar.php?id=<?php echo $id; ?>" method="post">
        <label for="data_viagem">Data:</label>
        <input type="date" name="data_viagem" value="<?php echo date('Y-m-d', strtotime($viagem['data_viagem'])); ?>" required><br>

        <label for="destino">Destino:</label>
        <input type="text" name="destino" value="<?php echo htmlspecialchars($viagem['destino']); ?>" required><br>

        <label for="hora_saida">Hora de Saída:</label>
        <input type="time" name="hora_saida" value="<?php echo htmlspecialchars($viagem['hora_saida']); ?>"><br>

        <label for="hora_chegada">Hora de Chegada:</label>
        <input type="time" name="hora_chegada" value="<?php echo htmlspecialchars($viagem['hora_chegada']); ?>"><br>

        <label for="carro_placa">Placa do Carro:</label>
        <select name="carro_placa" >
            <option value="">Selecione a placa</option>
            <?php foreach ($placas as $placa): ?>
                <option value="<?php echo htmlspecialchars($placa['placa']); ?>" <?php if($placa['placa'] == $viagem['carro_placa']) echo 'selected'; ?>><?php echo htmlspecialchars($placa['placa']); ?></option>
            <?php endforeach; ?>
        </select><br>

        <label for="km_inicial">KM Inicial:</label>
        <input type="number" name="km_inicial" step="0.01" value="<?php echo htmlspecialchars(number_format($viagem['km_inicial'], 0, '', '')); ?>"><br>

        <label for="km_final">KM Final:</label>
        <input type="number" name="km_final" step="0.01" value="<?php echo htmlspecialchars(number_format($viagem['km_final'], 0, '', '')); ?>"><br>

        <label for="motorista">Motorista:</label>
        <select name="motorista" required>
         <option value="">Selecione o motorista</option>
            <?php foreach ($motoristas as $motorista): ?>
                <option value="<?php echo htmlspecialchars($motorista['nome']); ?>" <?php if($motorista['nome'] == $viagem['motorista']) echo 'selected'; ?>><?php echo htmlspecialchars($motorista['nome']); ?></option>
            <?php endforeach; ?>
        </select><br>

        <label for="observacao">Observação:</label>
        <textarea name="observacao"><?php echo htmlspecialchars($viagem['observacao']); ?></textarea><br>

        <input type="submit" value="Salvar">
    </form>
    <a href="/">Voltar</a>
    <?php require_once __DIR__ . '/../layout/footer.php'; ?>
</body>
</html>