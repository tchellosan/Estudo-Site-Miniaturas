<?php

session_start();
require_once "includes/funcoes.php";
require_once "includes/dbConexao.php";

unset($_SESSION['erro']);

if (isset($_GET['subcateg'])) {
    $subcateg = mysqli_escape_string($conexao, test_input($_GET['subcateg']));

    if (isset($_GET['ordenar'])) {
        $parm_ordernar = test_input($_GET['ordenar']);
    } else {
        $parm_ordernar = "1";
    }

    switch ($parm_ordernar) {
        case "2":
            $order_by = "preco ASC";
            break;
        default:
            $order_by = "preco DESC";
            break;
    }

    $sql = "SELECT";
    $sql .= "  codigo";
    $sql .= " ,nome";
    $sql .= " ,estoque";
    $sql .= " ,min_estoque";
    $sql .= " ,preco";
    $sql .= " ,desconto";
    $sql .= " ,credito";
    $sql .= "  FROM miniaturas";
    $sql .= " WHERE subcateg = '$subcateg'";
    $sql .= " ORDER BY " . $order_by;

    $rs_cursor1 = mysqli_query($conexao, $sql);
    require "includes/status_acesso_db.php";

    $rs_cursor1_total_registros = mysqli_num_rows($rs_cursor1);

    if ($rs_cursor1_total_registros == 0) {
        $_SESSION['erro'][] = 5;
        header("Location: erro.php");
        exit;
    }
} else {
    $_SESSION['erro'][] = 36;
    header("Location: erro.php");
    exit;
}

$title = $subcateg . " - Miniaturas";

require_once "includes/principal.php";

mysqli_free_result($rs_cursor1);
mysqli_close($conexao);
?>