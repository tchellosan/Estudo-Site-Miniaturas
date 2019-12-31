<?PHP
require_once "../includes/defines.php";
require_once "../includes/funcoes.php";
require_once "../includes/dbConexao.php";
session_start();
$acao = $_GET['acao'];

$id = "";
if (isset($_GET['id'])) {
    $id = $_GET['id'];
}

$titulo_pagina = $_GET['titulo'];
if ($acao == "ver") {
    $editar = "readonly='true'";
    $editar_combo = "disabled='disabled'";
    $estilo_caixa = "caixa_texto_des";
} else {
    $editar = "";
    $editar_combo = "";
    $estilo_caixa = "caixa_texto";
}
$sql = "SELECT * FROM usuarios ";
$sql = $sql . " WHERE id = '" . $id . "' ";
$rs = mysqli_query($conexao, $sql);
$reg = mysqli_fetch_array($rs);
$id = $reg['id'];
$nome = $reg['nome'];
$login = $reg['login'];
$senha = $reg['senha'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Faça um Site - PHP 5 com banco de dados MySQL</title>
    <link href="estilo_adm.css" rel="stylesheet" type="text/css" />
</head>
<body>
    <form name="form_cad" method="post" action="cad_usuario1.php">	
        <div id="corpo">
            <div id="topo">
                <h1>Administração do Site</h1>
            </div>

            <div id="caixa_menu">
                <?PHP include "inc_menu.php" ?>
            </div>

            <div id="caixa_conteudo">
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td width="62%"><h1 class="c_cinza">Manutenção Cadastral <img src="../img/marcador_setaDir.gif" align="absmiddle" /> Usuários <img src="../img/marcador_setaDir.gif" align="absmiddle" /> <span class="c_preto"><?PHP print $titulo_pagina; ?></span> </h1>
                        </td>
                        <td width="38%"><div align="right">
                                <?PHP if ($acao == "ins" or $acao == "alt") { ?>
                                    <a href="cad_usuario_grid.php?id=<?PHP print $id; ?>"><img src="../img/btn_fechar_ss.gif" alt="Fechar" border="0" /></a>
                                    <input type="image" name="imageField" src="../img/btn_salvar.gif" />
                                    <input type="hidden" name="acao" value="<?PHP print $acao; ?>" />
                                    <input type="hidden" name="id" value="<?PHP print $id; ?>" />	
                                <?PHP } ?>	
                                <?PHP if ($acao == "exc") { ?>
                                    <input type="image" name="imageField" src="../img/btn_excluir.gif" />
                                    <input type="hidden" name="acao" value="<?PHP print $acao; ?>" />
                                    <input type="hidden" name="id" value="<?PHP print $id; ?>" />	
                                    <a href="cad_usuario_grid.php?id=<?PHP print $id; ?>"><img src="../img/btn_nao_excluir.gif" alt="Fechar" border="0" /></a>	
                                <?PHP } ?>
                                <?PHP if ($acao == "ver") { ?>
                                    <a href="cad_usuario_grid.php?id=<?PHP print $id; ?>"><img src="../img/btn_fechar.gif" alt="Fechar" border="0" /></a>
                                <?PHP } ?>
                            </div></td>
                    </tr>
                </table>

                <div id="caixa_cad">
                    <p><label>Nome:</label><input name="nome" type="text" class="<?PHP print $estilo_caixa; ?>" id="nome" value="<?PHP print $nome; ?>" size="30" maxlength="30" <?PHP print $editar; ?> />
                    </p>
                    <p><label>Login:</label><input name="login" type="text" class="<?PHP print $estilo_caixa; ?>" id="login" value="<?PHP print $login; ?>" size="30" maxlength="30" <?PHP print $editar; ?> />
                    </p>
                    <p><label>Senha:</label><input name="senha" type="text" class="<?PHP print $estilo_caixa; ?>" id="senha" value="<?PHP print $senha; ?>" size="8" maxlength="8" <?PHP print $editar; ?> />
                    </p>
                </div>
            </div>
            <?PHP include "inc_rodape.php" ?>
        </div>
    </form>
</body>
</html>

