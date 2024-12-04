<?php
    $hostname = "localhost";
    $bancodedados = "tde2_pratica";
    $usuario = "root";
    $senha = "";

    $mysqli = new mysqli($hostname, $usuario, $senha, $bancodedados);
    if ($mysqli->connect_error) {
        die("Falha ao conectar ao banco de dados: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error);
    }
    $conexao = $mysqli;
    echo "Conexão bem-sucedida!";
?>