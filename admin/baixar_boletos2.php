<?PHP

require_once "../includes/defines.php";
require_once "../includes/funcoes.php";
require_once "../includes/dbConexao.php";
session_start();

$acao = $_POST['acao'];

$id = $_POST['id'];
//$status = $_POST['status'];
$data_pag = date('Y-m-d');

if ($acao == "alt") {
    $titulo_pagina = "Alteração cadastral";
    $mensagem = "<h1 class='c_laranja'>A alteração do registro foi efetuada com sucesso.</h1>";

    $sql = "UPDATE pedidos SET ";
    $sql = $sql . "  status = '" . PGTO_CONFIRMADO . "'";
    $sql = $sql . " ,data_pag = '$data_pag' ";
    $sql = $sql . " WHERE id = '" . $id . "' ";
    mysqli_query($conexao, $sql);

    $sql = "SELECT * FROM pedidos ";
    $sql = $sql . " WHERE id = '" . $id . "' ";
    $rs = mysqli_query($conexao, $sql);
    $reg = mysqli_fetch_array($rs);
    $num_ped = $reg['num_ped'];

    $sqli = "SELECT * FROM itens";
    $sqli = $sqli . " WHERE num_ped = '" . $num_ped . "' ";
    $rsi = mysqli_query($conexao, $sqli);
    $total_registros = mysqli_num_rows($rsi);

    while ($regi = mysqli_fetch_array($rsi)) {
        $codigo = $regi["codigo"];
        $qt = $regi["qt"];

        $sqlm = "SELECT * FROM miniaturas";
        $sqlm = $sqlm . " WHERE codigo = '" . $codigo . "' ";

        $rsm = mysqli_query($conexao, $sqlm);
        $regm = mysqli_fetch_array($rsm);

        $qt_estoque = $regm['estoque'];
        $qt_estoque_atual = $qt_estoque - $qt;

        $sqlu = "UPDATE miniaturas SET ";
        $sqlu = $sqlu . "estoque = '$qt_estoque_atual' ";
        $sqlu = $sqlu . " WHERE codigo = '" . $codigo . "' ";
        mysqli_query($conexao, $sqlu);
    }
}

mysqli_free_result($rs);
mysqli_free_result($rsm);
mysqli_close($conexao);
print "<meta HTTP-EQUIV='Refresh' CONTENT='0;URL=baixar_boletos.php?id=" . $id . "'>";
?>