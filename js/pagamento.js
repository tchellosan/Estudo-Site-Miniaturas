window.addEventListener("load", function () {
    try {
        document.getElementById('btn_boleto').onclick = function () {
            if (!verificarFormaPgtoSelecionada()) {
                return false;
            }
            if (!document.enviar_pagamento.opcao_pgto[0].checked) {
                alert("Selecione a opção Boleto Bancário.");
                return false;
            }
            return true;
        }

        document.getElementById('btn_credito').onclick = function () {
            if (!verificarFormaPgtoSelecionada()) {
                return false;
            }
            if (!document.enviar_pagamento.opcao_pgto[1].checked
                    && !document.enviar_pagamento.opcao_pgto[2].checked
                    && !document.enviar_pagamento.opcao_pgto[3].checked
                    && !document.enviar_pagamento.opcao_pgto[4].checked) {
                alert("Selecione um Cartão de Crédito.");
                return false;
            }

            var erros = "";
            var el = null;

            if (!document.enviar_pagamento.num_cartao.value) {
                erros += " - Número do cartão de crédito\n";
                if (erros && !el) {
                    el = document.enviar_pagamento.num_cartao;
                }
            }

            if (!document.enviar_pagamento.nome_cartao.value) {
                erros += " - Nome impresso no cartão de crédito\n";
                if (erros && !el) {
                    el = document.enviar_pagamento.nome_cartao;
                }
            }

            if (!document.enviar_pagamento.data_cartao_mes.value) {
                erros += " - Mês referente a data de validade do cartão de crédito\n";
                if (erros && !el) {
                    el = document.enviar_pagamento.data_cartao_mes;
                }
            } else {
                var mes = document.enviar_pagamento.data_cartao_mes.value;
                if (mes < 1 || mes > 12) {
                    erros += " - Mês informado inválido.\n";
                    if (erros && !el) {
                        el = document.enviar_pagamento.data_cartao_mes;
                        el.value = "";
                    }
                }
            }

            if (!document.enviar_pagamento.data_cartao_ano.value) {
                erros += " - Ano referente a data de validade do cartão de crédito\n";
                if (erros && !el) {
                    el = document.enviar_pagamento.data_cartao_ano;
                }
            }

            if (!document.enviar_pagamento.cod_seg.value) {
                erros += " - Código de segurança do cartão de crédito\n";
                if (erros && !el) {
                    el = document.enviar_pagamento.cod_seg;
                }
            }

            if (erros) {
                alert("Por favor, informe/corrija os seguintes dados: \n" + erros);
                el.focus();
                return false;
            }

            return true;
        }

        document.enviar_pagamento.num_cartao.onkeypress = validarTecla;
        document.enviar_pagamento.data_cartao_mes.onkeypress = validarTecla;
        document.enviar_pagamento.data_cartao_ano.onkeypress = validarTecla;
        document.enviar_pagamento.cod_seg.onkeypress = validarTecla;
        document.enviar_pagamento.nome_cartao.onkeyup = function () {
            this.value = this.value.toUpperCase();
        }

        document.enviar_pagamento.opcao_pgto.forEach(function (el, index) {
            if (index !== 0) {
                el.onchange = function () {
                    if (!document.enviar_pagamento.num_cartao.value) {
                        document.enviar_pagamento.num_cartao.focus();
                        return false;
                    }
                    if (!document.enviar_pagamento.nome_cartao.value) {
                        document.enviar_pagamento.nome_cartao.focus();
                        return false;
                    }
                    if (!document.enviar_pagamento.data_cartao_mes.value) {
                        document.enviar_pagamento.nome_cartao.focus();
                        return false;
                    }
                    if (!document.enviar_pagamento.data_cartao_ano.value) {
                        document.enviar_pagamento.nome_cartao.focus();
                        return false;
                    }
                    if (!document.enviar_pagamento.cod_seg.value) {
                        document.enviar_pagamento.nome_cartao.focus();
                        return false;
                    }
                }
            }
        });


    } catch (e) {
        alert(e);
    }
});

function verificarFormaPgtoSelecionada() {
    if (!document.enviar_pagamento.opcao_pgto[0].checked
            && !document.enviar_pagamento.opcao_pgto[1].checked
            && !document.enviar_pagamento.opcao_pgto[2].checked
            && !document.enviar_pagamento.opcao_pgto[3].checked
            && !document.enviar_pagamento.opcao_pgto[4].checked) {
        alert("Por favor, selecione uma das opções de pagamento: \n - Boleto Bancário \n - Cartão de Crédido");
        return false;
    }
    return true;
}

function validarTecla() {
    try {
        var tecla = event.which || event.keyCode;
        var isCodeBetween48_57 = (tecla >= 48 && tecla <= 57);
        if (isCodeBetween48_57) {
            return true;
        }
        return false;
    } catch (e) {
        alert(e);
    }
}