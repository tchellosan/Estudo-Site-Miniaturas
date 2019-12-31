<?php

define("HOSTNAME", "localhost");
define("USERNAME", "root");
define("PASSWORD", "");
define("DATABASE", "db_php5");

$conexao = mysqli_connect(HOSTNAME, USERNAME, PASSWORD, DATABASE);

if (mysqli_connect_errno()) {
    unset($_SESSION['erro']);
    unset($_SESSION['erro_db']);

    $erro_db = "<br>";
    $erro_db .= "ERRO NO ACESSO AO BANCO DE DADOS: " . "<br>";
    $erro_db .="ERRNO: " . mysqli_connect_errno() . "<br>";
    $erro_db .="ERROR: " . mysqli_connect_error() . "<br><br>";
    $erro_db .= "Entre em contato com o administrador do sistema e informe este erro." . "<br>";

    $_SESSION['erro_db'] = $erro_db;
    $_SESSION['erro'][] = 99;

    header("Location: erro.php");
    exit;
}

mysqli_set_charset($conexao, "utf8");
?>