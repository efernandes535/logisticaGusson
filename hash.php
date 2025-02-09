<?php
    $senha = 'acs123';
    $senhaHash = password_hash($senha, PASSWORD_DEFAULT);
    echo $senhaHash;
?>