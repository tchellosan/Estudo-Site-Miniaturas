window.addEventListener("load", function () {
    try {
        var list_qtde = document.getElementsByName("qtde-produto");
        var list_id_item = document.getElementsByName("id-item");

        list_qtde.forEach(function (qtde, index) {
            qtde.onchange = function () {
                if (this.value < 1 || this.value > 10) {
                    alert("A quantidade permitida por produto é entre 1 e 10 unidades.");
                    this.focus();
                    return false;
                }
                location.href = "?id_item_ped=" + list_id_item[index].value + "&nova_qtde=" + this.value + "&atualizar";
                return true;
            }
        });
    } catch (e) {
        alert(e);
    }
});