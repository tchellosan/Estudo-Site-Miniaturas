<?PHP
require_once "../includes/defines.php";
require_once "../includes/funcoes.php";
require_once "../includes/dbConexao.php";
session_start();
if ($_SESSION['acesso'] <> "fs_liberado") {
    print "<meta HTTP-EQUIV='Refresh' CONTENT='0;URL=liberacao.php'>";
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Faça um Site - PHP 5 com banco de dados MySQL</title>
    <link href="estilo_adm.css" rel="stylesheet" type="text/css" />
</head>
<body>
    <div id="corpo">
        <div id="topo">
            <h1>Administração do Site</h1>
        </div>
        <div id="caixa_menu">
            <?PHP include "inc_menu.php" ?>
        </div>
        <div id="caixa_conteudo">
            <table width="100%" height="400" border="0" cellpadding="0" cellspacing="0">
                <tr>
                    <td>&nbsp;</td>
                </tr>
            </table>
        </div>
        <!-- rodape da página -->
        <?PHP include "inc_rodape.php" ?>
    </div>
</body>
</html>