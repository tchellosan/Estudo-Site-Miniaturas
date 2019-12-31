<?php
session_start();

require_once "includes/dbConexao.php";

unset($_SESSION['erro']);

if (!(isset($_SESSION['logado']) && $_SESSION['logado'] == 'S')) {
    header("Location: pedidos.php");
    exit;
}

$title = "Meus Pedidos";

$id_cliente = $_SESSION['id_cliente'];

$sql = " SELECT";
$sql .= "  num_ped";
$sql .= " ,data";
$sql .= " ,hora";
$sql .= " ,vencimento";
$sql .= " ,status";
$sql .= " ,valor";
$sql .= " ,desconto";
$sql .= "  FROM pedidos";
$sql .= " WHERE id_cliente = " . $id_cliente;
$sql .= " ORDER BY status, num_ped";

$rs_det_pedidos = mysqli_query($conexao, $sql);
require "includes/status_acesso_db.php";

$rs_det_pedidos_total_registros = mysqli_num_rows($rs_det_pedidos);

if ($rs_det_pedidos_total_registros == 0) {
    $_SESSION['erro'][] = 54;
    header("Location: erro.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang = "pt-br">
    <head>
        <meta charset = "UTF-8">
        <title><?php print $title; ?> - Identificação</title>
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
            <h1><?php print $title; ?></h1>
            <table id="lista-pedidos">
                <thead>
                    <tr>
                        <th></th>
                        <th>Nº Pedido</th>
                        <th>Data Pedido</th>
                        <th>Vencimento</th>
                        <th>Status</th>
                        <th>Valor</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    for ($pedido = 1; $pedido <= $rs_det_pedidos_total_registros; $pedido++) {
                        extract(mysqli_fetch_array($rs_det_pedidos), EXTR_PREFIX_ALL, "rs_det_pedidos");
                        require "includes/status_acesso_db.php";
                        ?>
                        <tr>
                            <td><span><a href="pedidos_detalhe?num_ped=<?php print $rs_det_pedidos_num_ped; ?>"><img src="img/btn_detalharPedido.gif" alt="Ver pedido"></a></span></td>
                            <td><span><?php print $rs_det_pedidos_num_ped; ?></span></td>
                            <td><span><?php print join("/", array_reverse(explode("-", $rs_det_pedidos_data))) . " " . $rs_det_pedidos_hora; ?></span></td>
                            <td><span><?php print join("/", array_reverse(explode("-", $rs_det_pedidos_vencimento))); ?></span></td>
                            <td><span>
                                    <?php
                                    switch ($rs_det_pedidos_status) {
                                        case "1":
                                            print "Em andamento";
                                            break;

                                        case "2":
                                            print "Aguardando Aprovação do Cartão de Crédito";
                                            break;

                                        case "3":
                                            print "Aguardando Pagamento do Boleto Bancário";
                                            break;

                                        case "4":
                                            print "Pagamento Confirmado";
                                            break;

                                        case "9":
                                            print "Cancelado";
                                            break;

                                        default:
                                            print "Sem descrição";
                                            break;
                                    }
                                    ?>
                                </span></td>
                            <td><span>R$ <?php print number_format($rs_det_pedidos_valor - $rs_det_pedidos_desconto, 2, ",", "."); ?></span></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
            <p class="btn-tela-lista-pedidos">
                <a href="index.php?sair"><img src="img/btn_sair.gif" alt="Sair"></a>
            </p>
            <?php require_once "includes/rodape.php"; ?>	
        </div>
        <script src="js/subcategoria.js"></script>
    </body>
</html>
<?php
mysqli_free_result($rs_det_pedidos);
mysqli_close($conexao);
?>