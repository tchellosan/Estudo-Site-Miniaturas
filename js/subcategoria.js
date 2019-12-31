window.addEventListener("load", function () {
    try {
        document.formSubcateg.onsubmit = function () {
            if (document.getElementById("subcateg").selectedIndex == 0) {
                alert('Por favor, selecione uma subcategoria.');
                return false;
            }
            return true;
        }
    } catch (e) {
        alert(e);
    }
});