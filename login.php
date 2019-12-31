<?php
session_start();

require_once "includes/dbConexao.php";

unset($_SESSION['erro']);

if (isset($_GET['meu_cadastro'])) {
    $_SESSION['meu_cadastro'] = "S";
    $title = "Meu Cadastro";
} else {
    $_SESSION['meu_cadastro'] = "N";
    $title = "Etapa 1";
}

if (isset($_SESSION['logado']) && $_SESSION['logado'] == 'S') {
    $_SESSION['operacao'] = "exibir_cadastro";
    header("Location: cadastro_manut.php");
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
            <?php if ($_SESSION['meu_cadastro'] == "S") { ?>
                <div id="topo">
                    <?php require_once "includes/menu_superior.php"; ?>
                </div>
                <div id="menuSup">
                    <?php require_once "includes/menu_categorias.php"; ?>
                </div>
            <?php } else { ?>
                <div id="etapa-1">
                    <div class="logo">
                        <a href="index.php"><img src="img/logo_fs.gif" alt="Miniaturas"></a>
                    </div>
                </div>
            <?php } ?>
            <h1><?php print $title; ?><img src="img/marcador_setaDir.gif" alt="seta direita">Identificação</h1>
            <div id="">
                <div class="login-cadastro">
                    <h2>Já sou cadastrado</h2>
                    <p>Para prosseguir, por favor, identifique-se utilizando os campos abaixo e depois Clique no Botão Continuar.</p>
                    <br>
                    <form name="form_login_acesso" method="POST" action="login_acesso.php">
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
                        <input type="hidden" name="operacao" value="exibir_cadastro">
                    </form>
                </div>
                <div class="login-cadastro">
                    <h2>Quero me cadastrar</h2>
                    <p>Caso esta seja a sua primeira compra no Site Miniaturas, preencha o campo com seu e-mail e Clique no Botão Continuar..</p>
                    <br>
                    <form name="form_cadastro" method="POST" action="cadastro.php">
                        <div class="campo-entrada">
                            <label for="email2">E-mail:</label>
                            <input type="text" id="email2" name="email" maxlength="60">
                        </div>
                        <div class="campo-entrada">
                            <label for="senha2">Senha:</label>
                            <input type="text" id="senha2" name="senha" maxlength="10" disabled value="Será preenchida na próxima etapa.">
                        </div>
                        <div>
                            <div class="continuar">
                                <button type="submit"><img src="img/btn_continuar.gif" alt="Continuar"></button>
                            </div>
                        </div>
                        <input type="hidden" name="operacao" value="incluir">
                    </form>
                </div>
            </div>
            <?php require_once "includes/rodape.php"; ?>	
        </div>
        <script src="js/login.js"></script>
        <?php if ($_SESSION['meu_cadastro'] == "S") { ?>
            <script src="js/subcategoria.js"></script>
        <?php } ?>
    </body>
</html>
<?php
mysqli_close($conexao);
?>