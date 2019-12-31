<?PHP

require_once "../includes/defines.php";
require_once "../includes/funcoes.php";
require_once "../includes/dbConexao.php";
session_start();

$acao = $_POST['acao'];

$id = $_POST['id'];
$nome = $_POST['nome'];
$login = $_POST['login'];
$senha = $_POST['senha'];

if ($acao == "ins") {
    $titulo_pagina = "Inserir novo registro";
    $mensagem = "<h1 class='c_laranja'>A inclusão do registro foi efetuada com sucesso.</h1>";
    $mensagem = $mensagem . $nome . " - ";

    $sql = "INSERT INTO usuarios ";
    $sql = $sql . "(nome,login,senha,acesso) ";
    $sql = $sql . "VALUES('$nome','$login','$senha','1') ";
    mysqli_query($conexao, $sql);
}

if ($acao == "alt") {
    $titulo_pagina = "Alteração cadastral";
    $mensagem = "<h1 class='c_laranja'>A alteração do registro foi efetuada com sucesso.</h1>";
    $mensagem = $mensagem . $nome . " - ";

    $sql = "UPDATE usuarios SET ";
    $sql = $sql . "nome = '$nome', ";
    $sql = $sql . "login = '$login', ";
    $sql = $sql . "senha = '$senha' ";
    $sql = $sql . " WHERE id = '" . $id . "' ";
    mysqli_query($conexao, $sql);
}

if ($acao == "exc") {

    $sql = "DELETE FROM usuarios ";
    $sql = $sql . " WHERE id = '" . $id . "' ";
    mysqli_query($conexao, $sql);
}
print "<meta HTTP-EQUIV='Refresh' CONTENT='0;URL=cad_usuario_grid.php?id=" . $id . "'>";

mysqli_close($conexao);
?>


