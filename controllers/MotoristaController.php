<?php
require_once __DIR__ . '/../models/Motorista.php';

class MotoristaController
{
    private $motoristaModel;

    public function __construct(PDO $conexao)
    {
        $this->motoristaModel = new Motorista($conexao);
    }

    public function index()
    {
        return $this->motoristaModel->getAll();
    }

    public function add()
    {
        return $this->motoristaModel->create($_POST['novo_motorista']);
    }
    public function edit($id, $nome)
    {
         return $this->motoristaModel->update($id, $nome);
    }
      public function delete($id)
    {
         return $this->motoristaModel->delete($id);
    }
}