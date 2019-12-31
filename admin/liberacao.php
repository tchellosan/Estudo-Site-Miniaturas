<?PHP
session_destroy();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Faça um Site - PHP 5 com banco de dados MySQL</title>
    <link href="estilo_adm.css" rel="stylesheet" type="text/css" />
</head>
<body>
    <div id="corpo">
        <div id="topo">
            <h1>Administração do Site</h1>
        </div>
        <!-- T�tulo da p�gina -->
        <h1>Você não possui autorização para executar esta página</h1>
        <div id="caixa"><div align="center">
                <table width="100%" height="200" border="0" cellpadding="0" cellspacing="0">
                    <tr>
                        <td align="center">
                            <p><a href="index.php"><img src="../img/btn_voltar.gif" alt="Voltar" vspace="5" border="0" /></a></p>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <?PHP include "inc_rodape.php" ?>	
    </div>
</body>
</html>
