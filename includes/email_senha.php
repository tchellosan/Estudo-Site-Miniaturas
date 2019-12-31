<?php

$assunto = "Recuperação de Senha - Site Miniaturas";

$corpo = "Olá $nome!\n\n";
$corpo .= "Agradecemos a sua preferência pelo Site Miniaturas.\n\n";
$corpo .= "A sua senha de acesso ao nossi site é: $senha\n\n";

$corpo .= "Para a sua segurança não revele a sua senha a ninguém.\n";
$corpo .= "Esta mensagem foi enviada apenas para o seu e-mail, e só você tem acesso a ela.\n\n";

$corpo .= "Atenciosamente,\n";
$corpo .= "Site Miniaturas";

$cabecalho = "From: pedidos@miniaturas.com";

if (mail($email_cliente, $assunto, $corpo, $cabecalho)) {
    $tipo_mensagem = "sucesso";
}
?>