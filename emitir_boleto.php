<?php
session_start();

unset($_SESSION['erro']);

require_once "includes/dbConexao.php";

if (!(isset($_SESSION['logado']) && $_SESSION['logado'] == 'S')) {
    header("Location: pedidos.php");
    exit;
}

if (isset($_GET['num_ped'])) {
    $_SESSION['buscar_num_ped'] = $_GET['num_ped'];
}

$sql = "  SELECT";
$sql .= "  b.id";
$sql .= " ,a.num_ped";
$sql .= " ,a.valor";
$sql .= " ,a.data AS data_pedido";
$sql .= " ,a.vencimento";
$sql .= " ,a.desconto";
$sql .= " ,b.nome";
$sql .= " ,b.end_nome";
$sql .= " ,b.end_num";
$sql .= " ,b.end_comp";
$sql .= " ,b.cep";
$sql .= " ,b.bairro";
$sql .= " ,b.cidade";
$sql .= " ,b.uf";
$sql .= " ,a.formpag";
$sql .= " ,a.status";
$sql .= "   FROM pedidos AS a";
$sql .= "  INNER JOIN cadcli AS b";
$sql .= "     ON a.id_cliente = b.id";
$sql .= "  WHERE num_ped = '" . $_SESSION['buscar_num_ped'] . "'";

$rs_pedido = mysqli_query($conexao, $sql);
require "includes/status_acesso_db.php";

$rs_pedido_total_registros = mysqli_num_rows($rs_pedido);

if ($rs_pedido_total_registros == 0) {
    mysqli_free_result($rs_pedido);
    $_SESSION['erro'][] = 38;
    header("Location: erro.php");
    exit;
}

$reg = mysqli_fetch_array($rs_pedido);

if ($reg['formpag'] != 'B') {
    $_SESSION['erro'][] = 55;
    header("Location: erro.php");
    exit;
}
if ($reg['status'] != '3') {
    $_SESSION['erro'][] = 57;
    header("Location: erro.php");
    exit;
}

$data_atual = mktime(0, 0, 0, date("m"), date("d"), date("Y"));
$data_vencimento = mktime(0, 0, 0, (int) substr($reg['vencimento'], 5, 2), (int) substr($reg['vencimento'], 8, 2), (int) substr($reg['vencimento'], 0, 4));

$qtde_dias_a_vencer = ($data_vencimento - $data_atual) / 86400;

if ($qtde_dias_a_vencer <= 0) {
    $_SESSION['erro'][] = 56;
    header("Location: erro.php");
    exit;
}


$data_vencimento = implode("/", array_reverse(explode("-", $reg['vencimento'])));
$data_pedido = implode("/", array_reverse(explode("-", $reg['data_pedido'])));

$dados_boleto['cedente_nome'] = "Miniaturas LTDA";
//$dados_boleto['cedente_cnpj'] = "11.222.333/0001-33";
$dados_boleto['cod_banco'] = str_pad("935", 3, "0", STR_PAD_LEFT);
$dados_boleto['dv_banco'] = "6";
$dados_boleto['moeda'] = "9";
$dados_boleto['num_agencia'] = "0572";
$dados_boleto['dv_agencia'] = "8";
$dados_boleto['num_conta'] = str_pad("5543771", 7, "0", STR_PAD_LEFT);
$dados_boleto['dv_conta'] = "8";
$dados_boleto['carteira'] = "06";
$dados_boleto['aceite'] = "N";
$dados_boleto['especie'] = "R$";
$dados_boleto['especie_doc'] = "99";
$dados_boleto['fixo'] = "0";
$dados_boleto['nosso_numero'] = str_pad($reg['id'], 11, "0", STR_PAD_LEFT);
$dados_boleto['sacado_nome'] = $reg['nome'];
$dados_boleto['sacado_end1'] = $reg['end_nome'] . ", " . $reg['end_num'] . " - " . $reg['end_comp'];
$dados_boleto['sacado_end2'] = substr($reg['cep'], 0, 5) . "-" . substr($reg['cep'], 5, 3) . " " . $reg['bairro'] . ", " . $reg['cidade'] . " - " . $reg['uf'];

$fator_vencimento = strval(floor((mktime(0, 0, 0, substr($data_vencimento, 3, 2), substr($data_vencimento, 0, 2), substr($data_vencimento, 6, 4)) - mktime(0, 0, 0, 10, 7, 1997)) / 86400));

$linha_digitavel_sd = $dados_boleto['cod_banco'];
$linha_digitavel_sd .= $dados_boleto['moeda'];
$linha_digitavel_sd .= $fator_vencimento;
$linha_digitavel_sd .= str_pad(str_replace(".", "", number_format($reg['valor'] - $reg['desconto'], 2)), 10, "0", STR_PAD_LEFT);
$linha_digitavel_sd .= $dados_boleto['num_agencia'];
$linha_digitavel_sd .= $dados_boleto['carteira'];
$linha_digitavel_sd .= $dados_boleto['nosso_numero'];
$linha_digitavel_sd .= $dados_boleto['num_conta'];
$linha_digitavel_sd .= $dados_boleto['fixo'];

//$linha_digitavel_sd = "9999238700000273719000153412387004312980101";
//$linha_digitavel_sd = "9359797400000366250572060000003775655437710";

$soma = 0;
$fator_multiplicacao = 2;
$i = 43;

while ($i--) {
    $soma += $linha_digitavel_sd[$i] * $fator_multiplicacao++;
    if ($fator_multiplicacao > 9) {
        $fator_multiplicacao = 2;
    }
}

$soma *= 10;
$dv_linha_digitavel = $soma % 11;

if ($dv_linha_digitavel == 0 || $dv_linha_digitavel == 10) {
    $dv_linha_digitavel = 1;
}

$campo_linha_digitavel[1] = substr($linha_digitavel_sd, 0, 4) . substr($linha_digitavel_sd, 18, 5);
$campo_linha_digitavel[2] = substr($linha_digitavel_sd, 23, 10);
$campo_linha_digitavel[3] = substr($linha_digitavel_sd, 33, 10);

for ($campo = 1; $campo <= count($campo_linha_digitavel); $campo++) {
    $i = strlen($campo_linha_digitavel[$campo]);
    $soma = 0;
    $campo_invertido = strrev($campo_linha_digitavel[$campo]);

    for ($j = 0; $j < $i; $j++) {
        if (($j % 2) == 0) {
            $res = $campo_invertido[$j] * 2;
            if ($res > 9) {
                $res = strval($res);
                $res = $res[0] + $res[1];
            }
        } else {
            $res = $campo_invertido[$j];
        }
        $soma += $res;
    }

    $dv_campo = $soma % 10;

    if ($dv_campo) {
        $dv_campo = 10 - $dv_campo;
    }

    $campo_linha_digitavel[$campo] .= $dv_campo;
}

$dados_boleto['linha_digitavel_format'] = substr($campo_linha_digitavel[1], 0, 5) . "." . substr($campo_linha_digitavel[1], 5, 5) . " ";
$dados_boleto['linha_digitavel_format'] .= substr($campo_linha_digitavel[2], 0, 5) . "." . substr($campo_linha_digitavel[2], 5, 6) . " ";
$dados_boleto['linha_digitavel_format'] .= substr($campo_linha_digitavel[3], 0, 5) . "." . substr($campo_linha_digitavel[3], 5, 6) . " ";
$dados_boleto['linha_digitavel_format'] .= $dv_linha_digitavel . " ";
$dados_boleto['linha_digitavel_format'] .= substr($linha_digitavel_sd, 4, 14);

$base2[0] = "00110";
$base2[1] = "10001";
$base2[2] = "01001";
$base2[3] = "11000";
$base2[4] = "00101";
$base2[5] = "10100";
$base2[6] = "01100";
$base2[7] = "00011";
$base2[8] = "10010";
$base2[9] = "01010";
$base2_start = "0000";
$base2_stop = "100";

$dados_boleto['linha_digitavel_bin'] = "";

$linha_digitavel_cd = substr($linha_digitavel_sd, 0, 4);
$linha_digitavel_cd .= $dv_linha_digitavel;
$linha_digitavel_cd .= substr($linha_digitavel_sd, 4, 39);

$qtde_digitos = strlen($linha_digitavel_cd);

for ($i = 0; $i < $qtde_digitos; $i += 2) {
    for ($j = 0; $j < 5; $j++) {
        $dados_boleto['linha_digitavel_bin'] .= $base2[$linha_digitavel_cd[$i]][$j];
        $dados_boleto['linha_digitavel_bin'] .= $base2[$linha_digitavel_cd[$i + 1]][$j];
    }
}

$dados_boleto['linha_digitavel_bin'] = $base2_start . $dados_boleto['linha_digitavel_bin'] . $base2_stop;
$qtde_digitos = strlen($dados_boleto['linha_digitavel_bin']);
$codigo_barras = "";

for ($i = 0; $i < $qtde_digitos; $i++) {
    if (($i % 2) == 0) {
        if ($dados_boleto['linha_digitavel_bin'][$i]) {
            $codigo_barras .= '<img class="l" src="img/p.png">';
        } else {
            $codigo_barras .= '<img class="e" src="img/p.png">';
        }
    } else {
        if ($dados_boleto['linha_digitavel_bin'][$i]) {
            $codigo_barras .= '<img class="l" src="img/b.png">';
        } else {
            $codigo_barras .= '<img class="e" src="img/b.png">';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <title>Miniaturas - Boleto Bancário</title>
        <link rel="stylesheet" href="css/emitir_boleto.css">
    </head>
    <body>
        <div class="menu-boleto">
            <img src="img/menu_boleto.gif" alt="Menu boleto" usemap="#menu-boleto">
            <map name="menu-boleto">
                <area id="btn_impressao" shape="rect" coords="333,0,413,20" href="#" alt="Impressão">
                <area shape="rect" coords="432,0,500,20" href="index.php" alt="Home">
                <area shape="rect" coords="520,0,615,20" href="pedidos.php" alt="Meus Pedidos">
                <area shape="rect" coords="636,0,681,20" href="index.php?sair" alt="Encerrar Sessão">
            </map>
        </div>
        <div class="cabecalho">
            <div class="logo"><a href="index.php"><img src="img/logo_fsboleto.gif"></a></div>
            <div class="numero-pedido"> <span>Boleto para pagamento do pedido nº <strong><?php print $reg['num_ped']; ?></strong></span></div>
        </div>
        <div class="recibo-sacado">
            <table>
                <tbody>
                    <tr>
                        <td colspan="5">
                            <label>Local de Pagamento</label>
                            <span>PAGAVEL PREFERENCIALMENTE EM QUALQUER AGÊNCIA BANCO TESTE</span>
                        </td>
                        <td>
                            <label>Vencimento</label>
                            <span><?php print $data_vencimento; ?></span>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="5">
                            <label>Cedente</label>
                            <span><?php print strtoupper($dados_boleto['cedente_nome']); ?></span>
                        </td>
                        <td>
                            <label>Agência/Código Cedente</label>
                            <span><?php print $dados_boleto ['num_agencia'] . "-" . $dados_boleto ['dv_agencia'] . "/" . $dados_boleto ['num_conta'] . "-" . $dados_boleto ['dv_conta']; ?></span>
                        </td>
                    </tr>                    
                    <tr>
                        <td>
                            <label>Data do Documento</label>
                            <span><?php print $data_pedido; ?></span>
                        </td>
                        <td>
                            <label>Nº do Documento</label>
                            <span><?php print $reg['num_ped']; ?></span>
                        </td>
                        <td>
                            <label>Espécie Documento</label>
                            <span><?php print $dados_boleto['especie_doc']; ?></span>
                        </td>
                        <td>
                            <label>Aceite</label>
                            <span><?php print $dados_boleto['aceite']; ?></span>
                        </td>
                        <td>
                            <label>Data do Processamento</label>
                            <span><?php print date("d/m/Y"); ?></span>
                        </td>
                        <td>
                            <label>Nosso Número</label>
                            <span><?php print $dados_boleto ['carteira'] . "-" . $dados_boleto['nosso_numero']; ?></span>
                        </td>
                    </tr>                    
                    <tr>
                        <td>
                            <label>Uso do Banco</label>
                            <span></span>
                        </td>
                        <td>
                            <label>Carteira</label>
                            <span><?php print $dados_boleto['carteira']; ?></span>
                        </td>
                        <td>
                            <label>Espécie</label>
                            <span><?php print $dados_boleto['especie']; ?></span>
                        </td>
                        <td>
                            <label>Quantidade</label>
                            <span></span>
                        </td>
                        <td>
                            <label>Valor</label>
                            <span></span>
                        </td>
                        <td class="valor-documento">
                            <label>(=) Valor do Documento</label>
                            <span><?php print number_format($reg['valor'] - $reg['desconto'], 2, ",", "."); ?></span>
                        </td>
                    </tr>                    
                </tbody>
            </table>
        </div>
        <div class="instrucoes">
            <p class="destaque"><strong>Instruções para Impressão</strong></p>
            <p>- Imprima em impressora jato de tinta (ink jet) ou laser em qualidade normal ou alta (Não use modo econômico).</p>
            <p>- Utilize folha A4 (210 x 297 mm) ou Carta (216 x 279 mm) e margens mínimas à esquerda e à direita do formulário.</p>
            <p>- Corte na linha indicada. Não rasure, risque, fure ou dobre a região onde se encontra o código de barras.</p>
            <p class="destaque"><strong>Pagamento via Internet Banking</strong></p>
            <p>- Caso tenha problemas ao imprimir este boleto, ou se desejar pagá-lo através do Internet Banking, utilize a linha digitável descrita abaixo:</p>
            <p class="linha-digitavel">   <?php print $dados_boleto['linha_digitavel_format']; ?></p>
        </div>
        <div class="dados-sacado">
            <table>
                <tbody>
                    <tr>
                        <td colspan="6">
                            <label>Sacado</label>
                            <p><?php print $dados_boleto['sacado_nome']; ?></p>
                            <p><?php print $dados_boleto['sacado_end1']; ?></p>
                            <p><?php print $dados_boleto['sacado_end2']; ?></p>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="6">
                            <label>Sacador/Avalista</label>
                        </td>
                    </tr>
                </tbody>
            </table>
            <p><strong>Recibo do Sacado - </strong>Altentificação Mecânica</p>
            <img src="img/corte.gif" alt="Corte">
        </div>
        <div class="ficha-compensasao">
            <table>
                <tbody>
                    <tr>
                        <td colspan="6">
                            <table>
                                <tbody>
                                    <tr>
                                        <td>
                                            <img src="img/logo_banco.gif" alt="Banco">
                                        </td>
                                        <td>
                                            <span><?php print $dados_boleto ['cod_banco'] . "-" . $dados_boleto['dv_banco']; ?></span>
                                        </td>
                                        <td>
                                            <span><?php print $dados_boleto['linha_digitavel_format']; ?></span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="5">
                            <label>Local de Pagamento</label>
                            <span>PAGAVEL PREFERENCIALMENTE EM QUALQUER AGÊNCIA BANCO TESTE</span>
                        </td>
                        <td>
                            <label>Vencimento</label>
                            <span><?php print $data_vencimento; ?></span>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="5">
                            <label>Cedente</label>
                            <span><?php print strtoupper($dados_boleto['cedente_nome']); ?></span>
                        </td>
                        <td>
                            <label>Agência/Código Cedente</label>
                            <span><?php print $dados_boleto ['num_agencia'] . "-" . $dados_boleto ['dv_agencia'] . "/" . $dados_boleto ['num_conta'] . "-" . $dados_boleto ['dv_conta']; ?></span>
                        </td>
                    </tr>                    
                    <tr>
                        <td>
                            <label>Data do Documento</label>
                            <span><?php print $data_pedido; ?></span>
                        </td>
                        <td>
                            <label>Nº do Documento</label>
                            <span><?php print $reg['num_ped']; ?></span>
                        </td>
                        <td>
                            <label>Espécie Documento</label>
                            <span><?php print $dados_boleto['especie_doc']; ?></span>
                        </td>
                        <td>
                            <label>Aceite</label>
                            <span><?php print $dados_boleto['aceite']; ?></span>
                        </td>
                        <td>
                            <label>Data do Processamento</label>
                            <span><?php print date("d/m/Y"); ?></span>
                        </td>
                        <td>
                            <label>Nosso Número</label>
                            <span><?php print $dados_boleto ['carteira'] . "-" . $dados_boleto['nosso_numero']; ?></span>
                        </td>
                    </tr>                    
                    <tr>
                        <td>
                            <label>Uso do Banco</label>
                            <span></span>
                        </td>
                        <td>
                            <label>Carteira</label>
                            <span><?php print $dados_boleto['carteira']; ?></span>
                        </td>
                        <td>
                            <label>Espécie</label>
                            <span><?php print $dados_boleto['especie']; ?></span>
                        </td>
                        <td>
                            <label>Quantidade</label>
                            <span></span>
                        </td>
                        <td>
                            <label>Valor</label>
                            <span></span>
                        </td>
                        <td class="valor-documento">
                            <label>(=) Valor do Documento</label>
                            <span><?php print number_format($reg['valor'] - $reg['desconto'], 2, ",", "."); ?></span>
                        </td>
                    </tr>           
                    <tr class="instrucoes-ficha">
                        <td colspan="5" rowspan="7">
                            <label>INSTRUÇÕES</label>
                            <p>ATENÇÃO</p>
                            <p>- Não pague este boleto após o seu vencimento.</p>
                            <p>- Após a data de vencimento o pedido será cancelado e o boleto perderá a validade.</p>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label>(-) Desconto/Abatimento</label>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label>(-) Outras Deduções</label>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label>(+) Mora/Multa</label>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label>(+) Outros Acréscimos</label>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label>(=) Valor Cobrado</label>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="dados-sacado ficha">
            <table>
                <tbody>
                    <tr>
                        <td colspan="6">
                            <label>Sacado</label>
                            <p>   <?php print $dados_boleto['sacado_nome']; ?></p>
                            <p><?php print $dados_boleto['sacado_end1']; ?></p>
                            <p><?php print $dados_boleto['sacado_end2']; ?></p>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="6">
                            <label>Sacador/Avalista</label>
                            <label>Código de Barras</label>
                        </td>
                    </tr>
                </tbody>
            </table>
            <p><strong>Ficha de Compensasão - </strong>Altentificação Mecânica</p>
        </div>
        <div class="codigo-barras">
            <?php print $codigo_barras; ?>
        </div>
        <script src="js/emitir_boleto.js"></script>
    </body>
</html>

<?php
mysqli_free_result($rs_pedido);
mysqli_close($conexao);
?>
