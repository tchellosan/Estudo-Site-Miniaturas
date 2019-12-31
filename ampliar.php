<?php
require_once "includes/funcoes.php";
if (isset($_GET['codigo']) && isset($_GET['nome'])) {
    $codigo = test_input($_GET['codigo']);
    $nome = test_input($_GET['nome']);
}
?>
<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <title>Miniaturas</title>
        <link rel="stylesheet" href="css/estilo_site.css">
    </head>
    <body>
        <div class="ampliar">
            <figure>
                <figcaption class="titulo-imagem"><?php print $nome; ?></figcaption>
                <img src="img/<?php print $codigo; ?>G.jpg" alt="<?php print $nome; ?>">
            </figure>
        </div>
    </body>
</html>