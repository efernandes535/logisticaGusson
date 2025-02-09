<?php
class Placa
{
    private $conexao;

    public function __construct(PDO $conexao)
    {
        $this->conexao = $conexao;
    }

    public function getAll()
    {
        $stmt = $this->conexao->query("SELECT * FROM carros ORDER BY id");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($placa)
    {
        $stmt = $this->conexao->prepare("INSERT INTO carros (placa) VALUES (:placa)");
        return $stmt->execute([':placa' => $placa]);
    }

    public function update($id, $placa)
    {
        $stmt = $this->conexao->prepare("UPDATE carros SET placa = :placa WHERE id = :id");
         return $stmt->execute([':placa' => $placa, ':id' => $id]);
    }

    public function delete($id)
    {
         $stmt = $this->conexao->prepare("DELETE FROM carros WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
}