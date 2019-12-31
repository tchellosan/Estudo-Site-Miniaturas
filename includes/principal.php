<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <title><?php print $title; ?></title>
        <link rel="stylesheet" href="css/estilo_site.css">
    </head>
    <body >
        <div id="corpo">
            <div id="topo">
                <?php require_once "menu_superior.php"; ?>
            </div>
            <div id="menuSup">
                <?php require_once "menu_categorias.php"; ?>
            </div>

            <?php if (isset($deco_banner)) { ?>
                <div id="deco-banner-<?php print $deco_banner; ?>">
                    <img src="img/deco_<?php print $deco_banner; ?>.jpg" alt="Banner miniaturas">
                </div>                
            <?php } ?>

            <div class="info-filtro">
                <div class="total-itens">
                    <h1>
                        <?php if (isset($cat_nome)) { ?>
                            <?php print $cat_nome; ?><span class="c_cinza"> [Total de itens nesta categoria: <?php print $rs_cursor1_total_registros; ?>]</span>     
                        <?php } else if (isset($subcateg)) { ?>
                            <?php print $subcateg; ?><span class="c_cinza"> [Total de itens nesta subcategoria: <?php print $rs_cursor1_total_registros; ?>]</span>     
                        <?php } else { ?>
                            Destaques<span class="c_cinza"> [Total de itens em destaque: <?php print $rs_cursor1_total_registros; ?>]</span> 
                        <?php } ?>
                    </h1>
                </div>
                <div class="ordenar-por">
                    <h1>Ordenar por:&nbsp;&nbsp;
                        <?php if ($parm_ordernar == "2") { ?>
                            <span class="radio-sel">Menor Preço</span><a href="?ordenar=1<?php if (isset($id_categoria)) print "&id-categoria=" . $id_categoria; ?><?php if (isset($cat_nome)) print "&cat-nome=" . $cat_nome; ?><?php if (isset($subcateg)) print "&subcateg=" . $subcateg; ?>" class="link-radio">Maior Preço</a>
                        <?php } else { ?>
                            <a href="?ordenar=2<?php if (isset($id_categoria)) print "&id-categoria=" . $id_categoria; ?><?php if (isset($cat_nome)) print "&cat-nome=" . $cat_nome; ?><?php if (isset($subcateg)) print "&subcateg=" . $subcateg; ?>" class="link-radio">Menor Preço</a><span class="radio-sel">Maior Preço</span>                        
                        <?php } ?>
                    </h1>
                </div>
            </div>
            <div id="caixa">
                <?php
                for ($contador = 0; $contador < $rs_cursor1_total_registros; $contador++) {
                    extract(mysqli_fetch_array($rs_cursor1), EXTR_PREFIX_ALL, "rs_cursor1");
                    require "status_acesso_db.php";

                    $valor_desconto = $rs_cursor1_preco - (($rs_cursor1_preco * $rs_cursor1_desconto) / 100);
                    ?>
                    <div class="card-miniaturas">
                        <div class="col-40p">
                            <div class="card-imagem">
                                <a href="#"><img src="img/<?php print $rs_cursor1_codigo; ?>.jpg" alt="<?php print $rs_cursor1_nome; ?>" title="<?php print $rs_cursor1_nome; ?>"></a><br>
                                <input type="hidden" name="codigo" value="<?php print $rs_cursor1_codigo; ?>">
                                <img src="img/btn_ampliar1.gif" alt="Ampliar imagem">
                            </div>
                        </div>
                        <div class="col-60p">
                            <div class="card-info">
                                <span class="titulo-miniatura"><?php print $rs_cursor1_nome; ?></span><br>
                                De: <span class="preco-normal">R$ <?php print number_format($rs_cursor1_preco, 2, ',', '.'); ?></span><br>
                                Por: <span class="destaque-preco">R$ <?php print number_format($valor_desconto, 2, ',', '.'); ?></span> no cartão<br>
                                <span class="credito-imagem">Crédito da imagem: <?php print $rs_cursor1_credito; ?></span><br>

                                <a href="detalhes.php?codigo_produto=<?php print $rs_cursor1_codigo; ?>" class="link-detalhes">Mais detalhes</a>
                                <?php if ($rs_cursor1_estoque < $rs_cursor1_min_estoque) { ?>
                                    <img src="img/btn_detalhes_nd.gif" alt="Estoque">
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                    <?php
                }
                ?>
            </div>
            <?php require_once "includes/rodape.php"; ?>	
        </div>
        <script src="js/ampliar.js"></script>
        <script src="js/subcategoria.js"></script>
    </body>
</html>