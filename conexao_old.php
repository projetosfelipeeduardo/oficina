<?php

    $BDSERVIDOR = '127.0.0.1';
    $BDUSUARIO = 'da';
    $BDSENHA = 'palio2001';
    $BDBANCO = 'da';

    $conexao = mysqli_connect($BDSERVIDOR, $BDUSUARIO, $BDSENHA, $BDBANCO);

    if (mysqli_connect_errno($conexao)) {
        echo "Problemas para conectar no banco de dados. Se o problema persistir, pedimos desculpas e, por favor, envie um e-mail para nós - ailtonsilva68@hotmail.com";
        die();
    }

    $resultado=mysqli_query($conexao,"SET NAMES 'utf8'");
    $resultado=mysqli_query($conexao,'SET character_set_connection=utf8');
    $resultado=mysqli_query($conexao,'SET character_set_client=utf8');
    $resultado=mysqli_query($conexao,'SET character_set_results=utf8');

?>