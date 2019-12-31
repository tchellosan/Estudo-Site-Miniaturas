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
    $sql = $sql . "status = '" . CANCELADO . "', ";
    $sql = $sql . "data_pag = '$data_pag' ";
    $sql = $sql . " WHERE id = '" . $id . "' ";
    mysqli_query($conexao, $sql);
}

mysqli_close($conexao);
print "<meta HTTP-EQUIV='Refresh' CONTENT='0;URL=baixar_boletos.php?id=" . $id . "'>";
?>

