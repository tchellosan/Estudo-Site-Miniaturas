<?php

if ($error_list = mysqli_error_list($conexao)) {
    unset($_SESSION['erro']);

    $erro_db = "<br>";
    $erro_db .= "Página..: " . basename($_SERVER['PHP_SELF']) . "<br>";
    $erro_db .= "ErroNo..: " . $error_list[0]['errno'] . "<br>";
    $erro_db .= "Error...: " . $error_list[0]['error'] . "<br>";
    $erro_db .= "SQLState: " . $error_list[0]['sqlstate'] . "<br><br>";
    $erro_db .= "Entre em contato com o administrador do sistema e informe este erro." . "<br>";

    $_SESSION['erro_db'] = $erro_db;
    $_SESSION['erro'][] = 99;

    header("Location: erro.php");
    exit;
}
?>