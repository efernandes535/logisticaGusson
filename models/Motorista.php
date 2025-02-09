<?php
class Motorista
{
    private $conexao;

    public function __construct(PDO $conexao)
    {
        $this->conexao = $conexao;
    }

     public function getAll()
     {
        $stmt = $this->conexao->query("SELECT * FROM motoristas ORDER BY id");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
     }
    public function create($nome)
    {
        $stmt = $this->conexao->prepare("INSERT INTO motoristas (nome) VALUES (:nome)");
        return $stmt->execute([':nome' => $nome]);
    }
    public function update($id, $nome)
    {
       $stmt = $this->conexao->prepare("UPDATE motoristas SET nome = :nome WHERE id = :id");
        return $stmt->execute([':nome' => $nome, ':id' => $id]);
    }
      public function delete($id)
    {
       $stmt = $this->conexao->prepare("DELETE FROM motoristas WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
}