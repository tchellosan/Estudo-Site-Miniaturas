var re_email = /^([\w-]+(\.[\w-]+)*)@(([\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(\.[a-z]{2})?)$/;
var re_senha = /^.{5,10}$/;

window.addEventListener("load", function () {
    try {
        document.form_login_acesso.onsubmit = function () {
            var email = document.form_login_acesso.email;
            if (!email.value) {
                alert("Por favor, informe o seu e-mail.");
                document.form_login_acesso.email.focus();
                return false;
            }
            if (!re_email.test(email.value)) {
                alert("Por favor, informe um e-mail válido.");
                email.value = "";
                document.form_login_acesso.email.focus();
                return false;
            }
            if (!document.form_login_acesso.senha.value) {
                alert("Por favor, informe a sua senha.");
                document.form_login_acesso.senha.focus();
                return false;
            }
            var senha = document.form_login_acesso.senha;
            if (!re_senha.test(senha.value)) {
                alert("A senha deve conter entre 5 e 10 caracteres");
                document.form_login_acesso.senha.focus();
                return false;
            }
            return true;
        }
        document.form_cadastro.onsubmit = function () {
            var email = document.form_cadastro.email;

            if (!email.value) {
                alert("Por favor, informe o seu e-mail.");
                document.form_cadastro.email.focus();
                return false;
            }
            if (!re_email.test(email.value)) {
                alert("Por favor, informe um e-mail válido.");
                email.value = "";
                document.form_cadastro.email.focus();
                return false;
            }
            return true;
        }
        document.form_login_acesso.email.focus();
    } catch (e) {
        alert(e);
    }
});