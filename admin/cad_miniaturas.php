<?php
require_once "../includes/defines.php";
require_once "../includes/funcoes.php";
require_once "../includes/dbConexao.php";
session_start();
$acao = $_GET['acao'];

$id = "";
if (isset($_GET['id'])) {
    $id = $_GET['id'];
}

$titulo_pagina = $_GET['titulo'];

if ($acao == "ver") {
    $editar = "readonly='true'";
    $editar_combo = "disabled='disabled'";
    $estilo_caixa = "caixa_texto_des";
} else {
    $editar = "";
    $editar_combo = "";
    $estilo_caixa = "caixa_texto";
}

$sql = "SELECT * FROM miniaturas ";
$sql = $sql . " WHERE id = '" . $id . "' ";
$rs = mysqli_query($conexao, $sql);
$reg = mysqli_fetch_array($rs);
$id = $reg['id'];
$codigo = $reg['codigo'];
$destaque = $reg['destaque'];
$nome = $reg['nome'];
$ano = $reg['ano'];
$id_categoria = $reg['id_categoria'];
$subcateg = $reg['subcateg'];
$destaque = $reg['destaque'];
$escala = $reg['escala'];
$peso = $reg['peso'];
$comprimento = $reg['comprimento'];
$largura = $reg['largura'];
$altura = $reg['altura'];
$cor = $reg['cor'];
$preco = $reg['preco'];
$desconto = $reg['desconto'];
$desconto_boleto = $reg['desconto_boleto'];
$max_parcelas = $reg['max_parcelas'];
$estoque = $reg['estoque'];
$min_estoque = $reg['min_estoque'];
$credito = $reg['credito'];
$data_cad = $reg['data_cad'];
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Faça um Site - PHP 5 com banco de dados MySQL</title>
    <link href="estilo_adm.css" rel="stylesheet" type="text/css" />
    <script language="javascript">
        function valida_form() {
            if (document.form_cad.codigo.value == "") {
                alert("Por favor, preencha o campo [Código].");
                form_cad.codigo.focus();
                return false;
            }

            if (document.form_cad.nome.value == "") {
                alert("Por favor, preencha o campo [Descrição].");
                form_cad.nome.focus();
                return false;
            }

            if (document.form_cad.ano.value == "") {
                alert("Por favor, preencha o campo [Ano].");
                form_cad.ano.focus();
                return false;
            }

            if (document.form_cad.ano.value == "") {
                alert("Por favor, preencha o campo [Ano].");
                form_cad.ano.focus();
                return false;
            }

            if (document.form_cad.id_categoria.value == "0") {
                alert("Por favor, selecione uma categoria.");
                form_cad.id_categoria.focus();
                return false;
            }

            if (document.form_cad.subcateg.value == "") {
                alert("Por favor, preencha o campo [Subcategoria].");
                form_cad.subcateg.focus();
                return false;
            }

            if (document.form_cad.destaque.value == "") {
                alert("Por favor, preencha o campo [Destaque Home Page].");
                form_cad.destaque.focus();
                return false;
            }

            if (document.form_cad.escala.value == "") {
                alert("Por favor, preencha o campo [Escala].");
                form_cad.escala.focus();
                return false;
            }

            if (document.form_cad.peso.value == "") {
                alert("Por favor, preencha o campo [Peso].");
                form_cad.peso.focus();
                return false;
            }

            if (document.form_cad.comprimento.value == "") {
                alert("Por favor, preencha o campo [Comprimento].");
                form_cad.comprimento.focus();
                return false;
            }

            if (document.form_cad.largura.value == "") {
                alert("Por favor, preencha o campo [Largura].");
                form_cad.largura.focus();
                return false;
            }

            if (document.form_cad.altura.value == "") {
                alert("Por favor, preencha o campo [Altura].");
                form_cad.altura.focus();
                return false;
            }

            if (document.form_cad.cor.value == "") {
                alert("Por favor, preencha o campo [Cor predominante].");
                form_cad.cor.focus();
                return false;
            }

            if (document.form_cad.preco.value == "") {
                alert("Por favor, preencha o campo [Preço].");
                form_cad.preco.focus();
                return false;
            }

            if (document.form_cad.desconto.value == "") {
                alert("Por favor, preencha o campo [Desconto].");
                form_cad.desconto.focus();
                return false;
            }

            if (document.form_cad.desconto_boleto.value == "") {
                alert("Por favor, preencha o campo [Desconto para boleto].");
                form_cad.desconto_boleto.focus();
                return false;
            }

            if (document.form_cad.max_parcelas.value == "") {
                alert("Por favor, preencha o campo [Parcelamento máximo].");
                form_cad.max_parcelas.focus();
                return false;
            }

            if (document.form_cad.estoque.value == "") {
                alert("Por favor, preencha o campo [Em estoque].");
                form_cad.estoque.focus();
                return false;
            }

            if (document.form_cad.min_estoque.value == "") {
                alert("Por favor, preencha o campo [Estoque mínimo].");
                form_cad.min_estoque.focus();
                return false;
            }

            if (document.form_cad.credito.value == "") {
                alert("Por favor, preencha o campo [Crédito da imagem].");
                form_cad.credito.focus();
                return false;
            }
            return true;
        }
    </script>

</head>
<body>
    <form name="form_cad" method="post" action="cad_miniaturas1.php" onsubmit="return valida_form(this);">	
        <div id="corpo">

            <div id="topo">
                <h1>Administração do Site</h1>
            </div>

            <div id="caixa_menu">
                <?php include "inc_menu.php" ?>
            </div>

            <div id="caixa_conteudo">
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td width="62%"><h1 class="c_cinza">Manutenção Cadastral <img src="../img/marcador_setaDir.gif" align="absmiddle" /> Miniaturas <img src="../img/marcador_setaDir.gif" align="absmiddle" /> <span class="c_preto"><?php print $titulo_pagina; ?></span> </h1>
                        </td>
                        <td width="38%"><div align="right">
                                <?php if ($acao == "ins" or $acao == "alt") { ?>
                                    <a href="cad_miniaturas_grid.php?id=<?php print $id; ?>"><img src="../img/btn_fechar_ss.gif" alt="Fechar" border="0" /></a>
                                    <input type="image" name="imageField" src="../img/btn_salvar.gif" />
                                    <input type="hidden" name="acao" value="<?php print $acao; ?>" />
                                    <input type="hidden" name="id" value="<?php print $id; ?>" />	
                                <?php } ?>	
                                <?php if ($acao == "exc") { ?>
                                    <input type="image" name="imageField" src="../img/btn_excluir.gif" />
                                    <input type="hidden" name="acao" value="<?php print $acao; ?>" />
                                    <input type="hidden" name="id" value="<?php print $id; ?>" />	
                                    <a href="cad_miniaturas_grid.php?id=<?php print $id; ?>"><img src="../img/btn_nao_excluir.gif" alt="Fechar" border="0" /></a>	
                                <?php } ?>	
                                <?php if ($acao == "ver") { ?>
                                    <a href="cad_miniaturas_grid.php?id=<?php print $id; ?>"><img src="../img/btn_fechar.gif" alt="Fechar" border="0" /></a>
                                <?php } ?>
                            </div></td>
                    </tr>
                </table>

                <div id="caixa_cad">
                    <?php
                    $imagem = "";
                    if ($acao <> "ins") {
                        $imagem_existe = "../img/" . $codigo . ".jpg";
                        if (file_exists($imagem_existe)) {
                            $imagem = "<p><label>Imagem:</label><img src='../img/" . $codigo . ".jpg' border='0' /></p>";
                        } else {
                            $imagem = "<p><label>Imagem:</label><img src='../img/fundo_imagem_naodisp.jpg' border='0' /></p>";
                        }
                    }
                    ?>
                    <?php print $imagem; ?>
                    <p><label>Código:</label><input name="codigo" type="text" class="<?php print $estilo_caixa; ?>" size="5" maxlength="5" value="<?php print $codigo; ?>" <?php print $editar; ?> /></p>
                    <p><label>Descrição:</label><input name="nome" type="text" class="<?php print $estilo_caixa; ?>" size="40" maxlength="60" value="<?php print $nome; ?>" <?php print $editar; ?> /></p>
                    <p><label>Ano de fabricação:</label><input name="ano" type="text" class="<?php print $estilo_caixa; ?>" size="6" maxlength="4" value="<?php print $ano; ?>" <?php print $editar; ?> /></p>
                    <p><label>ID categoria:</label>

                        <select name="id_categoria" <?php print $editar_combo; ?> class="caixa_combo">
                            <?php
                            $itens_cat = "<option value='0'>-- Selecione uma categoria</option><br /> ";
                            $sql_cat = "SELECT * FROM categorias ";
                            $rs_cat = mysqli_query($conexao, $sql_cat);
                            while ($reg_cat = mysqli_fetch_array($rs_cat)) {
                                if ($id_categoria == $reg_cat['id']) {
                                    $itens_cat = $itens_cat . "<option value='" . $reg_cat['id'] . "' selected='selected'>" . $reg_cat['cat_nome'] . "</option><br /> ";
                                } else {
                                    $itens_cat = $itens_cat . "<option value='" . $reg_cat['id'] . "'>" . $reg_cat['cat_nome'] . "</option><br /> ";
                                }
                            }
                            print $itens_cat;
                            ?>
                        </select> *</p>

                    <p><label>Subcategoria:</label><input name="subcateg" type="text" class="<?php print $estilo_caixa; ?>"  size="30" maxlength="30" value="<?php print $subcateg; ?>" <?php print $editar; ?> /></p>
                    <p><label>Destaque Home Page:</label><input name="destaque" type="text" class="<?php print $estilo_caixa; ?>"  size="2" maxlength="1" value="<?php print $destaque; ?>" <?php print $editar; ?> /></p>
                    <p><label>Esclala:</label><input name="escala" type="text" class="<?php print $estilo_caixa; ?>" size="10" maxlength="10" value="<?php print $escala; ?>" <?php print $editar; ?> /></p>
                    <p><label>Peso:</label><input name="peso" type="text" class="<?php print $estilo_caixa; ?>" size="10" maxlength="10" value="<?php print $peso; ?>" <?php print $editar; ?> /></p>
                    <p><label>Comprimento:</label><input name="comprimento" type="text" class="<?php print $estilo_caixa; ?>" size="10" maxlength="10" value="<?php print $comprimento; ?>" <?php print $editar; ?> /></p>
                    <p><label>Largura:</label><input name="largura" type="text" class="<?php print $estilo_caixa; ?>" size="10" maxlength="10" value="<?php print $largura; ?>" <?php print $editar; ?> /></p>
                    <p><label>Altura:</label><input name="altura" type="text" class="<?php print $estilo_caixa; ?>" size="10" maxlength="10" value="<?php print $altura; ?>" <?php print $editar; ?> /></p>
                    <p><label>Cor predominante:</label><input name="cor" type="text" class="<?php print $estilo_caixa; ?>" size="20" maxlength="20" value="<?php print $cor; ?>" <?php print $editar; ?> /></p>
                    <p><label>Preço:</label><input name="preco" type="text" class="<?php print $estilo_caixa; ?>" size="10" maxlength="20" value="<?php print $preco; ?>" <?php print $editar; ?> /></p>
                    <p><label>Desconto:</label><input name="desconto" type="text" class="<?php print $estilo_caixa; ?>" size="10" maxlength="2" value="<?php print $desconto; ?>" <?php print $editar; ?> /> (em porcentagem)</p>
                    <p><label>Desconto para boleto:</label><input name="desconto_boleto" type="text" class="<?php print $estilo_caixa; ?>" size="10" maxlength="2" value="<?php print $desconto_boleto; ?>" <?php print $editar; ?> /> (em porcentagem)</p>
                    <p><label>Parcelamento máximo:</label><input name="max_parcelas" type="text" class="<?php print $estilo_caixa; ?>" size="10" maxlength="2" value="<?php print $max_parcelas; ?>" <?php print $editar; ?> /> </p>
                    <p><label>Em estoque:</label><input name="estoque" type="text" class="<?php print $estilo_caixa; ?>" size="10" maxlength="10" value="<?php print $estoque; ?>" <?php print $editar; ?> /></p>
                    <p><label>Estoque mínimo:</label><input name="min_estoque" type="text" class="<?php print $estilo_caixa; ?>" size="10" maxlength="2" value="<?php print $min_estoque; ?>" <?php print $editar; ?> /></p>
                    <p><label>Crédito da imagem:</label><input name="credito" type="text" class="<?php print $estilo_caixa; ?>" size="50" maxlength="200" value="<?php print $credito; ?>" <?php print $editar; ?> /></p>
                </div>
            </div>

            <?php include "inc_rodape.php" ?>
        </div>
    </form>
</body>
</html>
<?php
mysqli_free_result($rs);
mysqli_close($conexao);
?>