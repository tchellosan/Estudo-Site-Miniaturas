<?php

session_start();
require_once "includes/funcoes.php";
require_once "includes/dbConexao.php";

unset($_SESSION['erro']);

if (isset($_GET['id-categoria']) and isset($_GET['cat-nome'])) {
    $id_categoria = mysqli_escape_string($conexao, test_input($_GET['id-categoria']));
    $cat_nome = test_input($_GET['cat-nome']);

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
    $sql .= " WHERE id_categoria = " . $id_categoria;
    $sql .= " ORDER BY " . $order_by;

    $rs_cursor1 = mysqli_query($conexao, $sql);
    require "includes/status_acesso_db.php";

    $rs_cursor1_total_registros = mysqli_num_rows($rs_cursor1);

    if ($rs_cursor1_total_registros == 0) {
        $_SESSION['erro'][] = 4;
        header("Location: erro.php");
        exit;
    }
} else {
    $_SESSION['erro'][] = 3;
    header("Location: erro.php");
    exit;
}

$title = $cat_nome . " - Miniaturas";
$deco_banner = $id_categoria;

require_once "includes/principal.php";

mysqli_free_result($rs_cursor1);
mysqli_close($conexao);
?>