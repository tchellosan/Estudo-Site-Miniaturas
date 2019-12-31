<?PHP

require_once "../includes/defines.php";
require_once "../includes/funcoes.php";
require_once "../includes/dbConexao.php";
session_start();

$acao = $_POST['acao'];

$id = $_POST['id'];
$uf = $_POST['uf'];
$nome = $_POST['nome'];
$frete = $_POST['frete'];
$cepi = $_POST['cepi'];
$cepf = $_POST['cepf'];

if ($acao == "ins") {
    $titulo_pagina = "Inserir novo registro";
    $mensagem = "<h1 class='c_laranja'>A inclus�o do registro foi efetuada com sucesso.</h1>";
    $mensagem = $mensagem . $uf . " - " . $nome;

    $sql = "INSERT INTO tb_estados ";
    $sql = $sql . "(uf,nome,frete,cepi,cepf) ";
    $sql = $sql . "VALUES('$uf','$nome','$frete','$cepi','$cepf') ";
    mysqli_query($conexao, $sql);
}

if ($acao == "alt") {
    $titulo_pagina = "Altera��o cadastral";
    $mensagem = "<h1 class='c_laranja'>A altera��o do registro foi efetuada com sucesso.</h1>";
    $mensagem = $mensagem . $uf . " - " . $nome;

    $sql = "UPDATE tb_estados SET ";
    $sql = $sql . "uf = '$uf', ";
    $sql = $sql . "nome = '$nome', ";
    $sql = $sql . "frete = '$frete', ";
    $sql = $sql . "cepi = '$cepi', ";
    $sql = $sql . "cepf = '$cepf' ";
    $sql = $sql . " WHERE id = '" . $id . "' ";
    mysqli_query($conexao, $sql);
}


if ($acao == "exc") {

    $sql = "DELETE FROM tb_estados ";
    $sql = $sql . " WHERE id = '" . $id . "' ";
    mysqli_query($conexao, $sql);
}

print "<meta HTTP-EQUIV='Refresh' CONTENT='0;URL=cad_frete_grid.php?id=" . $id . "'>";

mysqli_close($conexao);
?>
