<?php
$mensagem_erros[1] = "Não existem produtos cadastrados.";
$mensagem_erros[2] = "Código do produto duplicado.";
$mensagem_erros[3] = "ID e/ou Nome da categoria de produtos não informado(s).";
$mensagem_erros[4] = "Não existem produtos cadastrados para a categoria selecionada.";
$mensagem_erros[5] = "Não existem produtos cadastrados para a subcategoria selecionada.";
$mensagem_erros[6] = "Não foi possível carregar o menu Categorias.";
$mensagem_erros[7] = "Código do produto não informado.";
$mensagem_erros[8] = "Item do pedido não informado.";
$mensagem_erros[9] = "Sessão inexistente.";
$mensagem_erros[10] = "Nova quantidade do produto não informada.";
$mensagem_erros[11] = "A quantidade permitida por produto é entre 1 e 10 unidades.";
$mensagem_erros[12] = "Por favor, informe o seu e-mail.";
$mensagem_erros[13] = "Operação inexistente ou não informada.";
$mensagem_erros[14] = "E-mail inválido ou não informado.";
$mensagem_erros[15] = "Nome completo deve ser informado.";
$mensagem_erros[16] = "CPF deve ser informado.";
$mensagem_erros[17] = "Sexo deve ser informado.";
$mensagem_erros[18] = "E-mail deve ser informado.";
$mensagem_erros[19] = "E-mail de confirmação deve ser igual ao e-mail informado.";
$mensagem_erros[20] = "Senha inválida.";
$mensagem_erros[21] = "Senha de confirmação deve ser igual a senha informada.";
$mensagem_erros[22] = "Logradouro deve ser informado.";
$mensagem_erros[23] = "Número do logradouro deve ser informado.";
$mensagem_erros[24] = "CEP deve ser informado.";
$mensagem_erros[25] = "Bairro deve ser informado.";
$mensagem_erros[26] = "Cidade deve ser informada.";
$mensagem_erros[27] = "UF deve ser informada.";
$mensagem_erros[28] = "CPF inválido.";
$mensagem_erros[29] = "CEP inválido para o estado selecionado.";
$mensagem_erros[30] = "ID do cliente inexistente.";
$mensagem_erros[31] = "Por favor, informe a sua senha.";
$mensagem_erros[32] = "E-mail não cadastrado.";
$mensagem_erros[33] = "Senha inválida para o e-mail informado.";
$mensagem_erros[34] = "E-mail já cadastrado, utilize outro por favor.";
$mensagem_erros[35] = "Seu carrinho está vazio.";
$mensagem_erros[36] = "Nome da subcategoria de produtos não informado.";
$mensagem_erros[37] = "Produto não encontrado.";
$mensagem_erros[38] = "Pedido inexistente.";
$mensagem_erros[39] = "Cliente inexistente.";
$mensagem_erros[40] = "Bandeira de cartão de crédito deve ser informada.";
$mensagem_erros[41] = "Número do cartão deve ser informado.";
$mensagem_erros[42] = "Número do cartão inválido.";
$mensagem_erros[43] = "Nome impresso no cartão deve ser informado.";
$mensagem_erros[44] = "Mês referente a válidade do cartão deve ser informado.";
$mensagem_erros[45] = "Mês referente a válidade do cartão inválido.";
$mensagem_erros[46] = "Ano referente a válidade do cartão deve ser informado.";
$mensagem_erros[47] = "Ano referente a válidade do cartão inválido.";
$mensagem_erros[48] = "Código de segurança do cartão deve ser informado.";
$mensagem_erros[49] = "Código de segurança do cartão inválido.";
$mensagem_erros[50] = "Quantidade de parcelas deve ser informada.";
$mensagem_erros[51] = "Quantidade de parcelas inválida.";
$mensagem_erros[52] = "Bandeira de cartão de crédito inválida.";
$mensagem_erros[53] = "Selecione a opção Boleto Bancário.";
$mensagem_erros[54] = "Você ainda não realizou nenhum pedido.";
$mensagem_erros[55] = "A forma de pagamento deste pedido não é Boleto Bancário.";
$mensagem_erros[56] = "Este pedido foi cancelado devido a falta de pagamento no prazo de vencimento.";
$mensagem_erros[57] = "Pedido não disponível para impressão de boleto.";
$mensagem_erros[99] = "Erro no acesso ao banco de dados";
?>

<?php
session_start();
if (isset($_SESSION['erro'])) {
    $mensagem = "";
    if ($_SESSION['erro'][0] == 99) {
        $mensagem = $_SESSION['erro_db'];
    } else {
        foreach ($_SESSION['erro'] as $codigo_erro) {
            if ($codigo_erro[0] == 2) {
                $mensagem .= " - " . $mensagem_erros[$codigo_erro[0]] . " (Produto: " . $codigo_erro[1] . ")<br>";
            } else {
                $mensagem .= " - " . $mensagem_erros[$codigo_erro] . "<br>";
            }
        }
    }
} else {
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang = "pt-br">
    <head>
        <meta charset = "UTF-8">
        <title>Erro - Miniaturas</title>
        <link rel="stylesheet" href="css/estilo_site.css">
    </head>
    <body >
        <div id="corpo">
            <?php if (!($_SESSION['erro'][0] == 99)) { ?>
                <div id="topo">
                    <?php require_once "includes/menu_superior.php"; ?>
                </div>
                <div id="menuSup">
                    <?php require_once "includes/menu_categorias.php"; ?>
                </div>
            <?php } ?>

            <div id="caixa">
                <div class="caixa-erro">
                    <h2 class="mensagem-erro"><?php print $mensagem; ?></h2>
                    <?php if (($_SESSION['erro'][0] == 35)) { ?>
                        <input type="hidden" id="carrinho_vazio">
                        <p><a href="" id="btn_voltar"><img src="img/btn_voltarLoja.gif" alt="Voltar à Loja"></a></p>
                    <?php } else { ?>
                        <p><a href="" id="btn_voltar"><img src="img/btn_voltar.gif" alt="Voltar"></a></p>
                    <?php } ?>

                    <?php
                    if (isset($_SESSION['erro_cad_manut'])) {
                        unset($_SESSION['erro_cad_manut']);
                        ?>
                        <input type="hidden" id="erro_cad_manut">
                    <?php } ?>

                    <?php
                    if (isset($_SESSION['erro_pgto'])) {
                        unset($_SESSION['erro_pgto']);
                        ?>
                        <input type="hidden" id="erro_pgto">
                    <?php } ?>                        
                </div>
            </div>

            <?php require_once "includes/rodape.php"; ?>	
        </div>
        <?php if (!($_SESSION['erro'][0] == 99)) { ?>
            <script src="js/subcategoria.js"></script>
        <?php } ?>
        <script src="js/erro.js"></script>
    </body>
</html>