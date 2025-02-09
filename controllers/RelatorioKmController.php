<?php
require_once __DIR__ . '/../models/RelatorioKm.php';

class RelatorioKmController
{
    private $relatorioKmModel;

    public function __construct(PDO $conexao)
    {
        $this->relatorioKmModel = new RelatorioKm($conexao);
    }

    public function index($placaSelecionada = null, $dataInicio = null, $dataFim = null)
    {
        return $this->relatorioKmModel->getAllKmByPlacaWithFilters($placaSelecionada, $dataInicio, $dataFim);
    }
    public function listDistinctPlacas()
    {
        return $this->relatorioKmModel->getDistinctPlacas();
    }
}