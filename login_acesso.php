<?php

session_start();
require_once "includes/funcoes.php";
require_once "includes/dbConexao.php";

unset($_SESSION['erro']);

if (isset($_POST['operacao'])) {
    if (!$_POST['operacao']) {
        $_SESSION['erro'][] = 13;
        header("Location: erro.php");
        exit;
    }
    $_SESSION['operacao'] = $_POST['operacao'];
}

if (isset($_POST['email'])) {
    global $email;

    if (!$_POST['email']) {
        $_SESSION['erro'][] = 12;
        header("Location: erro.php");
        exit;
    }

    $email = filter_var(test_input($_POST['email']), FILTER_VALIDATE_EMAIL);

    if (!$email) {
        $_SESSION['erro'][] = 14;
        header("Location: erro.php");
        exit;
    }
}

if (isset($_POST['senha'])) {
    global $senha;

    if (!$_POST['senha']) {
        $_SESSION['erro'][] = 31;
        header("Location: erro.php");
        exit;
    }

    $re_senha = "/^.{5,10}$/";
    $senha = test_input($_POST['senha']);

    if (!preg_match($re_senha, $senha)) {
        $_SESSION['erro'][] = 20;
        header("Location: erro.php");
        exit;
    }
}

$sql = "SELECT";
$sql .= "  email";
$sql .= "  FROM cadcli";
$sql .= " WHERE email = '$email'";

$rs_email = mysqli_query($conexao, $sql);
require "includes/status_acesso_db.php";

$rs_email_total_registros = mysqli_num_rows($rs_email);

mysqli_free_result($rs_email);

if ($rs_email_total_registros == 0) {
    $_SESSION['erro'][] = 32;
    header("Location: erro.php");
    exit;
}

if (isset($_SESSION['operacao']) && $_SESSION['operacao'] == 'recuperar_senha') {
    $_SESSION['email'] = $_POST['email'];
    header("Location: senha_recuperar.php");
    exit;
}

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
$sql .= " WHERE email = '$email'";
$sql .= "   AND senha = '$senha'";

$rs_senha = mysqli_query($conexao, $sql);
require "includes/status_acesso_db.php";

$rs_senha_total_registros = mysqli_num_rows($rs_senha);

if ($rs_senha_total_registros == 0) {
    mysqli_free_result($rs_senha);
    $_SESSION['erro'][] = 33;
    header("Location: erro.php");
    exit;
}

extract(mysqli_fetch_array($rs_senha), EXTR_PREFIX_ALL, "rs_senha");
require "includes/status_acesso_db.php";

$_SESSION['id_cliente'] = $rs_senha_id;
$_SESSION['nome_completo'] = $rs_senha_nome;
$_SESSION['cpf'] = $rs_senha_cpf;
$_SESSION['rg'] = $rs_senha_rg;
$_SESSION['sexo'] = $rs_senha_sexo;
$_SESSION['email'] = $rs_senha_email;
$_SESSION['email_conf'] = $rs_senha_email;
$_SESSION['senha'] = $rs_senha_senha;
$_SESSION['senha_conf'] = $rs_senha_senha;
$_SESSION['cep'] = $rs_senha_cep;
$_SESSION['logradouro'] = $rs_senha_end_nome;
$_SESSION['numero_logra'] = $rs_senha_end_num;
$_SESSION['complemento'] = $rs_senha_end_comp;
$_SESSION['bairro'] = $rs_senha_bairro;
$_SESSION['cidade'] = $rs_senha_cidade;
$_SESSION['uf'] = $rs_senha_uf;

switch ($_SESSION['operacao']) {
    case 'exibir_cadastro':
        $_SESSION['logado'] = 'S';
        header("Location: cadastro_manut.php");
        break;

    case 'exibir_pedidos':
        $_SESSION['logado'] = 'S';
        header("Location: pedidos_lista.php");
        break;

    default:
        $_SESSION['logado'] = 'N';
        $_SESSION['erro'][] = 13;
        header("Location: erro.php");
        break;
}

mysqli_free_result($rs_senha);
mysqli_close($conexao);
?>