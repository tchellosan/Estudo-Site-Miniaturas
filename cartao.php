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
    $sql_u .= " ,status       = '" . AGUARD_APROV_CARTAO . "'";
    $sql_u .= " ,data         = '" . implode("-", array_reverse(explode("/", $_SESSION['data_pedido']))) . "'";
    $sql_u .= " ,hora         = '" . $_SESSION['hora_pedido'] . "'";
    $sql_u .= " ,valor        = " . $_SESSION['total_pedido'];
    $sql_u .= " ,vencimento   = '" . implode("-", array_reverse(explode("/", $_SESSION['vencimento']))) . "'";
    $sql_u .= " ,frete        = " . $_SESSION['total_frete'];
    $sql_u .= " ,peso         = " . $_SESSION['total_peso'];
    $sql_u .= " ,formpag      = 'C'";
    $sql_u .= " ,cartao       = '" . $_SESSION['opcao_pgto'] . "'";
    $sql_u .= " ,num_cartao   = '" . $_SESSION['num_cartao'] . "'";
    $sql_u .= " ,nome_cartao  = '" . $_SESSION['nome_cartao'] . "'";
    $sql_u .= " ,venc_cartao  = '" . $_SESSION['data_cartao_mes'] . $_SESSION['data_cartao_ano'] . "'";
    $sql_u .= " ,cods_cartao  = '" . $_SESSION['cod_seg'] . "'";
    $sql_u .= " ,parcelas     = " . $_SESSION['qtde_parcelas'];
    $sql_u .= " WHERE num_ped = '" . $_SESSION['num_ped'] . "'";

    mysqli_query($conexao, $sql_u);
    require "includes/status_acesso_db.php";

    $nome = $_SESSION['nome_completo'];
    $num_ped = $_SESSION['num_ped'];
    $valor_compra = number_format($_SESSION['total_pedido'], 2, ",", ".");
    $email_cliente = $_SESSION['email'];
    $link = "http://miniaturas.com/pedidos";

    require_once "includes/email_cartao.php";
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
                            ?><img src="img/marcador_setaDir.gif" alt="seta direita"><?php } ?><?php print $title; ?><img src="img/marcador_setaDir.gif" alt="seta direita">Pagamento com Cartão de Crédito
                    </h1>
                </div>
                <div id="num-ped" class="tela-pgto-num-ped">
                    <span>Número do pedido: </span>
                    <span class="numero"> <?php print $_SESSION['num_ped']; ?></span>
                </div>
            </div>
            <div id="caixa">
                <h4>Instruções Para o Pagamento</h4>
                <p>Obrigado por comprar em nossa loja. Seu pedido foi aceito e está aguardando aprovação da operadora do Cartão de Crédito. Após recebermos a confirmação de pagamento, nós lhe enviaremos um e-mail de notificação confirmando a entega do pedido.</p>
                <p>&nbsp;</p>

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
                            <td><span>R$ <?php print number_format($total_frete = ($_SESSION['total_peso'] * $frete), 2, ",", "."); ?></span></td>
                        </tr>
                        <tr>
                            <td colspan="3"><span>Total a pagar</span></td>
                            <td><span>R$ <?php print number_format($total_pedido_cartao = $subtotal_pedido + $total_frete, 2, ",", "."); ?></span></td>
                        </tr>                        
                    </tfoot>
                </table>   
                <br>
                <h2>Informações sobre a Forma de Pagamento</h2>
                <p>Forma de Pagamento: Cartão de Crédito</p>
                <p>Bandeira do Cartão: <?php print ucwords($_SESSION['opcao_pgto']); ?></p>
                <p>Status: Aguardando Aprovação</p>
                <p>Data do Pedido: <?php print $_SESSION['data_pedido']; ?></p>
                <p>Vencimento: <?php print $_SESSION['vencimento']; ?></p>
                <p>Nº de parcelas: <?php print $_SESSION['qtde_parcelas']; ?></p>
                <p>Valor da(s) Parcela(s): R$ <?php print number_format($total_pedido_cartao / $_SESSION['qtde_parcelas'], 2, ",", "."); ?></p>
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
$_SESSION['ultimo_num_ped'] = $_SESSION['num_ped'];
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
        $_SESSION['erro'][] = 40;
    } else {
        switch ($_SESSION['opcao_pgto']) {
            case "visa":
            case "mastercard":
            case "amex":
            case "diners":
                break;
            default:
                $_SESSION['erro'][] = 40;
                break;
        }
    }

    $_SESSION['num_cartao'] = test_input($_POST['num_cartao']);
    if (!$_SESSION['num_cartao']) {
        $_SESSION['erro'][] = 41;
    } else if (!filter_var($_SESSION['num_cartao'], FILTER_VALIDATE_INT)) {
        $_SESSION['erro'][] = 42;
    }

    $_SESSION['nome_cartao'] = test_input($_POST['nome_cartao']);
    if (!$_SESSION['nome_cartao']) {
        $_SESSION['erro'][] = 43;
    }

    $_SESSION['data_cartao_mes'] = test_input($_POST['data_cartao_mes']);
    if (!$_SESSION['data_cartao_mes']) {
        $_SESSION['erro'][] = 44;
    } else if (!filter_var($_SESSION['data_cartao_mes'], FILTER_VALIDATE_INT)) {
        $_SESSION['erro'][] = 45;
    }

    $_SESSION['data_cartao_ano'] = test_input($_POST['data_cartao_ano']);
    if (!$_SESSION['data_cartao_ano']) {
        $_SESSION['erro'][] = 46;
    } else if (!filter_var($_SESSION['data_cartao_ano'], FILTER_VALIDATE_INT)) {
        $_SESSION['erro'][] = 47;
    }

    $_SESSION['cod_seg'] = test_input($_POST['cod_seg']);
    if (!$_SESSION['cod_seg']) {
        $_SESSION['erro'][] = 48;
    } else if (!filter_var($_SESSION['cod_seg'], FILTER_VALIDATE_INT)) {
        $_SESSION['erro'][] = 49;
    }

    $_SESSION['qtde_parcelas'] = test_input($_POST['qtde_parcelas']);
    if (!$_SESSION['qtde_parcelas']) {
        $_SESSION['erro'][] = 50;
    } else if (!filter_var($_SESSION['qtde_parcelas'], FILTER_VALIDATE_INT)) {
        $_SESSION['erro'][] = 51;
    }

    if (isset($_SESSION['erro'])) {
        $_SESSION['erro_pgto'] = "Pagamento Cartão de Crédito";
        header("Location: erro.php");
        exit;
    }
}
?>