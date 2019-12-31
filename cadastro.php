<?php
session_start();
require_once "includes/funcoes.php";
require_once "includes/dbConexao.php";

unset($_SESSION['erro']);

if ($_SESSION['meu_cadastro'] == "N") {
    $etapa = "Etapa 1";
}

$title = "Meu Cadastro";

if (isset($_GET['operacao']) && $_SERVER['REQUEST_METHOD'] == 'GET') {
    if (!$_GET['operacao']) {
        $_SESSION['erro'][] = 13;
        header("Location: erro.php");
        exit;
    }
    $_SESSION['operacao'] = $_GET['operacao'];
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!isset($_POST['operacao']) || !$_POST['operacao']) {
        $_SESSION['erro'][] = 13;
        header("Location: erro.php");
        exit;
    }
    $_SESSION['operacao'] = $_POST['operacao'];

    $_SESSION['nome_completo'] = "";
    $_SESSION['cpf'] = "";
    $_SESSION['rg'] = "";
    $_SESSION['sexo'] = "";

    $_SESSION['email'] = filter_var(test_input($_POST['email']), FILTER_VALIDATE_EMAIL);

    if (!isset($_SESSION['email']) || !$_SESSION['email']) {
        $_SESSION['erro'][] = 14;
        header("Location: erro.php");
        exit;
    } else {
        $sql = "SELECT";
        $sql .= "  email";
        $sql .= "  FROM cadcli";
        $sql .= " WHERE email = '" . $_SESSION['email'] . "'";

        $rs_email = mysqli_query($conexao, $sql);
        require "includes/status_acesso_db.php";

        $rs_email_total_registros = mysqli_num_rows($rs_email);

        mysqli_free_result($rs_email);

        if ($rs_email_total_registros > 0) {
            $_SESSION['erro'][] = 34;
            header("Location: erro.php");
            exit;
        }
    }

    $_SESSION['email_conf'] = "";
    $_SESSION['senha'] = "";
    $_SESSION['senha_conf'] = "";
    $_SESSION['logradouro'] = "";
    $_SESSION['numero_logra'] = "";
    $_SESSION['complemento'] = "";
    $_SESSION['cep'] = "";
    $_SESSION['bairro'] = "";
    $_SESSION['cidade'] = "";
    $_SESSION['uf'] = "";
}
?>

<!DOCTYPE html>
<html lang = "pt-br">
    <head>
        <meta charset = "UTF-8">
        <title><?php
            if ($_SESSION['meu_cadastro'] == "N") {
                print $etapa . " - ";
            }
            ?><?php print ucwords($title); ?> - <?php print ucwords($_SESSION['operacao']); ?></title>
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
            <h1><?php
                if ($_SESSION['meu_cadastro'] == "N") {
                    print $etapa;
                    ?><img src="img/marcador_setaDir.gif" alt="seta direita"><?php } ?><?php print $title; ?><img src="img/marcador_setaDir.gif" alt="seta direita"><?php print ucwords($_SESSION['operacao']); ?>
            </h1>
            <div id="">

                <form action="cadastro_manut.php" name="cadastro_manut" method="POST">
                    <div id="caixa-cadastro">
                        <div id="dados-pessoais">
                            <h6>Dados Pessoais</h6>
                            <div class="campo-entrada">
                                <label for="nome-completo">Nome completo:</label>
                                <input type="text" id="nome-completo" name="nome_completo" maxlength="60" value="<?php print $_SESSION['nome_completo']; ?>">
                            </div>
                            <div class="campo-entrada">
                                <label for="cpf">(1) CPF:</label>
                                <input type="text" id="cpf" name="cpf" maxlength="11" value="<?php print $_SESSION['cpf']; ?>">
                            </div>
                            <div class="campo-entrada">
                                <label for="rg">RG:</label>
                                <input type="text" id="rg" name="rg" maxlength="14" value="<?php print $_SESSION['rg']; ?>">
                            </div>
                            <div class="campo-entrada">
                                <label for="sexo">Sexo:</label>
                                <select name="sexo" id="sexo">
                                    <option value=''>Selecione</option>
                                    <?php
                                    switch ($_SESSION['sexo']) {
                                        case "M":
                                            $option .= "<option value='M' selected>Masculino</option>";
                                            $option .= "<option value='F'>Feminino</option>";
                                            break;
                                        case "F":
                                            $option .= "<option value='M'>Masculino</option>";
                                            $option .= "<option value='F' selected>Feminino</option>";
                                            break;
                                        default:
                                            $option .= "<option value='M'>Masculino</option>";
                                            $option .= "<option value='F'>Feminino</option>";
                                            break;
                                    }
                                    print $option;
                                    ?>
                                </select>
                            </div>
                            <div class="campo-entrada">                                
                                <label for="email">E-mail:</label>
                                <input type="text" id="email" name="email" value="<?php print $_SESSION['email']; ?>" maxlength="60">
                            </div>
                            <div class="campo-entrada">
                                <label for="email-conf">Confirme o e-mail:</label>
                                <input type="text" id="email-conf" name="email_conf" maxlength="60" value="<?php print $_SESSION['email_conf']; ?>">
                            </div>
                            <div class="campo-entrada">
                                <label for="senha">Senha:</label>
                                <input type="password" id="senha" name="senha" maxlength="10" value="<?php print $_SESSION['senha']; ?>">
                            </div>
                            <div class="campo-entrada">
                                <label for="senha-conf">(2) Confirme a senha:</label>
                                <input type="password" id="senha-conf" name="senha_conf" maxlength="10" value="<?php print $_SESSION['senha_conf']; ?>">
                            </div>
                        </div>
                        <div id="endereco-entrega">
                            <h6>Endereco Entrega</h6>
                            <div class="campo-entrada">
                                <label for="cep">CEP:</label>
                                <input type="text" id="cep" name="cep" maxlength="8" value="<?php print $_SESSION['cep']; ?>">
                            </div>                                                                
                            <div class="campo-entrada">
                                <label for="logradouro">Logradouro:</label>
                                <input type="text" id="logradouro" name="logradouro" maxlength="60" value="<?php print $_SESSION['logradouro']; ?>" >
                            </div>
                            <div class="campo-entrada">
                                <label for="numero">Número:</label>
                                <input type="text" id="numero" name="numero_logra" maxlength="10" value="<?php print $_SESSION['numero_logra']; ?>">
                            </div>                                
                            <div class="campo-entrada">
                                <label for="complemento">Complemento:</label>
                                <input type="text" id="complemento" name="complemento" maxlength="20" value="<?php print $_SESSION['complemento']; ?>">
                            </div>                                                                
                            <div class="campo-entrada">
                                <label for="bairro">Bairro:</label>
                                <input type="text" id="bairro" name="bairro" maxlength="40" value="<?php print $_SESSION['bairro']; ?>" >
                            </div>                                                                                      
                            <div class="campo-entrada">
                                <label for="cidade">Cidade:</label>
                                <input type="text" id="cidade" name="cidade" maxlength="40" value="<?php print $_SESSION['cidade']; ?>" >
                            </div>
                            <div class="campo-entrada">
                                <label for="uf">UF:</label>
                                <select name="uf" id="uf" >
                                    <option value=''>Selecione</option>
                                    <?php
                                    $option = "";

                                    $sql = "SELECT";
                                    $sql .= "  uf";
                                    $sql .= " ,nome";
                                    $sql .= "  FROM tb_estados";
                                    $sql .= " ORDER BY nome";

                                    $rs_uf = mysqli_query($conexao, $sql);
                                    require "includes/status_acesso_db.php";

                                    while ($reg = mysqli_fetch_array($rs_uf)) {
                                        require "includes/status_acesso_db.php";
                                        if ($_SESSION['uf'] == $reg['uf']) {
                                            $option .= "<option value='" . $reg['uf'] . "' selected>" . $reg['nome'] . "</option>";
                                        } else {
                                            $option .= "<option id='" . $reg['uf'] . "' value='" . $reg['uf'] . "'>" . $reg['nome'] . "</option>";
                                        }
                                    }
                                    print $option;
                                    mysqli_free_result($rs_uf);
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div>
                            <div class="continuar">
                                <button type="submit"><img src="img/btn_continuar.gif" alt="Continuar"></button>
                            </div>
                        </div>
                    </div>
                </form>
                <p>&nbsp;</p>
                <div id="caixa">
                    <h2>Observações</h2>
                    <p><strong>(1) Porque pedimos seu CPF?</strong> O CPF é um dado de identificação importante, pessoal e intransferível, que garante maior segurança em suas transações no Faça um Site Miniaturas. Por isso, o pedimos em seu cadastro no site, além do mais, ele é um dado obrigatório para a emissão da nota fiscal. Tanto o CPF quanto seus demais dados serão mantidos em completo sigilo, não sendo repassados a terceiros sob nenhuma hipótese.</p><br>
                    <p><strong>(2) Porque preciso repetir meu e-mail e senha?</strong> É muito importante que a comunicação da Faça un Site Miniaturas com você aconteça satisfatoriamente. Por isso, pedimos a confirmação do seu e-mail, evitando erros de digitação que possam impedir o recebimento de mensagens sobre pedidos feitos no site. Seu e-mail permanecerá em completo sigilo e não será repassado a terceiros sob nenhuma hipótese.</p>
                </div>
            </div>
            <?php require_once "includes/rodape.php"; ?>	
        </div>
        <script src="js/cadastro.js"></script>
        <?php if ($_SESSION['meu_cadastro'] == "S") { ?>
            <script src="js/subcategoria.js"></script>
        <?php } ?>
    </body>
</html>
<?php
mysqli_close($conexao);
?>