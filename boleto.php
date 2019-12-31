<?php
session_start();

require_once "includes/defines.php";
require_once "includes/funcoes.php";
require_once "includes/dbConexao.php";

if (isset($_SESSION['num_ped']) && $_SERVER['REQUEST_METHOD'] == 'GET') {
    header("Location: cesta.php");
    exit;
}

if (!isset($_SESSION['num_ped']) && $_SERVER['REQUEST_METHOD'] == 'GET') {
    header("Location: index.php");
    exit;
}

unset($_SESSION['erro']);

if ($_SESSION['meu_cadastro'] == "N") {
    $etapa = "Etapa 4";
    $title = "Confirmação do Seu Pedido";
} else {
    $title = "Meu Cadastro";
}

$sql = "  SELECT";
$sql .= " status";
$sql .= "   FROM pedidos";
$sql .= "  WHERE num_ped = '" . $_SESSION['num_ped'] . "'";

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
$status_pedido = $reg['status'];

switch ($status_pedido) {
    case EM_ANDAMENTO:
        break;
    case AGUARD_APROV_CARTAO:
        $mensagem = "O seu pedido já foi processado e não pode ser atualizado. Para mais informações clique em Meus Pedidos.";
        break;
    case CANCELADO:
        break;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && $status_pedido == EM_ANDAMENTO) {
    validarDadosRecebidos();

    $sql_u = "UPDATE pedidos SET ";
    $sql_u .= "  id_cliente   = " . $_SESSION['id_cliente'];
    $sql_u .= " ,status       = '" . AGUARD_PGTO_BOLETO . "'";
    $sql_u .= " ,data         = '" . implode("-", array_reverse(explode("/", $_SESSION['data_pedido']))) . "'";
    $sql_u .= " ,hora         = '" . $_SESSION['hora_pedido'] . "'";
    $sql_u .= " ,valor        = " . $_SESSION['total_pedido'];
    $sql_u .= " ,vencimento   = '" . implode("-", array_reverse(explode("/", $_SESSION['vencimento']))) . "'";
    $sql_u .= " ,frete        = " . $_SESSION['total_frete'];
    $sql_u .= " ,peso         = " . $_SESSION['total_peso'];
    $sql_u .= " ,desconto     = " . $_SESSION['desconto_boleto'];
    $sql_u .= " ,formpag      = 'B'";
    $sql_u .= " ,parcelas     = " . $_SESSION['qtde_parcelas'];
    $sql_u .= " WHERE num_ped = '" . $_SESSION['num_ped'] . "'";

    mysqli_query($conexao, $sql_u);
    require "includes/status_acesso_db.php";

    $nome = $_SESSION['nome_completo'];
    $num_ped = $_SESSION['num_ped'];
    $valor_compra = number_format($_SESSION['total_pedido'] - $_SESSION['desconto_boleto'], 2, ",", ".");
    $email_cliente = $_SESSION['email'];
    $link = "http://miniaturas.com/pedidos_detalhe?num_ped=" . $_SESSION['num_ped'];

    require_once "includes/email_boleto.php";
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
                <div id="etapa-4">
                    <div class="logo">
                        <a href="index.php"><img src="img/logo_fs.gif" alt="Miniaturas"></a>
                    </div>
                </div>
            <?php } ?>
            <?php if (isset($mensagem)) { ?>
                <div class="mensagem-tela<?php
                if (isset($tipo_mensagem)) {
                    print "-" . $tipo_mensagem;
                }
                ?>"><p><?php print $mensagem; ?></p></div>
                 <?php } ?>
            <div class="cabec-num-ped">
                <div id="pagamento" class="tela-pgto-pagamento">
                    <h1><?php
                        if ($_SESSION['meu_cadastro'] == "N") {
                            print $etapa;
                            ?><img src="img/marcador_setaDir.gif" alt="seta direita"><?php } ?><?php print $title; ?><img src="img/marcador_setaDir.gif" alt="seta direita">Pagamento com Boleto Bancário
                    </h1>
                </div>
                <div id="num-ped" class="tela-pgto-num-ped">
                    <span>Número do pedido: </span>
                    <span class="numero"> <?php print $_SESSION['num_ped']; ?></span>
                </div>
            </div>
            <div id="caixa">
                <h4>Instruções Para o Pagamento</h4>
                <p>Obrigado por comprar em nossa loja. Seu pedido foi aceito e está aguardando o pagamento. Por favor, clique no botão abaixo, imprima o boleto bancário e pague em qualquer banco. Se preferir, pague por intermédio do Internet Banking. Para isso, utilize o <strong>código de barras </strong>localizado na parte superior direita da ficha de compensação do boleto.</p>
                <p>&nbsp;</p>
                <p><img src="img/marcador_atencao.gif" alt="Atenção">Após recebermos a confirmação de pagamento, nós lhe enviaremos um e-mail de notificação confirmando a entrega do pedido.</p>
                <p>&nbsp;</p>
                <p class="ajustar-img"><img src="img/marcador_atencao.gif" alt="Atenção">A data de vencimento do boleto é de 5 (cinco) dias após o fechamento do pedido. ATENÇÃO: <strong>Não pague após o vencimento. </strong>Após esta data o pedido será cancelado e o boleto perderá a validade.</p>
                <p>&nbsp;</p>
                <div class="emitir-boleto">
                    <a href="emitir_boleto.php"><img src="img/btn_imprimirBoleto.gif"></a>
                </div>
                <h4>Informações gerais sobre sua compra</h4>
                <h2>Resumo do Pedido</h2>
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
                        $subtotal_boleto = 0;

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

                        for ($item = 1; $item <= $rs_det_item_total_registros; $item++) {
                            extract(mysqli_fetch_array($rs_det_item), EXTR_PREFIX_ALL, "rs_det_item");
                            require "includes/status_acesso_db.php";

                            $preco_item_desconto = $rs_det_item_preco - (($rs_det_item_preco * $rs_det_item_desconto) / 100);
                            $total_item = $preco_item_desconto * $rs_det_item_qt;
                            $subtotal_pedido += $total_item;
                            $subtotal_boleto += $rs_det_item_preco_boleto * $rs_det_item_qt;
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
                        <tr id="valor-desconto">
                            <td colspan="3"><span>Desconto para pagamento com Boleto Bancário</span></td>
                            <td><span>R$ -<?php print number_format($desconto = ($subtotal_pedido - $subtotal_boleto), 2, ",", "."); ?></span></td>
                        </tr>                        
                        <tr>
                            <td colspan="3"><span>Frete</span></td>
                            <td><span>R$ <?php print number_format($total_frete = ($_SESSION['total_peso'] * $frete), 2, ",", "."); ?></span></td>
                        </tr>
                        <tr>
                            <td colspan="3"><span>Total a pagar</span></td>
                            <td><span>R$ <?php print number_format($total_pedido_cartao = ($subtotal_pedido - $desconto) + $total_frete, 2, ",", "."); ?></span></td>
                        </tr>                        
                    </tfoot>
                </table>   
                <br>
                <h2>Informações sobre a Forma de Pagamento</h2>
                <p>Forma de Pagamento: Boleto Bancário</p>
                <p>Status: Aguardando Pagamento do Boleto</p>
                <p>Data do Pedido: <?php print $_SESSION['data_pedido']; ?></p>
                <p>Vencimento: <?php print $_SESSION['vencimento']; ?></p>
                <p>Peso: <?php print number_format($_SESSION['total_peso'], 3, ",", "."); ?> Kg</p>
                <br>

                <h2>Informações para Envio de Sua Compra</h2>
                <p>Nome do Comprador: <strong><?php print $_SESSION['nome_completo']; ?></strong></p>
                <p>E-mail: <strong><?php print $_SESSION['email']; ?></strong></p>
                <p><?php print $_SESSION['logradouro'] . ", " . $_SESSION['numero_logra'] . " - " . $_SESSION['complemento']; ?></p>
                <p>CEP: <?php print substr($_SESSION['cep'], 0, 5) . "-" . substr($_SESSION['cep'], 5, 3) . " " . $_SESSION['cidade'] . " - " . $_SESSION['uf']; ?></p>
                <br>
                <p><strong>* E-mail de confirmação: </strong>se você não receber um e-mail de confirmação do pedido em breve, verifique sua pasta/diretório de spam ou e-mails indesejados (junk folder) na sua caixa de correio eletrônico. Se encontrar o e-mail em uma dessas pastas, seu provedor da Internet, bloqueador de spam ou software de filtragem está redirecionando as nossas mensagens.</p>
                <p><strong>* Status do pedido: </strong>você pode acompanhar o status do seu pedido, bem como visualizar todas as suas informações, clicando no botão "Meus pedidos" que se encontra na parte superior desse site.</p>

            </div>

            <?php require_once "includes/rodape.php"; ?>	
        </div>
        <?php if ($_SESSION['meu_cadastro'] == "S") { ?>
            <script src="js/subcategoria.js"></script>
        <?php } ?>
    </body>
</html>
<?php
$_SESSION['buscar_num_ped'] = $_SESSION['num_ped'];
unset($_SESSION['id_ped']);
unset($_SESSION['num_ped']);
unset($_SESSION['num_boleto']);
unset($_SESSION['status']);
unset($_SESSION['total_itens']);
unset($_SESSION['total_frete']);
unset($_SESSION['total_peso']);
unset($_SESSION['total_pedido']);
unset($_SESSION['data_pedido']);
unset($_SESSION['hora_pedido']);
unset($_SESSION['vencimento']);
unset($_SESSION['opcao_pgto']);
unset($_SESSION['num_cartao']);
unset($_SESSION['nome_cartao']);
unset($_SESSION['data_cartao_mes']);
unset($_SESSION['data_cartao_ano']);
unset($_SESSION['cod_seg']);
unset($_SESSION['qtde_parcelas']);

mysqli_free_result($rs_pedido);
mysqli_free_result($rs_det_item);
mysqli_close($conexao);

function validarDadosRecebidos() {

    $_SESSION['opcao_pgto'] = test_input($_POST['opcao_pgto']);
    if (!$_SESSION['opcao_pgto']) {
        $_SESSION['erro'][] = 53;
    } else {
        switch ($_SESSION['opcao_pgto']) {
            case "boleto":
                break;
            default:
                $_SESSION['erro'][] = 53;
                break;
        }
    }

    $_SESSION['qtde_parcelas'] = 1;

    if (isset($_SESSION['erro'])) {
        $_SESSION['erro_pgto'] = "Pagamento Cartão de Crédito";
        header("Location: erro.php");
        exit;
    }
}
?>