<?php
session_start();

require_once "includes/defines.php";
require_once "includes/funcoes.php";
require_once "includes/dbConexao.php";

unset($_SESSION['erro']);

if (!(isset($_SESSION['logado']) && $_SESSION['logado'] == 'S')) {
    header("Location: pedidos.php");
    exit;
}

$title = "Detalhe do Pedido";

// NUMERO PEDIDO
$num_ped = $_GET['num_ped'];

// DETALHES PEDIDO
$sql = " SELECT";
$sql .= "  data";
$sql .= " ,hora";
$sql .= " ,vencimento";
$sql .= " ,status";
$sql .= " ,valor";
$sql .= " ,formpag";
$sql .= " ,cartao";
$sql .= " ,parcelas";
$sql .= "  FROM pedidos";
$sql .= " WHERE num_ped = '" . $num_ped . "'";
$sql .= " ORDER BY num_ped";

$rs_det_pedidos = mysqli_query($conexao, $sql);
require "includes/status_acesso_db.php";

$rs_det_pedidos_total_registros = mysqli_num_rows($rs_det_pedidos);

if ($rs_det_pedidos_total_registros == 0) {
    $_SESSION['erro'][] = 54;
    header("Location: erro.php");
    exit;
}

extract(mysqli_fetch_array($rs_det_pedidos), EXTR_PREFIX_ALL, "rs_det_pedidos");
require "includes/status_acesso_db.php";

// DETALHES FRETE
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
        <title><?php print ucwords($title); ?></title>
        <link rel="stylesheet" href="css/estilo_site.css">
    </head>
    <body >
        <div id="corpo">
            <div id="topo">
                <?php require_once "includes/menu_superior.php"; ?>
            </div>
            <div id="menuSup">
                <?php require_once "includes/menu_categorias.php"; ?>
            </div>
            <div class="cabec-det-ped">
                <div id="pagamento" class="tela-det-ped-num">
                    <h1><?php print $title; ?> Número: <?php print $num_ped; ?></h1>
                </div>
                <div id="num-ped" class="tela-det-ped-status">
                    <span>Status:
                        <?php
                        switch ($rs_det_pedidos_status) {
                            case "1":
                                $status_pedido = "Em andamento";
                                break;

                            case "2":
                                $status_pedido = "Aguardando Aprovação do Cartão de Crédito";
                                break;

                            case "3":
                                $status_pedido = "Aguardando Pagamento do Boleto Bancário";
                                break;

                            case "4":
                                $status_pedido = "Pagamento Confirmado";
                                break;

                            case "9":
                                $status_pedido = "Cancelado";
                                break;
                            default:
                                $status_pedido = "Sem descrição";
                                break;
                        }
                        print $status_pedido;
                        ?>
                    </span>
                </div>
            </div>
            <div id="caixa" class="pedidos-detalhe">
                <?php
                $data_atual = mktime(0, 0, 0, date("m"), date("d"), date("Y"));
                $data_vencimento = mktime(0, 0, 0, (int) substr($rs_det_pedidos_vencimento, 5, 2), (int) substr($rs_det_pedidos_vencimento, 8, 2), (int) substr($rs_det_pedidos_vencimento, 0, 4));

                $qtde_dias_a_vencer = ($data_vencimento - $data_atual) / 86400;

                if (($qtde_dias_a_vencer) > 0 && $rs_det_pedidos_formpag == 'B' && $rs_det_pedidos_status == '3') {
                    ?>
                    <p><img src="img/marcador_atencao.gif" alt="Atenção">
                        Olá <strong><?php print $_SESSION['nome_completo'] ?></strong>, Esse pedido se encontra no prazo de validade. Você ainda tem <strong><?php print $qtde_dias_a_vencer; ?> dia(s) </strong>para efetuar seu pagamento. Após este período seu pedido será cancelado. Se você ainda não imprimiu o referido boleto, poderá fazê-lo agora clicando no botão "Imprimir Boleto".
                    </p>
                    <p>&nbsp;</p>
                    <div class="emitir-boleto">
                        <a href="emitir_boleto.php?num_ped=<?php print $num_ped; ?>"><img src="img/btn_imprimirBoleto.gif"></a>
                    </div>
                <?php } ?>

                <h4>Informações gerais sobre sua compra</h4>
                <h2>Resumo do Pedido</h2>
                <table id="itens-pedido">
                    <thead>
                        <tr>
                            <th colspan="2">Descrição do Produto</th>
                            <th>Quantidade</th>
                            <th>Preço Unitário R$</th>
                            <th>Total R$</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $subtotal_pedido = 0;
                        $subtotal_boleto = 0;
                        $total_peso = 0;
                        $desconto = 0;
                        if (isset($num_ped)) {
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
                            $total_peso += ($rs_det_item_qt * $rs_det_item_peso);
                            ?>
                            <tr>
                                <td><img src="img/<?php print $rs_det_item_codigo; ?>.jpg" alt="<?php print $rs_det_item_nome; ?>"></td>
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
                            <td colspan="4"><span>Subtotal</span></td>
                            <td><span>R$ <?php print number_format($subtotal_pedido, 2, ",", "."); ?></span></td>
                        </tr>

                        <?php if ($rs_det_pedidos_formpag == 'B') { ?>

                            <tr id="valor-desconto">
                                <td colspan="4"><span>Desconto para pagamento com Boleto Bancário</span></td>
                                <td><span>R$ -<?php print number_format($desconto = ($subtotal_pedido - $subtotal_boleto), 2, ",", "."); ?></span></td>
                            </tr>                        
                        <?php } ?>

                        <tr>
                            <td colspan="4"><span>Frete</span></td>
                            <td><span>R$ <?php print number_format($total_frete = ($total_peso * $frete), 2, ",", "."); ?></span></td>
                        </tr>
                        <tr>
                            <td colspan="4"><span>Total a pagar</span></td>
                            <td><span>R$ <?php print number_format($total_pedido_cartao = ($subtotal_pedido - $desconto) + $total_frete, 2, ",", "."); ?></span></td>
                        </tr>                        
                    </tfoot>
                </table>   
                <br>
                <?php if ($rs_det_pedidos_formpag == 'B') { ?>
                    <p>Forma de Pagamento: Boleto Bancário</p>
                    <p>Status: <?php print $status_pedido; ?></p>
                    <p>Data do Pedido: <?php print join("/", array_reverse(explode("-", $rs_det_pedidos_data))); ?></p>
                    <p>Vencimento: <?php print join("/", array_reverse(explode("-", $rs_det_pedidos_vencimento))); ?></p>
                    <p>Peso: <?php print number_format($total_peso, 3, ",", "."); ?> Kg</p>
                <?php } ?>

                <?php if ($rs_det_pedidos_formpag == 'C') { ?>
                    <p>Forma de Pagamento: Cartão de Crédito</p>
                    <p>Bandeira do Cartão: <?php print ucwords($rs_det_pedidos_cartao); ?></p>
                    <p>Status: <?php print $status_pedido; ?></p>
                    <p>Data do Pedido: <?php print join("/", array_reverse(explode("-", $rs_det_pedidos_data))); ?></p>
                    <p>Vencimento: <?php print join("/", array_reverse(explode("-", $rs_det_pedidos_vencimento))); ?></p>
                    <p>Nº de parcelas: <?php print $rs_det_pedidos_parcelas; ?></p>
                    <p>Valor da(s) Parcela(s): R$ <?php print number_format($rs_det_pedidos_valor / $rs_det_pedidos_parcelas, 2, ",", "."); ?></p>
                    <p>Peso: <?php print number_format($total_peso, 3, ",", "."); ?> Kg</p>
                <?php } ?>
                <br>
                <h4>cadastro</h4>
                <h2>Dados Pessoais</h2>
                <p>Nome do Comprador: <strong><?php print $_SESSION['nome_completo']; ?></strong></p>
                <p>E-mail: <strong><?php print $_SESSION['email']; ?></strong></p>
                <br>
                <h2>Endereço de Entrega</h2>
                <p><?php print $_SESSION['logradouro'] . ", " . $_SESSION['numero_logra'] . " - " . $_SESSION['complemento']; ?></p>
                <p>CEP: <?php print substr($_SESSION['cep'], 0, 5) . "-" . substr($_SESSION['cep'], 5, 3) . " " . $_SESSION['cidade'] . " - " . $_SESSION['uf']; ?></p>

            </div>
            <p class="btn-tela-detalhe"><a href="" id="btn_voltar"><img src="img/btn_voltar.gif" alt="Voltar"></a></p>

            <?php require_once "includes/rodape.php"; ?>	
        </div>
        <script src="js/pedidos_detalhe.js"></script>
        <script src="js/subcategoria.js"></script>
    </body>
</html>
<?php
mysqli_free_result($rs_det_item);
mysqli_free_result($rs_det_pedidos);
mysqli_close($conexao);
?>