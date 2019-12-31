window.addEventListener("load", function () {
    try {
        window.print();

        document.getElementById("btn_impressao").onclick = function () {
            window.print();
            return false;
        }
    } catch (e) {
        alert(e);
    }
});