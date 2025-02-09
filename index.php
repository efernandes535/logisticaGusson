<?php
session_start();

if (!isset($_SESSION['usuario_logado']) || $_SESSION['usuario_logado'] !== true) {
    header('Location: views/login.php');
    exit;
}
    if($_SESSION['usuario_tipo'] !== 'admin'){
      header('Location: views/viagens/index.php');
         exit;
}
header('Location: views/viagens/index.php');
exit;
?>