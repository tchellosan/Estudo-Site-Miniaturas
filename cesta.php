<?php
session_start();

require_once "includes/defines.php";
require_once "includes/funcoes.php";
require_once "includes/dbConexao.php";

unset($_SESSION['erro']);

if (isset($_GET['codigo_produto'])) {
    if (!$_GET['codigo_produto']) {
        $_SESSION['erro'][] = 7;
        header("Location: erro.php");
        exit;
    }

    if (!isset($_SESSION['num_ped'])) {
        $sql_i = "INSERT INTO pedidos (status)";
        $sql_i .= " VALUES ('" . EM_ANDAMENTO . "')";

        mysqli_query($conexao, $sql_i);
        require "includes/status_acesso_db.php";

        $_SESSION['id_ped'] = mysqli_insert_id($conexao);
        require "includes/status_acesso_db.php";

        $_SESSION['num_ped'] = $_SESSION['id_ped'] . "." . date("H") . substr(date("i"), 0, 1);

        $sql_u = " UPDATE pedidos SET num_ped = '" . $_SESSION['num_ped'] . "'";
        $sql_u .= " WHERE id = " . $_SESSION['id_ped'];

        mysqli_query($conexao, $sql_u);
        require "includes/status_acesso_db.php";

        $_SESSION['num_boleto'] = str_replace(".", "", $_SESSION['num_ped']);
        $_SESSION['status_pedido'] = EM_ANDAMENTO;
    }

    $num_ped = $_SESSION['num_ped'];
    $codigo_produto = mysqli_escape_string($conexao, test_input($_GET['codigo_produto']));

    $sql = " SELECT";
    $sql .= "  codigo";
    $sql .= "  FROM itens";
    $sql .= " WHERE num_ped = '$num_ped'";
    $sql .= "   AND codigo  = '$codigo_produto'";
    $sql .= " LIMIT 1";

    $rs_dupli = mysqli_query($conexao, $sql);
    require "includes/status_acesso_db.php";

    $rs_dupli_total_registros = mysqli_num_rows($rs_dupli);

    if ($rs_dupli_total_registros == 0) {
        $sql = "SELECT";
        $sql .= "  id";
        $sql .= " ,nome";
        $sql .= " ,preco";
        $sql .= " ,desconto";
        $sql .= " ,peso";
        $sql .= " ,desconto_boleto";
        $sql .= " FROM miniaturas";
        $sql .= " WHERE codigo = '$codigo_produto'";

        $rs_det_prod = mysqli_query($conexao, $sql);
        require "includes/status_acesso_db.php";

        $rs_det_prod_total_registros = mysqli_num_rows($rs_det_prod);

        if ($rs_det_prod_total_registros == 0) {
            $_SESSION['erro'][] = 37;
            header("Location: erro.php");
            exit;
        } else {
            extract(mysqli_fetch_array($rs_det_prod), EXTR_PREFIX_ALL, "rs_det_prod");
            require "includes/status_acesso_db.php";
            $preco_desconto = $rs_det_prod_preco - (($rs_det_prod_preco * $rs_det_prod_desconto ) / 100);
            $preco_boleto = $preco_desconto - (($preco_desconto * $rs_det_prod_desconto_boleto ) / 100);
        }

        $sql_i = "INSERT INTO itens (";
        $sql_i .= "  num_ped";
        $sql_i .= " ,codigo";
        $sql_i .= " ,nome";
        $sql_i .= " ,qt";
        $sql_i .= " ,preco";
        $sql_i .= " ,peso";
        $sql_i .= " ,preco_boleto";
        $sql_i .= " ,desconto";
        $sql_i .= " ,desconto_boleto) ";
        $sql_i .= " VALUES ('$num_ped'";
        $sql_i .= " ,'$codigo_produto'";
        $sql_i .= " ,'$rs_det_prod_nome'";
        $sql_i .= " ,1";
        $sql_i .= " ,$rs_det_prod_preco";
        $sql_i .= " ,$rs_det_prod_peso";
        $sql_i .= " ,$preco_boleto";
        $sql_i .= " ,$rs_det_prod_desconto";
        $sql_i .= " ,$rs_det_prod_desconto_boleto)";

        mysqli_query($conexao, $sql_i);
        require "includes/status_acesso_db.php";

        mysqli_free_result($rs_det_prod);
    }
    mysqli_free_result($rs_dupli);
}

if (isset($_GET['id_item_ped']) && isset($_GET['nova_qtde']) && isset($_GET['atualizar'])) {
    if (!$_GET['id_item_ped']) {
        $_SESSION['erro'][] = 8;
        header("Location: erro.php");
        exit;
    }
    if (!$_GET['nova_qtde']) {
        $_SESSION['erro'][] = 10;
        header("Location: erro.php");
        exit;
    } else {
        if ($_GET['nova_qtde'] < 1 || $_GET['nova_qtde'] > 10) {
            $_SESSION['erro'][] = 11;
            header("Location: erro.php");
            exit;
        }
    }

    if (isset($_SESSION['num_ped'])) {
        $id_item_ped = mysqli_escape_string($conexao, test_input($_GET['id_item_ped']));
        $nova_qtde = mysqli_escape_string($conexao, test_input($_GET['nova_qtde']));

        $sql_u = " UPDATE itens SET qt = " . $nova_qtde;
        $sql_u .= " WHERE id           = " . $id_item_ped;
        $sql_u .= "   AND num_ped      = '" . $_SESSION['num_ped'] . "'";

        mysqli_query($conexao, $sql_u);
        require "includes/status_acesso_db.php";
    } else {
        $_SESSION['erro'][] = 38;
        header("Location: erro.php");
        exit;
    }
}

if (isset($_GET['id_item_ped']) && isset($_GET['excluir'])) {
    if (!$_GET['id_item_ped']) {
        $_SESSION['erro'][] = 8;
        header("Location: erro.php");
        exit;
    }

    if (isset($_SESSION['num_ped'])) {
        $id_item_ped = mysqli_escape_string($conexao, test_input($_GET['id_item_ped']));

        $sql_d = "DELETE FROM itens ";
        $sql_d .= "WHERE id      = " . $id_item_ped;
        $sql_d .= "  AND num_ped = '" . $_SESSION['num_ped'] . "'";

        mysqli_query($conexao, $sql_d);
        require "includes/status_acesso_db.php";
    } else {
        $_SESSION['erro'][] = 38;
        header("Location: erro.php");
        exit;
    }
}

if (isset($_SESSION['num_ped'])) {
    $num_ped = $_SESSION['num_ped'];

    $sql = " SELECT ";
    $sql .= "     * ";
    $sql .= "  FROM itens ";
    $sql .= " WHERE num_ped = '$num_ped'";
    $sql .= " ORDER BY id";

    $rs_det_item = mysqli_query($conexao, $sql);
    require "includes/status_acesso_db.php";

    $rs_det_item_total_registros = mysqli_num_rows($rs_det_item);
    $_SESSION['total_itens'] = $rs_det_item_total_registros;
} else {
    $_SESSION['total_itens'] = 0;
}

if ($_SESSION['total_itens'] == 0) {
    $_SESSION['erro'][] = 35;
    header("Location: erro.php");
    exit;
}

$title = "Meu Carrinho - Miniaturas";
?>
<!DOCTYPE html>
<html lang = "pt-br">
    <head>
        <meta charset = "UTF-8">
        <title><?php print $title; ?></title>
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
            <div id="caixa">
                <div class="cabec-num-ped">
                    <div id="meu-carrinho">
                        <span>Meu carrinho de compras</span>
                    </div>
                    <div id="num-ped">
                        <span>Número do pedido: </span>
                        <span class="numero"> <?php print $_SESSION['num_ped']; ?></span>
                    </div>
                </div>

                <table id="itens-pedido">
                    <thead>
                        <tr>
                            <th colspan="2">Descrição do Produto</th>
                            <th>Quantidade</th>
                            <th>Excluir Item</th>
                            <th>Preço Unitário R$</th>
                            <th>Total R$</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $subtotal_pedido = 0;
                        for ($item = 1; $item <= $rs_det_item_total_registros; $item++) {
                            extract(mysqli_fetch_array($rs_det_item), EXTR_PREFIX_ALL, "rs_det_item");
                            require "includes/status_acesso_db.php";
                            $preco_item_desconto = $rs_det_item_preco - (($rs_det_item_preco * $rs_det_item_desconto) / 100);
                            $total_item = $preco_item_desconto * $rs_det_item_qt;
                            $subtotal_pedido += $total_item;
                            ?>
                            <tr>
                                <td><a href="detalhes.php?codigo_produto=<?php print $rs_det_item_codigo; ?>"><img src="img/<?php print $rs_det_item_codigo; ?>.jpg" alt="<?php print $rs_det_item_nome; ?>"></a></td>
                                <td><span><?php print $rs_det_item_codigo; ?> - <?php print $rs_det_item_nome; ?></span></td>
                                <td>
                                    <input type="number" name="qtde-produto" min="1" max="10" value="<?php print $rs_det_item_qt; ?>">
                                    <input  type="hidden" name="id-item" value="<?php print $rs_det_item_id; ?>">
                                </td>
                                <td><a href="?id_item_ped=<?php print $rs_det_item_id; ?>&excluir"><img src="img/btn_removerItem.gif" alt="Remover"></a></td>
                                <td><span>R$ <?php print number_format($preco_item_desconto, 2, ",", "."); ?></span></td>
                                <td><span>R$ <?php print number_format($total_item, 2, ",", "."); ?></span></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                    <tfoot>
                        <tr></tr>
                        <tr>
                            <td colspan="4"><span>*O valor total da sua compra não inclui o frete, ele será calculado no fechamento do pedido.</span></td>
                            <td><span>Subtotal</span></td>
                            <td><span>R$ <?php print number_format($subtotal_pedido, 2, ",", "."); ?></span></td>
                        </tr>
                    </tfoot>
                </table>
                <div>
                    <div id="comprar-mais"><a href="index.php"><img src="img/btn_comprarMais.gif" alt="Comprar Mais"></a></div>
                    <div id="finalizar-pedido"><a href="login.php"><img src="img/btn_fecharPedido.gif" alt="Finalizar Pedido"></a></div>
                </div>
                <?php
                mysqli_free_result($rs_det_item);
                ?>
            </div>
            <?php require_once "includes/rodape.php"; ?>	
        </div>
        <script src="js/cesta.js"></script>
        <script src="js/subcategoria.js"></script>
    </body>
</html>
<?php
mysqli_close($conexao);
?>