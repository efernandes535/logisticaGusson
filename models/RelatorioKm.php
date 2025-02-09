<?php
class RelatorioKm
{
    private $conexao;

    public function __construct(PDO $conexao)
    {
        $this->conexao = $conexao;
    }

      public function getAllKmByPlacaWithFilters($placaSelecionada, $dataInicio, $dataFim)
    {
          $sql = "SELECT SUM(km_final - km_inicial) AS total_km_rodado, viagens.carro_placa
          FROM viagens
          WHERE 1=1";

        if (!empty($placaSelecionada)) {
            $sql .= " AND carro_placa = :placa";
        }

         if (!empty($dataInicio)) {
            $sql .= " AND data_viagem >= :data_inicio";
        }
         if (!empty($dataFim)) {
            $sql .= " AND data_viagem <= :data_fim";
        }

        $sql .="  GROUP BY carro_placa ORDER BY total_km_rodado DESC";


         $stmt = $this->conexao->prepare($sql);
           if (!empty($placaSelecionada)) {
            $stmt->bindParam(':placa', $placaSelecionada);
        }

        if (!empty($dataInicio)) {
             $stmt->bindParam(':data_inicio', $dataInicio);
        }

         if (!empty($dataFim)) {
             $stmt->bindParam(':data_fim', $dataFim);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getDistinctPlacas()
    {
         $stmt = $this->conexao->query("SELECT DISTINCT carro_placa FROM viagens");
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }
}