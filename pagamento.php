<?php
session_start();

require_once "includes/funcoes.php";
require_once "includes/dbConexao.php";

if (!(isset($_SESSION['logado']) && $_SESSION['logado'] == 'S')) {
    header("Location: cesta.php");
    exit;
}

if (isset($_SESSION['ultimo_num_ped']) && (!isset($_SESSION['total_itens']) || $_SESSION['total_itens'] == 0)) {
    header("Location: pedidos.php");
    exit;
}

unset($_SESSION['erro']);

if ($_SESSION['meu_cadastro'] == "N") {
    $etapa = "Etapa 3";
    $title = "Pagamento";
} else {
    $title = "Meu Cadastro";
}

$sql = " SELECT";
$sql .= " frete";
$sql .= "  FROM tb_estados";
$sql .= " WHERE uf = '" . $_SESSION['uf'] . "'";

$rs_uf = mysqli_query($conexao, $sql);
require "includes/status_acesso_db.php";

$reg = mysqli_fetch_array($rs_uf);
$frete = $reg['frete'];
mysqli_free_result($rs_uf);

if (isset($_SESSION['num_ped'])) {
    $num_ped = $_SESSION['num_ped'];

    $sql = " SELECT";
    $sql .= "   a.*";
    $sql .= " , b.max_parcelas";
    $sql .= "  FROM itens a";
    $sql .= " INNER JOIN miniaturas b";
    $sql .= "    ON a.codigo = b.codigo";
    $sql .= " WHERE num_ped = '$num_ped'";
    $sql .= " ORDER BY a.id";

    $rs_det_item = mysqli_query($conexao, $sql);
    require "includes/status_acesso_db.php";

    $rs_det_item_total_registros = mysqli_num_rows($rs_det_item);
} else {
    $rs_det_item_total_registros = 0;
}

if ($rs_det_item_total_registros == 0) {
    $_SESSION['erro'][] = 35;
    header("Location: erro.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang = "pt-br">
    <head>
        <meta charset = "UTF-8">
        <title><?php
            if ($_SESSION['meu_cadastro'] == "N") {
                print $etapa . " - ";
            }
            ?><?php print ucwords($title); ?></title>
        <link rel="stylesheet" href="css/estilo_site.css">
    </head>
    <body >
        <div id="corpo">
            <?php if ($_SESSION['meu_cadastro'] == "S") { ?>
                <div id="topo">
                    <?php require_once "includes/menu_superior.php"; ?>
                </div>
                <div id="menuSup">
                    <?php require_once "includes/menu_categorias.php"; ?>
                </div>
            <?php } else { ?>
                <div id="etapa-3">
                    <div class="logo">
                        <a href="index.php"><img src="img/logo_fs.gif" alt="Miniaturas"></a>
                    </div>
                </div>
            <?php } ?>
            <div class="cabec-num-ped">
                <div id="pagamento">
                    <h1><?php
                        if ($_SESSION['meu_cadastro'] == "N") {
                            print $etapa;
                            ?><img src="img/marcador_setaDir.gif" alt="seta direita"><?php } ?><?php print $title; ?>
                    </h1>
                </div>
                <div id="num-ped">
                    <span>Número do pedido: </span>
                    <span class="numero"> <?php print $_SESSION['num_ped']; ?></span>
                </div>
            </div>
            <form name="enviar_pagamento" method="POST">
                <div id="caixa">
                    <h2>Seu Pedido</h2>
                    <p>* Antes de confirmar o seu pagamento, confira as informações contidas nesta tela. Para realizar alterações no pedido clique em: "Alterar Pedido".</p>
                    <table id="resumo-pedido">
                        <thead>
                            <tr>
                                <th>Descrição do Produto</th>
                                <th>Quantidade</th>
                                <th>Preço Unitário R$</th>
                                <th>Total R$</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $subtotal_pedido = 0;
                            $total_peso = 0;
                            $subtotal_boleto = 0;
                            $maior_max_parcelas = 0;

                            for ($item = 1; $item <= $rs_det_item_total_registros; $item++) {
                                extract(mysqli_fetch_array($rs_det_item), EXTR_PREFIX_ALL, "rs_det_item");
                                require "includes/status_acesso_db.php";

                                $preco_item_desconto = $rs_det_item_preco - (($rs_det_item_preco * $rs_det_item_desconto) / 100);
                                $total_item = $preco_item_desconto * $rs_det_item_qt;
                                $subtotal_pedido += $total_item;
                                $total_peso += ($rs_det_item_qt * $rs_det_item_peso);
                                $subtotal_boleto += $rs_det_item_preco_boleto * $rs_det_item_qt;
                                if ($rs_det_item_max_parcelas > $maior_max_parcelas) {
                                    $maior_max_parcelas = $rs_det_item_max_parcelas;
                                }
                                ?>
                                <tr>
                                    <td><span><?php print $rs_det_item_codigo; ?> - <?php print $rs_det_item_nome; ?></span></td>
                                    <td><span><?php print $rs_det_item_qt; ?></span></td>
                                    <td><span>R$ <?php print number_format($preco_item_desconto, 2, ",", "."); ?></span></td>
                                    <td><span>R$ <?php print number_format($total_item, 2, ",", "."); ?></span></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                        <tfoot>
                            <tr></tr>
                            <tr>
                                <td colspan="3"><span>Subtotal</span></td>
                                <td><span>R$ <?php print number_format($subtotal_pedido, 2, ",", "."); ?></span></td>
                            </tr>
                            <tr>
                                <td colspan="3"><span>Frete</span></td>
                                <td><span>R$ <?php print number_format($total_frete = ($total_peso * $frete), 2, ",", "."); ?></span></td>
                            </tr>
                            <tr>
                                <td colspan="3"><span>Total a pagar</span></td>
                                <td><span>R$ <?php print number_format($total_pedido_cartao = $subtotal_pedido + $total_frete, 2, ",", "."); ?></span></td>
                            </tr>                        
                        </tfoot>
                    </table>            
                    <div class="alterar-pedido"><a class="link-detalhes" href="cesta.php">Alterar Pedido</a></div>
                    <h2>Local de Entrega</h2>
                    <div class="local-entrega">
                        <div class="endereco-atual">
                            <span><?php print $_SESSION['logradouro'] . ", " . $_SESSION['numero_logra'] . " - " . $_SESSION['complemento']; ?></span><br>
                            <span>CEP: <?php print substr($_SESSION['cep'], 0, 5) . "-" . substr($_SESSION['cep'], 5, 3) . " " . $_SESSION['cidade'] . " - " . $_SESSION['uf']; ?></span>
                        </div>
                        <div class="alterar-endereco">
                            <a class="link-detalhes" href="cadastro.php?operacao=alterar">Alterar Endereço</a>
                        </div>
                    </div>
                </div>
                <h4>Formas de Pagamento</h4>
                <div class="caixa-forma-pgto">
                    <h2>Opção 1: <span>Quero pagar este pedido por intermédio de </span>BOLETO BANCÁRIO</h2>
                    <div class="caixa-pgto">
                        <div class="caixa-boleto-opcao">
                            <div class="opcao-forma-pgto"><input type="radio" value="boleto" name="opcao_pgto" id="boleto"><label for="boleto"><img src="img/marcador_boleto.gif" alt="Boleto"><span>(Boleto bancário)</span></label></div>
                        </div>
                        <div class="caixa-boleto-confirmar">
                            <p>Valor da fatura para pagamento com boleto bancário: <strong>R$ <?php print number_format($subtotal_boleto + $total_frete, 2, ",", "."); ?></strong></p><br>
                            <p><img src="img/marcador_atencao.gif" alt="Atenção">O boleto deve ser impresso após a confirmação do pedido, porque não enviamos via correio.</p><br>
                            <p>A data de vencimento do boleto é de 5 dias corridos após o fechamento do pedido, após esta data, ele perderá a validade. Na impossibilidade de imprimi-lo, faça o pagamento do boleto pelo Home Banking de seu banco. Para isso, utilize o código de barras localizado na parte superior esquerda da ficha de compensação do boleto. Não é possível pagar o seu pedido através de DOC, transferência ou depósito para conta indicada neste boleto.</p>
                            <div class="confirmar-pgto">
                                <button id="btn_boleto" type="submit" formaction="boleto.php"><img src="img/btn_confirmarCompra.gif" alt="Confirmar compra"></button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="caixa-forma-pgto">
                    <h2>Opção 2: <span>Quero pagar este pedido por intermédio de </span>CARTÃO DE CRÉDITO</h2>
                    <div class="caixa-pgto">
                        <div class="caixa-credito-opcao">
                            <p><strong>Selecione um cartão de crédito</strong></p>
                            <div class="opcao-forma-pgto"><input type="radio" value="visa" name="opcao_pgto" id="visa"
                                <?php
                                if (isset($_SESSION['opcao_pgto']) && ($_SESSION['opcao_pgto'] == "visa")) {
                                    print "checked ";
                                }
                                ?>><label for="visa"><img src="img/c_visa.gif" alt="Visa"><span>(Visa)</span></label></div>
                            <div class="opcao-forma-pgto"><input type="radio" value="mastercard" name="opcao_pgto" id="mastercard"
                                <?php
                                if (isset($_SESSION['opcao_pgto']) && ($_SESSION['opcao_pgto'] == "mastercard")) {
                                    print "checked ";
                                }
                                ?>><label for="mastercard"><img src="img/c_mastercard.gif" alt="Mastercard"><span>(Mastercard)</span></label></div>
                            <div class="opcao-forma-pgto"><input type="radio" value="amex" name="opcao_pgto" id="amex" 
                                <?php
                                if (isset($_SESSION['opcao_pgto']) && ($_SESSION['opcao_pgto'] == "amex")) {
                                    print "checked ";
                                }
                                ?>><label for="amex"><img src="img/c_amex.gif" alt="Amex"><span>(Amex)</span></label></div>
                            <div class="opcao-forma-pgto"><input type="radio" value="diners" name="opcao_pgto" id="diners"
                                <?php
                                if (isset($_SESSION['opcao_pgto']) && ($_SESSION['opcao_pgto'] == "diners")) {
                                    print "checked ";
                                }
                                ?>><label for="diners"><img src="img/c_diners.gif" alt="Diners"><span>(Diners)</span></label></div>
                        </div>
                        <div class="caixa-credito-confirmar">
                            <p><img src="img/marcador_atencao.gif" alt="Atenção">É necessário um cartão de crédito válido (Visa, Mastercard, Amex ou Diners). Para sua segurança, usamos a tecnologia SSL (Secure Socket Layer) para proteger as informações de seu cartão.</p>
                            <h6>Informações sobre o seu cartão de crédito</h6>
                            <div class="campo-entrada">
                                <label for="num_cartao">Nº do Cartão:</label>
                                <input type="text" id="num_cartao" name="num_cartao" maxlength="16" value="<?php
                                if (isset($_SESSION['num_cartao'])) {
                                    print $_SESSION['num_cartao'];
                                }
                                ?>">
                            </div>
                            <div class="campo-entrada">
                                <label for="nome_cartao">Nome no Cartão:</label>
                                <input type="text" id="nome_cartao" name="nome_cartao" maxlength="60" value="<?php
                                if (isset($_SESSION['nome_cartao'])) {
                                    print $_SESSION['nome_cartao'];
                                }
                                ?>">
                            </div>
                            <div class="campo-entrada">
                                <label for="data_cartao_mes">Data de Validade:</label>
                                <input type="text" id="data_cartao_mes" name="data_cartao_mes" maxlength="2" value="<?php
                                if (isset($_SESSION['data_cartao_mes'])) {
                                    print $_SESSION['data_cartao_mes'];
                                }
                                ?>"><span> / </span><input type="text" id="data_cartao_ano" name="data_cartao_ano" maxlength="2" value="<?php
                                       if (isset($_SESSION['data_cartao_ano'])) {
                                           print $_SESSION['data_cartao_ano'];
                                       }
                                       ?>">
                            </div>
                            <div class="campo-entrada">
                                <label for="cod_seg">Código de Segurança:</label>
                                <input type="text" id="cod_seg" name="cod_seg" maxlength="4" value="<?php
                                if (isset($_SESSION['cod_seg'])) {
                                    print $_SESSION['cod_seg'];
                                }
                                ?>">
                            </div>
                            <br>
                            <p>O Código de Segurança do Cartão é um código de 3 ou 4 dígitos gravado ou impresso no verso dos cartões Visa, MasterCard, Diners. No cartão Amex este código se encontra na frente do cartão.</p>
                            <div>
                                <h6>Selecione o número de parcelas</h6>
                                <table class="parcelas-pgto">
                                    <tbody>
                                        <?php
                                        for ($parcela = 1; $parcela <= $maior_max_parcelas; $parcela++) {
                                            ?>
                                            <?php if ($parcela % 2 == 1) { ?>
                                                <tr>
                                                    <td class="parcelas"><label for="parcela_<?php print $parcela ?>"><input 
                                                            <?php
                                                            if (isset($_SESSION['qtde_parcelas'])) {
                                                                if ($_SESSION['qtde_parcelas'] == $parcela) {
                                                                    print "checked ";
                                                                }
                                                            } else if ($parcela == 1) {
                                                                print "checked ";
                                                            }
                                                            ?>type="radio" name="qtde_parcelas" id="parcela_<?php print $parcela ?>" value="<?php print $parcela ?>"><span><?php print $parcela ?> x de <strong>R$ <?php print number_format($total_pedido_cartao / $parcela, 2, ",", "."); ?></strong> sem juros</span></label></td>
                                                                <?php if ($parcela == $maior_max_parcelas) { ?>
                                                        <td></td>
                                                    </tr>                                                                
                                                <?php } ?>
                                            <?php } else { ?>
                                            <td class="parcelas"><label for="parcela_<?php print $parcela ?>"><input 
                                                    <?php
                                                    if (isset($_SESSION['qtde_parcelas'])) {
                                                        if ($_SESSION['qtde_parcelas'] == $parcela) {
                                                            print "checked ";
                                                        }
                                                    } else if ($parcela == 1) {
                                                        print "checked ";
                                                    }
                                                    ?>
                                                        type="radio" name="qtde_parcelas" id="parcela_<?php print $parcela ?>" value="<?php print $parcela ?>"><span><?php print $parcela ?> x de <strong>R$ <?php print number_format($total_pedido_cartao / $parcela, 2, ",", "."); ?></strong> sem juros</span></label></td>
                                            </tr>                                
                                        <?php } ?>
                                    <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="confirmar-pgto">
                                <button id="btn_credito" type="submit" formaction="cartao.php"><img src="img/btn_confirmarCompra.gif" alt="Confirmar compra"></button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <?php require_once "includes/rodape.php"; ?>	
        </div>
        <script src="js/pagamento.js"></script>
        <?php if ($_SESSION['meu_cadastro'] == "S") { ?>
            <script src="js/subcategoria.js"></script>
        <?php } ?>
    </body>
</html>
<?php
$_SESSION['total_frete'] = $total_frete;
$_SESSION['total_peso'] = $total_peso;
$_SESSION['total_pedido'] = $total_pedido_cartao;
$_SESSION['data_pedido'] = date("d/m/Y", time());
$_SESSION['hora_pedido'] = date("H:i:s", time());
$_SESSION['vencimento'] = date("d/m/Y", strtotime("5 days"));
$_SESSION['desconto_boleto'] = $subtotal_pedido - $subtotal_boleto;

mysqli_free_result($rs_det_item);
mysqli_close($conexao);
?>