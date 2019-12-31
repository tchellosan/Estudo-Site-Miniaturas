<?PHP
require_once "../includes/defines.php";
require_once "../includes/funcoes.php";
require_once "../includes/dbConexao.php";
session_start();
$titulo_pagina = "Pedidos confirmados";
// Seleciona registros
$sql = "SELECT cadcli.nome, pedidos.* ";
$sql = $sql . " FROM cadcli ";
$sql = $sql . " INNER JOIN pedidos ";
$sql = $sql . " ON cadcli.id = pedidos.id_cliente ";
$sql = $sql . " WHERE pedidos.status = '" . PGTO_CONFIRMADO . "' ";
$sql = $sql . " ORDER BY id ASC";
$rs = mysqli_query($conexao, $sql);
$total_registros = mysqli_num_rows($rs);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
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
            <h1 class="c_cinza">Relatórios <img src="../img/marcador_setaDir.gif" align="absmiddle" /> <span class="c_preto"><?PHP print $titulo_pagina; ?></span> </h1>
            <P>Total de registros encontrados: <span class="c_preto"><?PHP print $total_registros; ?></span></P>
            <table width="100%" cellspacing="0">
                <tr>
                    <td width="14%" class="tabela_titulo">N. do pedido</td>
                    <td width="31%" class="tabela_titulo">Nome do cliente</td>
                    <td width="31%" class="tabela_titulo">Status</td>
                    <td width="13%" class="tabela_titulo">Vencimento</td>
                    <td width="11%" align="right" class="tabela_titulo">Valor</td>
                </tr>	
                <?PHP
                $total = 0;
                while ($reg = mysqli_fetch_array($rs)) {

                    $id = $reg["id"];
                    $id_cliente = $reg["nome"];
                    $num_ped = $reg["num_ped"];
                    $status = $reg["status"];
                    $vencimento = $reg["vencimento"];
                    $valor = $reg["valor"];
                    $desconto = $reg["desconto"];
                    $total = $total + ($valor - $desconto);
                    ?>
                    <tr>
                        <td class="registro"><?PHP print $num_ped; ?></td>
                        <td class="registro"><?PHP print $id_cliente; ?></td>
                        <td class="registro">Pagamento Confirmado</td>		
                        <td class="registro"><?PHP print substr($vencimento, 8, 2) . "/" . substr($vencimento, 5, 2) . "/" . substr($vencimento, 0, 4); ?></td>	
                        <td class="registro" align="right"><?PHP print number_format($valor - $desconto, 2, ',', '.'); ?></td>		
                    </tr>
                <?PHP } ?>	
                <tr>
                    <td colspan="4" class="registro"><strong>Total de valores cancelados</strong></td>
                    <td align="right" class="registro"><strong><?PHP print number_format($total, 2, ',', '.'); ?></strong></td>		
                </tr>
            </table>
        </div>
        <?PHP include "inc_rodape.php" ?>
    </div>
</body>
</html>
<?PHP
mysqli_free_result($rs);
mysqli_close($conexao);
?>