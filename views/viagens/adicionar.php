<?php
require_once __DIR__ . '/../../includes/conexao.php';

// Buscar placas cadastradas
try {
    $stmtPlacas = $conexao->query("SELECT * FROM carros");
    $placas = $stmtPlacas->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Erro ao buscar placas: " . $e->getMessage();
    $placas = [];
}

// Buscar motoristas cadastrados
try {
    $stmtMotoristas = $conexao->query("SELECT * FROM motoristas");
    $motoristas = $stmtMotoristas->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Erro ao buscar motoristas: " . $e->getMessage();
    $motoristas = [];
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
         $kmInicial = !empty($_POST['km_inicial']) ? $_POST['km_inicial'] : 0;
         $kmFinal = !empty($_POST['km_final']) ? $_POST['km_final'] : 0;
        $sql = "INSERT INTO viagens (data_viagem, destino, hora_saida, hora_chegada, carro_placa, km_inicial, km_final, motorista, observacao) VALUES (:data_viagem, :destino, :hora_saida, :hora_chegada, :carro_placa, :km_inicial, :km_final, :motorista, :observacao)";
        $stmt = $conexao->prepare($sql);
        $stmt->execute([
            ':data_viagem' => $_POST['data_viagem'],
            ':destino' => $_POST['destino'],
            ':hora_saida' => $_POST['hora_saida'],
            ':hora_chegada' => $_POST['hora_chegada'],
            ':carro_placa' => $_POST['carro_placa'],
            ':km_inicial' =>  $kmInicial,
            ':km_final' =>  $kmFinal,
            ':motorista' => $_POST['motorista'],
           ':observacao' => $_POST['observacao']
        ]);
        header('Location: index.php');
        exit;
    } catch (PDOException $e) {
        echo "Erro ao adicionar viagem: " . $e->getMessage();
    }
}
?>
<?php require_once __DIR__ . '/../layout/header.php'; ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <h1>Adicionar Viagem</h1>
    <form action="adicionar.php" method="post">
        <label for="data_viagem">Data:</label>
        <input type="date" name="data_viagem" required><br>

        <label for="destino">Destino:</label>
        <input type="text" name="destino" required><br>

        <label for="hora_saida">Hora de Saída:</label>
        <input type="time" name="hora_saida"><br>

        <label for="hora_chegada">Hora de Chegada:</label>
        <input type="time" name="hora_chegada"><br>

        <label for="carro_placa">Placa do Carro:</label>
        <select name="carro_placa" >
            <option value="">Selecione a placa</option>
            <?php foreach ($placas as $placa): ?>
                <option value="<?php echo htmlspecialchars($placa['placa']); ?>"><?php echo htmlspecialchars($placa['placa']); ?></option>
            <?php endforeach; ?>
        </select><br>

        <label for="km_inicial">KM Inicial:</label>
       <input type="number" name="km_inicial" step="0.01" value="<?php if(isset($_POST['km_inicial'])) echo htmlspecialchars(number_format($_POST['km_inicial'], 0, '', '')); ?>">
    <br>

    <label for="km_final">KM Final:</label>
    <input type="number" name="km_final" step="0.01"  value="<?php if(isset($_POST['km_final'])) echo htmlspecialchars(number_format($_POST['km_final'], 0, '', '')); ?>"><br>

        <label for="motorista">Motorista:</label>
        <select name="motorista" required>
          <option value="">Selecione o motorista</option>
            <?php foreach ($motoristas as $motorista): ?>
                <option value="<?php echo htmlspecialchars($motorista['nome']); ?>"><?php echo htmlspecialchars($motorista['nome']); ?></option>
            <?php endforeach; ?>
        </select><br>

        <label for="observacao">Observação:</label>
        <textarea name="observacao" ></textarea><br>

        <input type="submit" value="Adicionar">
    </form>
    <a href="/">Voltar</a>
    <?php require_once __DIR__ . '/../layout/footer.php'; ?>
</body>
</html>