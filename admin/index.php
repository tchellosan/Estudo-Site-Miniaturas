<?php
require_once "../includes/defines.php";
require_once "../includes/funcoes.php";
require_once "../includes/dbConexao.php";
session_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Faça um Site - PHP 5 com banco de dados MySQL</title>
    <link href="estilo_adm.css" rel="stylesheet" type="text/css" />
    <script language="javascript">
        function valida_form() {
            if (document.form_login.login.value == "") {
                alert("Por favor, preencha o campo [Login].");
                form_login.login.focus();
                return false;
            }
            if (document.form_login.senha.value == "")
            {
                alert("Por favor, preencha o campo [Senha].");
                form_login.senha.focus();
                return false;
            }
            return true;
        }
    </script>
</head>
<body>
    <div id="corpo">
        <div id="topo">
            <h1>Administração do Site</h1>
        </div>
        <table width="100%" height="300" border="0" cellpadding="0" cellspacing="0">
            <tr>
                <td align="center"><table width="50%" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                            <td width="49%" align="left" valign="top" class="caixa_cinza">
                                <h1>Acesso restrito aos administradores do site</h1>
                                <p class="c_vermelho">
                                    <strong>
                                        <?php
                                        if (isset($_SESSION['mensagem_erro'])) {
                                            print $_SESSION['mensagem_erro'];
                                        }
                                        ?>
                                    </strong>
                                </p>
                                <p>&nbsp;</p>
                                <form name="form_login" method="post" action="login_usuario.php" onsubmit="return valida_form(this);">	
                                    <p><label>Login:</label><input name="login" type="text" class="caixa_texto" size="30" maxlength="30" /></p>
                                    <p><label>Senha:</label><input name="senha" type="password" class="caixa_texto" size="8" maxlength="8" /></p>
                                    <p>&nbsp;</p>
                                    <input type="image" name="imageField" src="../img/btn_continuar.gif" />
                                </form>
                            </td>
                        </tr>
                    </table></td>
            </tr>
        </table>
        <?php include "inc_rodape.php" ?>
    </div>
</body>
</html>

