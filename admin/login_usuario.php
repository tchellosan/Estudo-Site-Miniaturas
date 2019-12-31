<?PHP

require_once "../includes/defines.php";
require_once "../includes/funcoes.php";
require_once "../includes/dbConexao.php";
session_start();
$login = $_POST['login'];
$senha = $_POST['senha'];

$sql = "SELECT * ";
$sql = $sql . " FROM usuarios ";
$sql = $sql . " WHERE login = '" . $login . "' ";
$sql = $sql . " AND senha = '" . $senha . "' ";
$rs = mysqli_query($conexao, $sql);
$reg = mysqli_fetch_array($rs);

$id = $reg['id'];
$acesso = $reg['acesso'];
$total_registros = mysqli_num_rows($rs);
if ($total_registros == 0) {
    $_SESSION['mensagem_erro'] = "Login ou senha inválido";
    print "<meta HTTP-EQUIV='Refresh' CONTENT='0;URL=index.php'>";
} else {
    $_SESSION['mensagem_erro'] = "";
    $_SESSION['acesso'] = "fs_liberado";
    print "<meta HTTP-EQUIV='Refresh' CONTENT='0;URL=entrada.php'>";
}
// Libera os recursos usados pela conex�o atual
mysqli_free_result($rs);
mysqli_close($conexao);
?>