<?php
require_once __DIR__ . '/../includes/conexao.php';
require_once __DIR__ . '/../controllers/MotoristaController.php';
$motoristaController = new MotoristaController($conexao);

$mensagem = '';
$exibirFormularioAdicionar = false; // Inicialmente, não exibir o formulário

// Lógica para exibir/ocultar o formulário
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['adicionar'])) {
    $exibirFormularioAdicionar = true;
}

// Adicionar motorista
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['adicionar'])) {
    try {
        $success = $motoristaController->add();
        $mensagem = "Motorista adicionado com sucesso!";
         $exibirFormularioAdicionar = false;
    } catch (PDOException $e) {
        $mensagem = "Erro ao adicionar motorista: " . $e->getMessage();
    }
}

// Editar motorista
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['editar'])) {
     $id = $_POST['id'];
     $nome = $_POST['nome'];
    try {
        $success = $motoristaController->edit($id, $nome);
        $mensagem = "Motorista atualizado com sucesso!";
    } catch (PDOException $e) {
        $mensagem = "Erro ao atualizar motorista: " . $e->getMessage();
    }
}
// Deletar motorista
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['deletar_id'])) {
    $id = $_GET['deletar_id'];
    try {
        $success = $motoristaController->delete($id);
        $mensagem = "Motorista deletado com sucesso!";
    } catch (PDOException $e) {
        $mensagem = "Erro ao deletar motorista: " . $e->getMessage();
    }
}
$motoristas = $motoristaController->index();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Gerenciar Motoristas</title>
    <link rel="stylesheet" href="../css/style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
    <h1>Gerenciar Motoristas</h1>
    <a href="../index.php">Voltar</a>
    <?php if (!empty($mensagem)): ?>
            <p><?php echo $mensagem; ?></p>
        <?php endif; ?>

    <!-- Botão para exibir o formulário -->
    <?php if (!$exibirFormularioAdicionar): ?>
        <a href="gerenciar_motoristas.php?adicionar" class="botao-adicionar">Adicionar Motorista</a>
    <?php endif; ?>


    <?php if($exibirFormularioAdicionar): ?>
     <h2>Adicionar Motorista</h2>
    <form action="gerenciar_motoristas.php" method="post">
        <label for="novo_motorista">Novo Motorista:</label>
        <input type="text" name="novo_motorista" required>
        <input type="submit" name="adicionar" value="Adicionar">
          <a href="gerenciar_motoristas.php">Cancelar</a>
    </form>
      <?php endif; ?>

    <h2>Lista de Motoristas</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($motoristas as $motorista): ?>
                <tr>
                   <td><?php echo htmlspecialchars($motorista['id']); ?></td>
                    <form action="gerenciar_motoristas.php" method="post">
                         <td><input type="text" name="nome" value="<?php echo htmlspecialchars($motorista['nome']); ?>" required>
                        </td>
                        <input type="hidden" name="id" value="<?php echo htmlspecialchars($motorista['id']); ?>">
                           <td>
                            <input type="submit" name="editar" value="Salvar">
                             <a href="gerenciar_motoristas.php?deletar_id=<?php echo $motorista['id']; ?>" onclick="return confirm('Tem certeza que deseja deletar?')">Deletar</a>
                        </td>
                   </form>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
         <?php include __DIR__ . '/../includes/rodape.php'; ?>
</body>
</html>