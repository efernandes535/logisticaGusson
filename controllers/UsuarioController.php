<?php
require_once __DIR__ . '/../models/Usuario.php';

class UsuarioController
{
    private $usuarioModel;

    public function __construct(PDO $conexao)
    {
        $this->usuarioModel = new Usuario($conexao);
    }

    public function login($usuario, $senha)
    {
        $usuarioBanco = $this->usuarioModel->findByUsuario($usuario);

        if ($usuarioBanco && password_verify($senha, $usuarioBanco['senha'])) {
            return true;
        } else {
             return false;
        }
    }
}