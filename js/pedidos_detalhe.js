window.addEventListener("load", function () {
    try {
        var btn_voltar = document.getElementById("btn_voltar");

        btn_voltar.onclick = function () {
            history.go(-1);
            return false;
        }
    } catch (e) {
        alert(e);
    }
});