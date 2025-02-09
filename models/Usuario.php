<?php
class Usuario
{
    private $conexao;
     public function __construct(PDO $conexao)
    {
        $this->conexao = $conexao;
    }
    public function findByUsuario($usuario)
     {
       $stmt = $this->conexao->prepare("SELECT * FROM usuarios WHERE usuario = :usuario");
        $stmt->execute([':usuario' => $usuario]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}