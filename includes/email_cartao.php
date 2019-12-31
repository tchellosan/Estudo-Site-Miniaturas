<?php

$assunto = "Confirmação do Seu Pedido [$num_ped]";

$corpo = "Olá $nome!\n\n";
$corpo .= "Agradecemos a sua preferência pelo Site Miniaturas.\n\n";
$corpo .= "A confirmação do seu pedido de número $num_ped, no valor de R$ $valor_compra foi realizada com sucesso.\n\n";
$corpo .= "Para acompanhar este pedido visite o nosso site e selecione a opção 'Meus Pedidos'.\n\n";

$corpo .= "$link\n\n";

$corpo .= "Atenciosamente,\n";
$corpo .= "Site Miniaturas";

$cabecalho = "From: pedidos@miniaturas.com";

if (mail($email_cliente, $assunto, $corpo, $cabecalho)) {
    $mensagem = "Pedido enviado com sucesso, em alguns instantes você irá receber um e-mail com os dados do pedido.";
    $tipo_mensagem = "sucesso";
}
?>