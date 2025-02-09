<?php
class Viagem
{
    private $conexao;

    public function __construct(PDO $conexao)
    {
        $this->conexao = $conexao;
    }
    public function getAll()
    {
         $stmt = $this->conexao->query("SELECT * FROM viagens ORDER BY data_viagem DESC, hora_saida DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getAllWithFilters($motoristaSelecionado, $dataInicio, $dataFim)
    {
        $sql = "SELECT * FROM viagens WHERE 1=1";

        if (!empty($motoristaSelecionado)) {
            $sql .= " AND motorista = :motorista";
        }

        if (!empty($dataInicio)) {
            $sql .= " AND data_viagem >= :data_inicio";
        }

        if (!empty($dataFim)) {
            $sql .= " AND data_viagem <= :data_fim";
        }

        $sql .= " ORDER BY data_viagem DESC, hora_saida DESC";
        $stmt = $this->conexao->prepare($sql);

       if (!empty($motoristaSelecionado)) {
           $stmt->bindParam(':motorista', $motoristaSelecionado);
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
    public function find($id)
    {
         $stmt = $this->conexao->prepare("SELECT * FROM viagens WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

     public function create($data)
    {
         $sql = "INSERT INTO viagens (data_viagem, destino, hora_saida, hora_chegada, carro_placa, km_inicial, km_final, motorista, observacao) VALUES (:data_viagem, :destino, :hora_saida, :hora_chegada, :carro_placa, :km_inicial, :km_final, :motorista, :observacao)";
          $stmt = $this->conexao->prepare($sql);
          $stmt->execute([
            ':data_viagem' => $data['data_viagem'],
            ':destino' => $data['destino'],
            ':hora_saida' => $data['hora_saida'],
            ':hora_chegada' => $data['hora_chegada'],
            ':carro_placa' => $data['carro_placa'],
            ':km_inicial' => $data['km_inicial'],
            ':km_final' => $data['km_final'],
            ':motorista' => $data['motorista'],
           ':observacao' => $data['observacao']
           ]);
          $sql = "UPDATE motoristas SET ultima_viagem = :data_atual WHERE nome = :motorista";
          $stmt = $this->conexao->prepare($sql);
          return $stmt->execute([
              ':data_atual' => date('Y-m-d H:i:s'),
              ':motorista' => $data['motorista']
            ]);
    }
     public function update($id, $data)
    {
          $sql = "UPDATE viagens SET data_viagem = :data_viagem, destino = :destino, hora_saida = :hora_saida, hora_chegada = :hora_chegada, carro_placa = :carro_placa, km_inicial = :km_inicial, km_final = :km_final, motorista = :motorista, observacao = :observacao WHERE id = :id";
          $stmt = $this->conexao->prepare($sql);
          $stmt->execute([
            ':id' => $id,
            ':data_viagem' => $data['data_viagem'],
            ':destino' => $data['destino'],
            ':hora_saida' => $data['hora_saida'],
            ':hora_chegada' => $data['hora_chegada'],
            ':carro_placa' => $data['carro_placa'],
            ':km_inicial' => $data['km_inicial'],
            ':km_final' => $data['km_final'],
            ':motorista' => $data['motorista'],
             ':observacao' => $data['observacao']
        ]);
            $sql = "UPDATE motoristas SET ultima_viagem = :data_atual WHERE nome = :motorista";
          $stmt = $this->conexao->prepare($sql);
          return $stmt->execute([
              ':data_atual' => date('Y-m-d H:i:s'),
              ':motorista' => $data['motorista']
            ]);
    }

    public function delete($id)
    {
         $stmt = $this->conexao->prepare("DELETE FROM viagens WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
    public function getDistinctMotoristas()
    {
        $stmt = $this->conexao->query("SELECT DISTINCT motorista FROM viagens");
         return  $stmt->fetchAll(PDO::FETCH_COLUMN);
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
     public function getMotoristaDisponibilidade(){
        $stmt = $this->conexao->query("SELECT DISTINCT motorista FROM viagens");
        $motoristas = $stmt->fetchAll(PDO::FETCH_COLUMN);
         $motoristasStatus = [];
        foreach ($motoristas as $motorista) {
            $stmt = $this->conexao->prepare("SELECT COUNT(*) FROM viagens WHERE motorista = :motorista AND hora_chegada IS NULL");
            $stmt->execute([':motorista' => $motorista]);
            $count = $stmt->fetchColumn();
            $motoristasStatus[$motorista] = $count > 0 ? 'viajando' : 'disponivel';
        }
        return $motoristasStatus;
    }
      public function getMotoristaKmRodado()
    {
         $sql = "SELECT motorista, SUM(km_final - km_inicial) AS total_km_rodado
        FROM viagens
         GROUP BY motorista";
        $stmt = $this->conexao->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}