<?php
session_start();

require_once "includes/funcoes.php";
require_once "includes/dbConexao.php";

unset($_SESSION['erro']);

if ($_SERVER['REQUEST_METHOD'] == 'GET' && !isset($_SESSION['logado'])) {
    header("Location: index.php");
    exit;
}

if ($_SESSION['meu_cadastro'] == "N") {
    $etapa = "Etapa 2";
    $title = "Endereço de Entrega";
} else {
    $title = "Meu Cadastro";
}

if (isset($_SESSION['operacao'])) {
    switch ($_SESSION['operacao']) {
        case "incluir":
            $_SESSION['id_cliente'] = 0;

            validarDadosRecebidos("incluir");

            $sql_i = "INSERT INTO cadcli (";
            $sql_i .= "  nome";
            $sql_i .= " ,cpf";
            $sql_i .= " ,rg";
            $sql_i .= " ,sexo";
            $sql_i .= " ,email";
            $sql_i .= " ,senha";
            $sql_i .= " ,end_nome";
            $sql_i .= " ,end_num";
            $sql_i .= " ,end_comp";
            $sql_i .= " ,cep";
            $sql_i .= " ,bairro";
            $sql_i .= " ,cidade";
            $sql_i .= " ,uf) ";
            $sql_i .= " VALUES ('" . $_SESSION['nome_completo'] . "'";
            $sql_i .= " ,'" . $_SESSION['cpf'] . "'";
            $sql_i .= " ,'" . $_SESSION['rg'] . "'";
            $sql_i .= " ,'" . $_SESSION['sexo'] . "'";
            $sql_i .= " ,'" . $_SESSION['email'] . "'";
            $sql_i .= " ,'" . $_SESSION['senha'] . "'";
            $sql_i .= " ,'" . $_SESSION['logradouro'] . "'";
            $sql_i .= " ,'" . $_SESSION['numero_logra'] . "'";
            $sql_i .= " ,'" . $_SESSION['complemento'] . "'";
            $sql_i .= " ,'" . $_SESSION['cep'] . "'";
            $sql_i .= " ,'" . $_SESSION['bairro'] . "'";
            $sql_i .= " ,'" . $_SESSION['cidade'] . "'";
            $sql_i .= " ,'" . $_SESSION['uf'] . "')";

            mysqli_query($conexao, $sql_i);
            require "includes/status_acesso_db.php";

            $_SESSION['id_cliente'] = mysqli_insert_id($conexao);
            $_SESSION['logado'] = 'S';

            break;

        case "alterar":
            if (!isset($_SESSION['id_cliente'])) {
                $_SESSION['erro'][] = 30;
                header("Location: erro.php");
                exit;
            }

            validarDadosRecebidos("alterar");

            $sql_u = "UPDATE cadcli SET ";
            $sql_u .= "  nome ='" . $_SESSION['nome_completo'] . "'";
            $sql_u .= " ,cpf ='" . $_SESSION['cpf'] . "'";
            $sql_u .= " ,rg ='" . $_SESSION['rg'] . "'";
            $sql_u .= " ,sexo ='" . $_SESSION['sexo'] . "'";
            $sql_u .= " ,email ='" . $_SESSION['email'] . "'";
            $sql_u .= " ,senha ='" . $_SESSION['senha'] . "'";
            $sql_u .= " ,end_nome ='" . $_SESSION['logradouro'] . "'";
            $sql_u .= " ,end_num ='" . $_SESSION['numero_logra'] . "'";
            $sql_u .= " ,end_comp ='" . $_SESSION['complemento'] . "'";
            $sql_u .= " ,cep ='" . $_SESSION['cep'] . "'";
            $sql_u .= " ,bairro ='" . $_SESSION['bairro'] . "'";
            $sql_u .= " ,cidade ='" . $_SESSION['cidade'] . "'";
            $sql_u .= " ,uf ='" . $_SESSION['uf'] . "'";
            $sql_u .= " WHERE id = " . $_SESSION['id_cliente'];

            mysqli_query($conexao, $sql_u);
            require "includes/status_acesso_db.php";

            break;

        case "exibir_cadastro":
            $sql = "SELECT";
            $sql .= " id";
            $sql .= " ,nome";
            $sql .= " ,cpf";
            $sql .= " ,rg";
            $sql .= " ,sexo";
            $sql .= " ,email";
            $sql .= " ,senha";
            $sql .= " ,end_nome";
            $sql .= " ,end_num";
            $sql .= " ,end_comp";
            $sql .= " ,cep";
            $sql .= " ,bairro";
            $sql .= " ,cidade";
            $sql .= " ,uf";
            $sql .= "  FROM cadcli";
            $sql .= " WHERE id = " . $_SESSION['id_cliente'];

            $rs_exibir = mysqli_query($conexao, $sql);
            require "includes/status_acesso_db.php";

            $rs_exibir_total_registros = mysqli_num_rows($rs_exibir);

            if ($rs_exibir_total_registros == 0) {
                mysqli_free_result($rs_exibir);
                $_SESSION['erro'][] = 39;
                header("Location: erro.php");
                exit;
            }

            extract(mysqli_fetch_array($rs_exibir), EXTR_PREFIX_ALL, "rs_exibir");
            require "includes/status_acesso_db.php";

            $_SESSION['id_cliente'] = $rs_exibir_id;
            $_SESSION['nome_completo'] = $rs_exibir_nome;
            $_SESSION['cpf'] = $rs_exibir_cpf;
            $_SESSION['rg'] = $rs_exibir_rg;
            $_SESSION['sexo'] = $rs_exibir_sexo;
            $_SESSION['email'] = $rs_exibir_email;
            $_SESSION['email_conf'] = $rs_exibir_email;
            $_SESSION['senha'] = $rs_exibir_senha;
            $_SESSION['senha_conf'] = $rs_exibir_senha;
            $_SESSION['cep'] = $rs_exibir_cep;
            $_SESSION['logradouro'] = $rs_exibir_end_nome;
            $_SESSION['numero_logra'] = $rs_exibir_end_num;
            $_SESSION['complemento'] = $rs_exibir_end_comp;
            $_SESSION['bairro'] = $rs_exibir_bairro;
            $_SESSION['cidade'] = $rs_exibir_cidade;
            $_SESSION['uf'] = $rs_exibir_uf;

            mysqli_free_result($rs_exibir);

        default:
            break;
    }
} else {
    $_SESSION['erro'][] = 13;
    header("Location: erro.php");
    exit;
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
            ?><?php print ucwords($title); ?> - Dados Pessoais</title>
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
                <div id="etapa-2">
                    <div class="logo">
                        <a href="index.php"><img src="img/logo_fs.gif" alt="Miniaturas"></a>
                    </div>
                </div>
            <?php } ?>
            <h1><?php
                if ($_SESSION['meu_cadastro'] == "N") {
                    print $etapa;
                    ?><img src="img/marcador_setaDir.gif" alt="seta direita"><?php } ?><?php print $title; ?><img src="img/marcador_setaDir.gif" alt="seta direita">Dados Pessoais
            </h1>
            <div id="">
                <div id="caixa-cadastro">
                    <div id="dados-pessoais">
                        <h6>Dados Pessoais</h6>
                        <div class="campo-entrada">
                            <label for="nome-completo">Nome completo:</label>
                            <input type="text" id="nome-completo" name="nome_completo" maxlength="60" value="<?php print $_SESSION['nome_completo']; ?>" readonly>
                        </div>
                        <div class="campo-entrada">
                            <label for="cpf">CPF:</label>
                            <input type="text" id="cpf" name="cpf" maxlength="11" value="<?php print substr($_SESSION['cpf'], 0, 3) . '.' . substr($_SESSION['cpf'], 3, 3) . '.' . substr($_SESSION['cpf'], 6, 3) . '-' . substr($_SESSION['cpf'], 9, 2); ?>" readonly>
                        </div>
                        <div class="campo-entrada">
                            <label for="rg">RG:</label>
                            <input type="text" id="rg" name="rg" maxlength="14" value="<?php print $_SESSION['rg']; ?>" readonly>
                        </div>
                        <div class="campo-entrada">
                            <label for="sexo">Sexo:</label>
                            <input type="text" id="sexo" name="sexo"  value="<?php ($_SESSION['sexo'] == "M") ? print "Masculino" : print "Feminino"; ?>" readonly>
                        </div>
                        <div class="campo-entrada">                                
                            <label for="email">E-mail:</label>
                            <input type="text" id="email" name="email" value="<?php print $_SESSION['email']; ?>" maxlength="60" readonly>
                        </div>
                        <div class="campo-entrada">
                            <label for="senha">Senha:</label>
                            <input type="password" id="senha" name="senha" maxlength="10" value="<?php print $_SESSION['senha']; ?>" readonly>
                        </div>
                    </div>
                    <div id="endereco-entrega">
                        <h6>Endereco Entrega</h6>
                        <div class="campo-entrada">
                            <label for="cep">CEP:</label>
                            <input type="text" id="cep" name="cep" maxlength="8" value="<?php print substr($_SESSION['cep'], 0, 5) . '-' . substr($_SESSION['cep'], 5, 3); ?>" readonly>
                        </div>                                                                
                        <div class="campo-entrada">
                            <label for="logradouro">Logradouro:</label>
                            <input type="text" id="logradouro" name="logradouro" maxlength="60" value="<?php print $_SESSION['logradouro']; ?>" readonly>
                        </div>
                        <div class="campo-entrada">
                            <label for="numero">Número:</label>
                            <input type="text" id="numero" name="numero_logra" maxlength="10" value="<?php print $_SESSION['numero_logra']; ?>" readonly>
                        </div>                                
                        <div class="campo-entrada">
                            <label for="complemento">Complemento:</label>
                            <input type="text" id="complemento" name="complemento" maxlength="20" value="<?php print $_SESSION['complemento']; ?>" readonly>
                        </div>                                                                

                        <div class="campo-entrada">
                            <label for="bairro">Bairro:</label>
                            <input type="text" id="bairro" name="bairro" maxlength="40" value="<?php print $_SESSION['bairro']; ?>" readonly>
                        </div>                                                                                      
                        <div class="campo-entrada">
                            <label for="cidade">Cidade:</label>
                            <input type="text" id="cidade" name="cidade" maxlength="40" value="<?php print $_SESSION['cidade']; ?>" readonly>
                        </div>
                        <div class="campo-entrada">
                            <label for="uf">UF:</label>
                            <input type="text" id="uf" name="uf"
                            <?php
                            $sql = "SELECT";
                            $sql .= "  uf";
                            $sql .= " ,nome";
                            $sql .= "  FROM tb_estados";

                            $rs_uf = mysqli_query($conexao, $sql);
                            require "includes/status_acesso_db.php";

                            while ($reg = mysqli_fetch_array($rs_uf)) {
                                require "includes/status_acesso_db.php";
                                if ($_SESSION['uf'] == $reg['uf']) {
                                    ?>
                                           value="<?php print $reg['uf'] . ' - ' . $reg['nome']; ?>" 
                                           <?php
                                       }
                                   }
                                   mysqli_free_result($rs_uf);
                                   ?>
                                   readonly>
                        </div>
                    </div>
                    <div>
                        <div class="continuar cadastro-manut">
                            <a href="index.php?sair"><img src="img/btn_sair.gif" alt="Sair"></a>
                            <a href="cadastro.php?operacao=alterar"><img src="img/btn_alterar.gif" alt="Alterar"></a>
                            <?php if ($_SESSION['meu_cadastro'] == "N") { ?>
                                <a href="pagamento.php"><img src="img/btn_continuar.gif" alt="Continuar"></a>
                            <?php } else { ?>
                                <a href="index.php"><img src="img/btn_voltarLoja.gif" alt="Voltar à Loja"></a>
                            <?php } ?>
                        </div>
                        <div class="mensagem-manut">
                            <?php
                            switch ($_SESSION['operacao']) {
                                case "incluir":
                                    $mensagem = "Inclusão efetuada com sucesso!";
                                    break;

                                case "alterar":
                                    $mensagem = "Alteração efetuada com sucesso!";
                                    break;

                                case "exibir_cadastro":
                                    if (isset($_SESSION['logado']) && $_SESSION['logado'] == 'S') {
                                        $mensagem = "";
                                    } else {
                                        $mensagem = "Login efetuado com sucesso!";
                                    }

                                    break;

                                default:
                                    break;
                            }
                            ?>
                            <span><?php print $mensagem; ?></span>
                        </div>
                    </div>
                </div>
            </div>
            <?php require_once "includes/rodape.php"; ?>	
        </div>
        <?php if ($_SESSION['meu_cadastro'] == "S") { ?>
            <script src="js/subcategoria.js"></script>
        <?php } ?>
    </body>
</html>
<?php

function validarDadosRecebidos($operacao) {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $_SESSION['nome_completo'] = $_POST['nome_completo'];
        $_SESSION['cpf'] = $_POST['cpf'];
        $_SESSION['rg'] = $_POST['rg'];
        $_SESSION['sexo'] = $_POST['sexo'];
        $_SESSION['email'] = $_POST['email'];
        $_SESSION['email_conf'] = $_POST['email_conf'];
        $_SESSION['senha'] = $_POST['senha'];
        $_SESSION['senha_conf'] = $_POST['senha_conf'];
        $_SESSION['cep'] = $_POST['cep'];
        $_SESSION['logradouro'] = $_POST['logradouro'];
        $_SESSION['numero_logra'] = $_POST['numero_logra'];
        $_SESSION['complemento'] = $_POST['complemento'];
        $_SESSION['bairro'] = $_POST['bairro'];
        $_SESSION['cidade'] = $_POST['cidade'];
        $_SESSION['uf'] = $_POST['uf'];
    }

    $_SESSION['nome_completo'] = test_input($_SESSION['nome_completo']);

    if (!$_SESSION['nome_completo']) {
        $_SESSION['erro'][] = 15;
    }

    $_SESSION['cpf'] = test_input($_SESSION['cpf']);

    $_SESSION['cpf'] = trim($_SESSION['cpf']);

    if (!$_SESSION['cpf']) {
        $_SESSION['erro'][] = 16;
    } else {
        $_SESSION['cpf'] = str_pad($_SESSION['cpf'], 11 - strlen($_SESSION['cpf']), "0", STR_PAD_LEFT);
        switch ($_SESSION['cpf']) {
            case str_pad("", 11, "0"):
            case str_pad(11, "1"):
            case str_pad("", 11, "2"):
            case str_pad("", 11, "3"):
            case str_pad("", 11, "4"):
            case str_pad("", 11, "5"):
            case str_pad("", 11, "6"):
            case str_pad("", 11, "7"):
            case str_pad("", 11, "8"):
            case str_pad("", 11, "9"):
                $_SESSION['erro'][] = 28;
                break;

            default:
                $pos_comp = 9;
                $pos_n = 9;
                do {
                    $soma = 0;
                    for ($fator = 2; $pos_n; $fator++) {
                        $soma += substr($_SESSION['cpf'], --$pos_n, 1) * $fator;
                    }

                    $digito = 11 - ($soma % 11);
                    if ($digito == 10 || $digito == 11) {
                        $digito = 0;
                    }

                    if (substr($_SESSION['cpf'], $pos_comp, 1) != $digito) {
                        $_SESSION['erro'][] = 28;
                        break;
                    }
                    $pos_n = 10;
                } while (++$pos_comp <= 10);
        }
    }

    $_SESSION['rg'] = test_input($_SESSION['rg']);

    $_SESSION['sexo'] = test_input($_SESSION['sexo']);

    if (!$_SESSION['sexo']) {
        $_SESSION['erro'][] = 17;
    }

    $_SESSION['email'] = filter_var(test_input($_SESSION['email']), FILTER_VALIDATE_EMAIL);

    $_SESSION['email_conf'] = filter_var(test_input($_SESSION['email_conf']), FILTER_VALIDATE_EMAIL);

    if (!$_SESSION['email']) {
        $_SESSION['erro'][] = 18;
    } else {
        global $conexao;
        $sql = "SELECT";
        $sql .= "  email";
        $sql .= "  FROM cadcli";
        $sql .= " WHERE email =  '" . $_SESSION['email'] . "'";
        $sql .= "   AND    id <> '" . $_SESSION['id_cliente'] . "'";

        $rs_email = mysqli_query($conexao, $sql);
        require "includes/status_acesso_db.php";

        $rs_email_total_registros = mysqli_num_rows($rs_email);

        mysqli_free_result($rs_email);

        if ($rs_email_total_registros > 0) {
            $_SESSION['erro'][] = 34;
        } else if ($_SESSION['email'] != $_SESSION['email_conf']) {
            $_SESSION['erro'][] = 19;
        }
    }

    $re_senha = "/^.{5,10}$/";

    $_SESSION['senha'] = test_input($_SESSION['senha']);


    $_SESSION['senha_conf'] = test_input($_SESSION['senha_conf']);

    if (!preg_match($re_senha, $_SESSION['senha'])) {
        $_SESSION['erro'][] = 20;
    }

    if ($_SESSION['senha'] != $_SESSION['senha_conf']) {
        $_SESSION['erro'][] = 21;
    }

    $_SESSION['cep'] = test_input($_SESSION['cep']);


    if (!$_SESSION['cep']) {
        $_SESSION['erro'][] = 24;
    }

    $_SESSION['logradouro'] = test_input($_SESSION['logradouro']);


    if (!$_SESSION['logradouro']) {
        $_SESSION['erro'][] = 22;
    }

    $_SESSION['numero_logra'] = test_input($_SESSION['numero_logra']);


    if (!$_SESSION['numero_logra']) {
        $_SESSION['erro'][] = 23;
    }

    $_SESSION['complemento'] = test_input($_SESSION['complemento']);


    $_SESSION['bairro'] = test_input($_SESSION['bairro']);


    if (!$_SESSION['bairro']) {
        $_SESSION['erro'][] = 25;
    }

    $_SESSION['cidade'] = test_input($_SESSION['cidade']);


    if (!$_SESSION['cidade']) {
        $_SESSION['erro'][] = 26;
    }

    $_SESSION['uf'] = test_input($_SESSION['uf']);

    if (!$_SESSION['uf']) {
        $_SESSION['erro'][] = 27;
    } else {
        $faixa_cep_uf = [
            [ "uf" => "AC", "min" => "69900000", "max" => "69999999"],
            [ "uf" => "AL", "min" => "57000000", "max" => "57999999"],
            [ "uf" => "AP", "min" => "68900000", "max" => "68999999"],
            [ "uf" => "AM", "min" => "69000000", "max" => "69899999"],
            [ "uf" => "BA", "min" => "40000000", "max" => "48999999"],
            [ "uf" => "CE", "min" => "60000000", "max" => "63999999"],
            [ "uf" => "DF", "min" => "70000000", "max" => "73699999"],
            [ "uf" => "ES", "min" => "29000000", "max" => "29999999"],
            [ "uf" => "GO", "min" => "72800000", "max" => "76799999"],
            [ "uf" => "MA", "min" => "65000000", "max" => "65999999"],
            [ "uf" => "MT", "min" => "78000000", "max" => "78899999"],
            [ "uf" => "MS", "min" => "79000000", "max" => "79999999"],
            [ "uf" => "MG", "min" => "30000000", "max" => "39999999"],
            [ "uf" => "PA", "min" => "66000000", "max" => "68899999"],
            [ "uf" => "PB", "min" => "58000000", "max" => "58999999"],
            [ "uf" => "PR", "min" => "80000000", "max" => "87999999"],
            [ "uf" => "PE", "min" => "50000000", "max" => "56999999"],
            [ "uf" => "PI", "min" => "64000000", "max" => "64999999"],
            [ "uf" => "RJ", "min" => "20000000", "max" => "28999999"],
            [ "uf" => "RN", "min" => "59000000", "max" => "59999999"],
            [ "uf" => "RS", "min" => "90000000", "max" => "99999999"],
            [ "uf" => "RO", "min" => "78900000", "max" => "78999999"],
            [ "uf" => "RR", "min" => "69300000", "max" => "69399999"],
            [ "uf" => "SC", "min" => "88000000", "max" => "89999999"],
            [ "uf" => "SC", "min" => "00000000", "max" => "19999999"],
            [ "uf" => "SP", "min" => "01000000", "max" => "19999999"],
            [ "uf" => "SE", "min" => "49000000", "max" => "49999999"],
            [ "uf" => "TO", "min" => "77000000", "max" => "77999999"]
        ];

        foreach ($faixa_cep_uf as $info_uf) {
            if ($info_uf['uf'] == $_SESSION['uf']) {
                if ($_SESSION['cep'] < $info_uf['min'] || $_SESSION['cep'] > $info_uf['max']) {
                    $_SESSION['erro'][] = 29;
                }
            }
        }
    }

    if (isset($_SESSION['erro'])) {
        $_SESSION['erro_cad_manut'] = $operacao;
        header("Location: erro.php");
        exit;
    }
}

mysqli_close($conexao);
?>