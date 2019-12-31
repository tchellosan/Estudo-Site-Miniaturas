window.addEventListener("load", function () {
    try {
        var btn_voltar = document.getElementById("btn_voltar");


        if (document.getElementById("erro_pgto")) {
            btn_voltar.onclick = function () {
                location.href = "pagamento.php";
                return false;
            }
        } else if (document.getElementById("erro_cad_manut")) {
            btn_voltar.onclick = function () {
                location.href = "cadastro.php";
                return false;
            }
        } else if (document.getElementById("carrinho_vazio")) {
            btn_voltar.onclick = function () {
                location.href = "index.php";
                return false;
            }
        } else {
            btn_voltar.onclick = function () {
                history.go(-1);
                return false;
            }
        }
    } catch (e) {
        alert(e);
    }
});