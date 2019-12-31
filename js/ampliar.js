window.addEventListener("load", function () {
    try {
        var listImg = document.querySelectorAll(".card-imagem a img");
        var listCodigo = document.querySelectorAll(".card-imagem input[name=codigo]");

        listImg.forEach(function (img, index) {
            img.onclick = function () {
                try {
                    window.open("ampliar.php?codigo=" + listCodigo[index].value + "&nome=" + this.title, "", "width=522,height=338,top=50,left=50");
                } catch (e) {
                    // Tratamento tela de detalhe.php (link Ampliar Imagem)
                    window.open("ampliar.php?codigo=" + listCodigo[index - 1].value + "&nome=" + this.title, "", "width=522,height=338,top=50,left=50");
                }
            }
        });
    } catch (e) {
        alert(e);
    }
});