<?php
session_start();

require_once "includes/dbConexao.php";

unset($_SESSION['erro']);

$title = "Meus Pedidos";

if (isset($_SESSION['logado']) && $_SESSION['logado'] == 'S') {
    $_SESSION['operacao'] = "exibir_cadastro";
    header("Location: pedidos_lista.php");
    exit;
}
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
                <p>Para prosseguir, por favor, identifique-se utilizando os campos abaixo e depois Clique no Botão Continuar.</p>
                <br>
                <form name="form_login_pedidos" method="POST" action="login_acesso.php">
                    <div class="campo-entrada">
                        <label for="email1">E-mail:</label>
                        <input type="text" id="email1" name="email" maxlength="60">
                    </div>
                    <div class="campo-entrada">
                        <label for="senha1">Senha:</label>
                        <input type="password" id="senha1" name="senha" maxlength="10">
                    </div>
                    <div>
                        <div class="continuar">
                            <button type="submit"><img src="img/btn_continuar.gif" alt="Continuar"></button>
                        </div>
                        <div class="esqueci-senha"><a class="link-detalhes" href="senha.php">Esqueci minha senha</a></div>
                    </div>
                    <input type="hidden" name="operacao" value="exibir_pedidos">
                </form>
            </div>
            <?php require_once "includes/rodape.php"; ?>	
        </div>
        <script src="js/pedidos.js"></script>
        <script src="js/subcategoria.js"></script>
    </body>
</html>
<?php
mysqli_close($conexao);
?>