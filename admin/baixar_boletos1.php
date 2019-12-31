<?php
require_once "../includes/defines.php";
require_once "../includes/funcoes.php";
require_once "../includes/dbConexao.php";
session_start();

$acao = $_GET['acao'];
$id = $_GET['id'];
$titulo_pagina = $_GET['titulo'];

$editar = "readonly='true'";
$editar_combo = "disabled='disabled'";
$estilo_caixa = "caixa_texto_des";

$sql = "SELECT * FROM pedidos ";
$sql = $sql . " WHERE id = '" . $id . "' ";
$rs = mysqli_query($conexao, $sql);
$reg = mysqli_fetch_array($rs);
$id = $reg['id'];
$num_ped = $reg['num_ped'];
$valor = $reg['valor'];
$desconto = $reg['desconto'];
$vencimento = $reg['vencimento'];
$data = $reg['data'];

function zero_esquerda($numero, $zeros) {
    $numero = str_replace(".", "", $numero);
    $loop = $zeros - strlen($numero);
    for ($i = 0; $i < $loop; $i++) {
        $numero = "0" . $numero;
    }
    return $numero;
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <title>Faça um Site - PHP 5 com banco de dados MySQL</title>
    <link href="estilo_adm.css" rel="stylesheet" type="text/css" />
</head>
<body>
    <form name="form_baixa" method="post" action="baixar_boletos2.php">	
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
                        <td width="62%"><h1 class="c_cinza">Manutenção Cadastral <img src="../img/marcador_setaDir.gif" align="absmiddle" /> Usuários <img src="../img/marcador_setaDir.gif" align="absmiddle" /> <span class="c_preto"><?php print $titulo_pagina; ?></span> </h1>
                        </td>
                        <td width="38%"><div align="right">
                                <?php if ($acao == "alt") { ?>
                                    <a href="baixar_boletos.php?id=<?php print $id; ?>"><img src="../img/btn_fechar.gif" alt="Fechar" border="0" /></a>
                                    <input type="image" name="imageField" src="../img/btn_conf_baixa.gif" />
                                    <input type="hidden" name="acao" value="<?php print $acao; ?>" />
                                    <input type="hidden" name="id" value="<?php print $id; ?>" />	
                                <?php } ?>	
                                <?php if ($acao == "ver") { ?>
                                    <a href="baixar_boletos.php?id=<?php print $id; ?>"><img src="../img/btn_fechar.gif" alt="Fechar" border="0" /></a>
                                <?php } ?>
                            </div></td>
                    </tr>
                </table>

                <div id="caixa_cad">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                            <td width="28%" class="c_preto"><h1>Nosso nómero</h1></td>
                            <td width="72%" class="c_preto"><h1><strong><?php print zero_esquerda($id, 11); ?></strong></h1></td>
                        </tr>
                        <tr>
                            <td class="c_preto"><h1>Número do pedido</h1></td>
                            <td class="c_preto"><h1><?php print $num_ped; ?></h1></td>
                        </tr>
                        <tr>
                            <td class="c_preto"><h1>Valor</h1></td>
                            <td class="c_preto"><h1><strong>R$ <?php print number_format($valor - $desconto, 2, ',', '.'); ?></strong></h1></td>
                        </tr>
                        <tr>
                            <td>Vencimento</td>
                            <td class="c_preto"><h1><strong><?php print substr($vencimento, 8, 2) . "/" . substr($vencimento, 5, 2) . "/" . substr($vencimento, 0, 4); ?></strong></h1></td>
                        </tr>
                        <tr>
                            <td>Data do pedido</td>
                            <td class="c_preto"><h1><?php print substr($data, 8, 2) . "/" . substr($data, 5, 2) . "/" . substr($data, 0, 4); ?></h1></td>
                        </tr>
                    </table>
                </div>
            </div>
            <?php include "inc_rodape.php" ?>
        </div>
    </form>
</body>
</html>
<?php
mysqli_free_result($rs);
mysqli_close($conexao);
?>