<?php
session_start();

require_once "includes/dbConexao.php";

unset($_SESSION['erro']);

$title = "Recuperar Senha";

$sql = "SELECT";
$sql .= "  nome";
$sql .= "  ,senha";
$sql .= "  FROM cadcli";
$sql .= " WHERE email = '" . $_SESSION['email'] . "'";

$rs_senha = mysqli_query($conexao, $sql);
require "includes/status_acesso_db.php";

$rs_senha_total_registros = mysqli_num_rows($rs_senha);

if ($rs_senha_total_registros == 0) {
    $_SESSION['erro'][] = 32;
    header("Location: erro.php");
    exit;
}

extract(mysqli_fetch_array($rs_senha), EXTR_PREFIX_ALL, "rs_senha");
require "includes/status_acesso_db.php";

$email_cliente = $_SESSION['email'];
$senha = $rs_senha_senha;
$nome = $rs_senha_nome;
require_once "includes/email_senha.php";
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
            <h1><?php print $title; ?></h1>
            <div id="caixa" class="recuperar-senha">
                <?php if (isset($tipo_mensagem)) { ?>
                    <p>
                        Olá <strong><?php print $nome; ?></strong>!<br><br>
                        <span>Agradecemos a sua preferência pelo <em>Site Miniaturas</em>. <br><br>A sua senha foi enviado para a caixa postal: <strong><?php print $_SESSION['email']; ?></strong></span>
                    </p>                    
                <?php } else { ?>
                    <p>
                        Olá <strong><?php print $nome; ?></strong>!<br><br>
                        <span>Agradecemos a sua preferência pelo <em>Site Miniaturas</em>. <br><br>Ocorreu uma falha ao tentarmos enviar o e-mail. Por favor, tente novamente.</span>
                    </p>                    
                <?php } ?>
            </div>
            <?php require_once "includes/rodape.php"; ?>	
        </div>
        <script src="js/subcategoria.js"></script>
    </body>
</html>
<?php
mysqli_close($conexao);
?>