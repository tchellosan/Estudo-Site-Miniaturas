<?php
session_start();
require_once "includes/funcoes.php";
require_once "includes/dbConexao.php";

unset($_SESSION['erro']);

if (isset($_GET['codigo_produto'])) {
    $codigo = mysqli_escape_string($conexao, test_input($_GET['codigo_produto']));

    $sql = "SELECT";
    $sql .= "  a.cat_nome";
    $sql .= " ,b.id";
    $sql .= " ,b.codigo";
    $sql .= " ,b.nome";
    $sql .= " ,b.ano";
    $sql .= " ,b.subcateg";
    $sql .= " ,b.escala";
    $sql .= " ,b.peso";
    $sql .= " ,b.comprimento";
    $sql .= " ,b.largura";
    $sql .= " ,b.altura";
    $sql .= " ,b.cor";
    $sql .= " ,b.preco";
    $sql .= " ,b.desconto";
    $sql .= " ,b.desconto_boleto";
    $sql .= " ,b.max_parcelas";
    $sql .= " ,b.estoque";
    $sql .= " ,b.min_estoque";
    $sql .= " ,b.credito";
    $sql .= "  FROM categorias a INNER JOIN miniaturas b";
    $sql .= "    ON b.id_categoria = a.id";
    $sql .= " WHERE b.codigo       = '$codigo'";

    $rs_det = mysqli_query($conexao, $sql);
    require "includes/status_acesso_db.php";

    $rs_det_total_registros = mysqli_num_rows($rs_det);

    if ($rs_det_total_registros == 0) {
        $_SESSION['erro'][] = 37;
        header("Location: erro.php");
        exit;
    } else if ($rs_det_total_registros > 1) {
        $_SESSION['erro'][0][] = 2;
        $_SESSION['erro'][0][] = $codigo;
        header("Location: erro.php");
        exit;
    }
} else {
    $_SESSION['erro'][] = 7;
    header("Location: erro.php");
    exit;
}

extract(mysqli_fetch_array($rs_det), EXTR_PREFIX_ALL, "rs_det");
require "includes/status_acesso_db.php";

$valor_desconto = $rs_det_preco - (($rs_det_preco * $rs_det_desconto) / 100);
$valor_boleto = $valor_desconto - (($valor_desconto * $rs_det_desconto_boleto) / 100);
$title = "$rs_det_nome - Miniaturas";
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
            <h1>Detalhes <img src="img/marcador_setaDir.gif" alt="seta direita"> <?php print $rs_det_cat_nome; ?> <img src="img/marcador_setaDir.gif" alt="seta direita"> <?php print $rs_det_nome; ?></h1>
            <div id="caixa">
                <div class="dados-tecnicos">
                    <div class="card-imagem">
                        <a href="#"><img src="img/<?php print $rs_det_codigo; ?>G.jpg" alt="<?php print $rs_det_nome; ?>" title="<?php print $rs_det_nome; ?>"></a><br>
                        <input type="hidden" name="codigo" value="<?php print $rs_det_codigo; ?>">
                        <a href="#"><img src="img/btn_ampliar.gif" alt="Ampliar imagem" title="<?php print $rs_det_nome; ?>"></a>
                    </div>
                    <table>
                        <caption>Dados Técnicos</caption>
                        <tr><td scope="row"><span>Código:</span><br></td><td><span><?php print $rs_det_codigo; ?></span></td></tr>
                        <tr><td scope="row"><span>Categoria:</span></td><td><span><?php print $rs_det_cat_nome; ?></span></td></tr>
                        <tr><td scope="row"><span>Tipo:</span></td><td><span><?php print $rs_det_subcateg; ?></span></td></tr>
                        <tr><td scope="row"><span>Ano:</span></td><td><span><?php print $rs_det_ano; ?></span></td></tr>
                        <tr><td scope="row"><span>Escala:</span></td><td><span><?php print $rs_det_escala; ?> Die Cast Models</span></td></tr>
                        <tr><td scope="row"><span>Peso:</span></td><td><span><?php print number_format($rs_det_peso, 3, ",", "."); ?> Kg</span></td></tr>
                        <tr><td scope="row"><span>Cor:</span></td><td><span><?php print $rs_det_cor; ?></span></td></tr>
                        <tr><td scope="row"><span>Dimensões:</span></td><td><span>(C x L x A): <?php print number_format($rs_det_comprimento, 1, ",", "."); ?> x <?php print number_format($rs_det_largura, 1, ",", "."); ?> x <?php print number_format($rs_det_altura, 1, ",", "."); ?> cm</span></td></tr>
                    </table>
                    <p><span class="credito-imagem"><strong>Crédito da imagem</strong>: <?php print $rs_det_credito; ?></span></p>
                </div>
                <div class="forma-pgto">
                    <div id="caixa-detalhes">
                        <span class="titulo-miniatura"><?php print $rs_det_nome; ?></span>
                        <?php if ($rs_det_estoque > $rs_det_min_estoque) { ?>
                            <a href="cesta.php?codigo_produto=<?php print $rs_det_codigo; ?>"><img src="img/btn_comprar.gif" alt="Comprar"></a>
                        <?php } else { ?>
                            <img src="img/btn_comprar_nd.gif" alt="Não disponível em estoque.">
                        <?php } ?>
                        <p>
                            De: <span class="preco-normal">R$ <?php print number_format($rs_det_preco, 2, ',', '.'); ?></span><br>
                            Por: <span class="destaque-preco">R$ <?php print number_format($valor_desconto, 2, ',', '.'); ?></span>
                        </p>
                        <h6>Parcelamento no Cartão de Crédito</h6>
                        <table>
                            <tbody>
                                <?php for ($parcela = 1; $parcela <= $rs_det_max_parcelas; $parcela++) { ?>
                                    <?php if ($parcela % 2 == 1) { ?>
                                        <tr>
                                            <td class="parcelas"><span><?php print $parcela ?> x de R$ <?php print number_format($valor_desconto / $parcela, 2, ",", "."); ?> sem juros</span></td>
                                            <?php if ($parcela == $rs_det_max_parcelas) { ?>
                                                <td></td>
                                            </tr>                                                                
                                        <?php } ?>
                                    <?php } else { ?>
                                    <td class="parcelas"><span><?php print $parcela ?> x de R$ <?php print number_format($valor_desconto / $parcela, 2, ",", "."); ?> sem juros</span></td>
                                    </tr>                                
                                <?php } ?>
                            <?php } ?>
                            </tbody>
                        </table>
                        <p>* Pague com Boleto Bancário e ganhe + <?php print number_format($rs_det_desconto_boleto, 0, ",", "."); ?>% de desconto: <?php print number_format($valor_boleto, 2, ",", "."); ?>.</p>
                        <p>* Este produto por ser pago com Cartão de Crédito em até <?php print $rs_det_max_parcelas; ?> parcelas.</p>
                        <h6>Formas de Pagamento</h6>
                        <img src="img/banner_formapag.gif" alt="Formas de Pagamento">
                        <h6>Prazos de Entrega</h6>
                        <p>2 dias úteis para o estado de São Paulo.</p>
                        <p>5 dias úteis para os demais estados.</p>
                        <h6>Observações</h6>
                        <p>As mercadorias adquiridas serão despachadas via Sedex (Sedex ou e-sedex), no primeiro dia útil após a comprovação do pagamento, estando a entrega condicionada à disponibilidade de estoque. Prazo médio de entrega dos Correios: 24 à 72 horas.</p>
                    </div>
                </div>
            </div>

            <?php require_once "includes/rodape.php"; ?>	
        </div>
        <script src="js/subcategoria.js"></script>
        <script src="js/ampliar.js"></script>
    </body>
</html>
<?php
mysqli_free_result($rs_det);
mysqli_close($conexao);
?>