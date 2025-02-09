<?php
require_once __DIR__ . '/../includes/conexao.php';
require_once __DIR__ . '/../controllers/PlacaController.php';
$placaController = new PlacaController($conexao);

$mensagem = '';
$exibirFormularioAdicionar = false; // Inicialmente, não exibir o formulário

// Lógica para exibir/ocultar o formulário
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['adicionar'])) {
    $exibirFormularioAdicionar = true;
}

// Adicionar placa
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['adicionar'])) {
    try {
        $success = $placaController->add();
         $mensagem = "Placa adicionada com sucesso!";
         $exibirFormularioAdicionar = false;
    } catch (PDOException $e) {
        $mensagem = "Erro ao adicionar placa: " . $e->getMessage();
    }
}

// Editar placa
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['editar'])) {
     $id = $_POST['id'];
     $placa = $_POST['placa'];
    try {
        $success = $placaController->edit($id, $placa);
         $mensagem = "Placa atualizada com sucesso!";
    } catch (PDOException $e) {
        $mensagem = "Erro ao atualizar placa: " . $e->getMessage();
    }
}

// Deletar placa
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['deletar_id'])) {
    $id = $_GET['deletar_id'];
    try {
        $success = $placaController->delete($id);
        $mensagem = "Placa deletada com sucesso!";
    } catch (PDOException $e) {
        $mensagem = "Erro ao deletar placa: " . $e->getMessage();
    }
}

// Buscar todas as placas
$placas = $placaController->index();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Gerenciar Placas</title>
     <link rel="stylesheet" href="../css/style.css">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
    <h1>Gerenciar Placas</h1>
    <a href="../index.php">Voltar</a>
     <?php if (!empty($mensagem)): ?>
            <p><?php echo $mensagem; ?></p>
        <?php endif; ?>

    <!-- Botão para exibir o formulário -->
    <?php if (!$exibirFormularioAdicionar): ?>
        <a href="gerenciar_placas.php?adicionar" class="botao-adicionar">Adicionar Placa</a>
    <?php endif; ?>


    <?php if($exibirFormularioAdicionar): ?>
    <h2>Adicionar Placa</h2>
     <form action="gerenciar_placas.php" method="post">
        <label for="nova_placa">Nova Placa:</label>
        <input type="text" name="nova_placa" required>
        <input type="submit" name="adicionar" value="Adicionar">
          <a href="gerenciar_placas.php">Cancelar</a>
    </form>
    <?php endif; ?>

    <h2>Lista de Placas</h2>
        <table>
            <thead>
                <tr>
                   <th>ID</th>
                    <th>Placa</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($placas as $placa): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($placa['id']); ?></td>
                         <form action="gerenciar_placas.php" method="post">
                             <td> <input type="text" name="placa" value="<?php echo htmlspecialchars($placa['placa']); ?>" required>
                            </td>
                            <input type="hidden" name="id" value="<?php echo htmlspecialchars($placa['id']); ?>">
                               <td>
                              <input type="submit" name="editar" value="Salvar">
                                <a href="gerenciar_placas.php?deletar_id=<?php echo $placa['id']; ?>" onclick="return confirm('Tem certeza que deseja deletar?')">Deletar</a>
                            </td>
                        </form>
                   </tr>
            <?php endforeach; ?>
             </tbody>
        </table>
   <?php include __DIR__ . '/../includes/rodape.php'; ?>
</body>
</html>