<?php
session_start();

require_once "includes/dbConexao.php";

unset($_SESSION['erro']);

$title = "Recuperar Senha";
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
            <div class="login-pedidos">
                <p>Digite seu e-mail no campo abaixo e depois clique no botão "Continuar". Sua senha será enviada para o e-mail informado.</p>
                <br>
                <form name="form_login_senha" method="POST" action="login_acesso.php">
                    <div class="campo-entrada">
                        <label for="email1">E-mail:</label>
                        <input type="text" id="email1" name="email" maxlength="60">
                    </div>
                    <div>
                        <div class="continuar">
                            <button type="submit"><img src="img/btn_continuar.gif" alt="Continuar"></button>
                        </div>
                    </div>
                    <input type="hidden" name="operacao" value="recuperar_senha">
                </form>
            </div>
            <?php require_once "includes/rodape.php"; ?>	
        </div>
        <script src="js/senha.js"></script>
        <script src="js/subcategoria.js"></script>
    </body>
</html>
<?php
mysqli_close($conexao);
?>