<?php
require_once __DIR__ . '/../models/Placa.php';

class PlacaController
{
    private $placaModel;

    public function __construct(PDO $conexao)
    {
        $this->placaModel = new Placa($conexao);
    }

    public function index()
    {
        return $this->placaModel->getAll();
    }

    public function add()
    {
        return $this->placaModel->create($_POST['nova_placa']);
    }

    public function edit($id, $placa)
    {
        return $this->placaModel->update($id, $placa);
    }

     public function delete($id)
    {
        return $this->placaModel->delete($id);
    }
}