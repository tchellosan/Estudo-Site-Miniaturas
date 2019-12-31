var re_email = /^([\w-]+(\.[\w-]+)*)@(([\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(\.[a-z]{2})?)$/;
var re_senha = /^.{5,10}$/;

window.addEventListener("load", function () {
    try {
        document.form_login_senha.onsubmit = function () {
            var email = document.form_login_senha.email;
            if (!email.value) {
                alert("Por favor, informe o seu e-mail.");
                document.form_login_senha.email.focus();
                return false;
            }
            if (!re_email.test(email.value)) {
                alert("Por favor, informe um e-mail válido.");
                email.value = "";
                document.form_login_senha.email.focus();
                return false;
            }
            return true;
        }
        document.form_login_senha.email.focus();
    } catch (e) {
        alert(e);
    }
});