<?php
require_once "../includes/defines.php";
require_once "../includes/funcoes.php";
require_once "../includes/dbConexao.php";
session_start();

$idsel = "";

if (isset($_GET['id'])) {
    $idsel = $_GET['id'];
}

$sql = "SELECT * FROM miniaturas";
$sql = $sql . " ORDER BY id ";
$rs = mysqli_query($conexao, $sql);
$total_registros = mysqli_num_rows($rs);
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
            <?php include "inc_menu.php" ?>
        </div>
        <div id="caixa_conteudo">
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td width="80%"><h1 class="c_cinza">Manutenção Cadastral <img src="../img/marcador_setaDir.gif" align="absmiddle" /> <span class="c_preto">Miniaturas</span> </h1></td>
                    <td width="20%"><h1 align="right"><a href="cad_miniaturas.php?acao=ins&titulo=Inclusão de registro"><img src="../img/btn_inserir.gif" alt="Inserir novo registro" border="0" /></a></h1></td>
                </tr>
            </table>
            <P>Total de registros no cadastro: <span class="c_preto"><?php print $total_registros; ?></span></P>
            <table width="100%" cellspacing="0">
                <tr id="titulo_tabela">
                    <td width="8%" class="tabela_titulo">Código</td>
                    <td width="48%" class="tabela_titulo">Descrição</td>
                    <td width="12%" align="right" class="tabela_titulo">Estoque</td>
                    <td width="18%" align="right" class="tabela_titulo">Preço</td>
                    <td colspan="3" class="tabela_titulo"><div align="right">Ações</div></td>	
                </tr>

                <?php
                while ($reg = mysqli_fetch_array($rs)) {
                    $id = $reg["id"];
                    $codigo = $reg["codigo"];
                    $nome = $reg["nome"];
                    $estoque = $reg["estoque"];
                    $preco = $reg["preco"];

                    if ($idsel == $id) {
                        $fundo = "registro_sel";
                    } else {
                        $fundo = "registro";
                    }
                    ?>
                    <tr>
                        <td class="<?php print $fundo; ?>"><?php print $codigo; ?></td>
                        <td class="<?php print $fundo; ?>"><?php print $nome; ?></td>
                        <td align="right" class="<?php print $fundo; ?>"><?php print $estoque; ?></td>
                        <td align="right" class="<?php print $fundo; ?>">R$ <?php print number_format($preco, 2, ',', '.'); ?></td>
                        <td width="6%" class="<?php print $fundo; ?>"><a href="cad_miniaturas.php?acao=exc&id=<?php print $id; ?>&titulo=Exclusão de registro"><img src="../img/btn_cancelar_reg.gif" alt="Cancelar esse registro" border="0" /></a></td>
                        <td width="5%" class="<?php print $fundo; ?>"><a href="cad_miniaturas.php?acao=alt&id=<?php print $id; ?>&titulo=Alteração de registro"><img src="../img/btn_alterar_reg.gif" alt="Alterar esse registro" border="0" /></a></td>	
                        <td width="3%" class="<?php print $fundo; ?>"><a href="cad_miniaturas.php?acao=ver&id=<?php print $id; ?>&titulo=Detalhes da miniatura"><img src="../img/btn_ver_detalhes.gif" alt="Ver detalhes desse registro" border="0" /></a></td>			
                    </tr>
                <?php } ?>
            </table>
        </div>
        <?php include "inc_rodape.php" ?>
    </div>
</body>
</html>
<?php
mysqli_free_result($rs);
mysqli_close($conexao);
?>