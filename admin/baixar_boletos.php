<?php
require_once "../includes/defines.php";
require_once "../includes/funcoes.php";
require_once "../includes/dbConexao.php";
session_start();
$titulo_pagina = "Baixa de boletos";

// Seleciona registros
$sql = "SELECT * FROM pedidos ";
$sql = $sql . " WHERE status = '3' ";
$sql = $sql . " ORDER BY id ASC";
$rs = mysqli_query($conexao, $sql);
$total_registros = mysqli_num_rows($rs);

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
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
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
            <h1 class="c_cinza">Contas a Receber <img src="../img/marcador_setaDir.gif" align="absmiddle" /> <span class="c_preto"><?php print $titulo_pagina; ?></span> </h1>

            <P>Total de registros encontrados: <span class="c_preto"><?php print $total_registros; ?></span></P>
            <table width="100%" cellspacing="0">
                <tr>
                    <td width="14%" class="tabela_titulo">N. do pedido</td>
                    <td width="16%" class="tabela_titulo">Nosso Número</td>
                    <td width="33%" class="tabela_titulo">Status</td>
                    <td width="12%" class="tabela_titulo">Vencimento</td>
                    <td width="14%" align="right" class="tabela_titulo">Valor</td>
                    <td width="11%" class="tabela_titulo">&nbsp;</td>
                </tr>

                <?php
                $total = 0;
                while ($reg = mysqli_fetch_array($rs)) {
                    $id = $reg["id"];
                    $num_ped = $reg["num_ped"];
                    $status = $reg["status"];
                    $vencimento = $reg["vencimento"];
                    $valor = $reg["valor"];
                    $desconto = $reg["desconto"];
                    $total = $total + ($valor - $desconto);
                    ?>
                    <tr>
                        <td class="registro"><?php print $num_ped; ?></td>
                        <td class="registro"><h1 class="c_verde"><strong><?php print zero_esquerda($id, 11); ?></strong></h1></td>
                        <td class="registro">Aguardando Pgto do Boleto Bancário</td>		
                        <td class="registro"><?php print substr($vencimento, 8, 2) . "/" . substr($vencimento, 5, 2) . "/" . substr($vencimento, 0, 4); ?></td>	
                        <td class="registro" align="right"><?php print number_format($valor - $desconto, 2, ',', '.'); ?></td>		
                        <?php if ($vencimento < date("Y-m-d")) { ?>
                            <td class="registro"><div align="right"><a href="cancelar_boletos1.php?acao=alt&id=<?php print $id; ?>&titulo=Baixa de boletos"><img src="../img/btn_cancbol.gif" alt="Baixar pagamento" width="55" height="16" border="0" /></a></div></td>
                        <?php } else { ?>
                            <td class="registro"><div align="right"><a href="baixar_boletos1.php?acao=alt&id=<?php print $id; ?>&titulo=Baixa de boletos"><img src="../img/btn_baixar.gif" alt="Baixar pagamento" width="55" height="16" border="0" /></a></div></td>
                        <?php } ?>
                    </tr>
                <?php } ?>	
                <tr>
                    <td colspan="4" class="registro"><strong>Total a receber</strong></td>
                    <td align="right" class="registro"><strong><?php print number_format($total, 2, ',', '.'); ?></strong></td>
                    <td class="registro">&nbsp;</td>		
                </tr>
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