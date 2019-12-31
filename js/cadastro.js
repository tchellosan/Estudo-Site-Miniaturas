var re_email = /^([\w-]+(\.[\w-]+)*)@(([\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(\.[a-z]{2})?)$/;
var re_senha = /^.{5,10}$/;

window.addEventListener("load", function () {
    try {
        document.cadastro_manut.onsubmit = function () {
            try {
                var erros = "";
                var el = null;

                if (!document.cadastro_manut.nome_completo.value) {
                    erros += " - Nome completo\n";
                    if (erros && !el) {
                        el = document.cadastro_manut.nome_completo;
                    }
                }

                if (!document.cadastro_manut.cpf.value) {
                    erros += " - CPF (apenas números)\n";
                    if (erros && !el) {
                        el = document.cadastro_manut.cpf;
                    }
                }

                if (!document.cadastro_manut.sexo.value) {
                    erros += " - Sexo\n";
                    if (erros && !el) {
                        el = document.cadastro_manut.sexo;
                    }
                }

                var email = document.cadastro_manut.email;
                if (!email.value) {
                    erros += " - E-mail\n";
                    if (erros && !el) {
                        el = document.cadastro_manut.email;
                    }
                } else if (!re_email.test(email.value)) {
                    erros += " - E-mail inválido\n";
                    if (erros && !el) {
                        el = document.cadastro_manut.email;
                    }
                }

                if (!document.cadastro_manut.email_conf.value) {
                    erros += " - Confirmação de e-mail\n";
                    if (erros && !el) {
                        el = document.cadastro_manut.email_conf;
                    }
                }

                var senha = document.cadastro_manut.senha;

                if (!senha.value) {
                    erros += " - Senha (mínimo de 5 caracteres e máximo de 10)\n";
                    if (erros && !el) {
                        el = document.cadastro_manut.senha;
                    }
                } else if (!re_senha.test(senha.value)) {
                    erros += " - A senha deve conter entre 5 e 10 caracteres\n";
                    if (erros && !el) {
                        el = document.cadastro_manut.senha;
                    }
                }

                if (!document.cadastro_manut.senha_conf.value) {
                    erros += " - Confirmação de senha\n";
                    if (erros && !el) {
                        el = document.cadastro_manut.senha_conf;
                    }
                }

                var cep = document.cadastro_manut.cep;
                var re_cep = /^[\d]{8}$/;
                if (!re_cep.test(cep.value)) {
                    erros += " - CEP inválido (informe 8 números sem ponto ou traço)\n";
                    if (erros && !el) {
                        el = document.cadastro_manut.cep;
                    }
                }

                if (!document.cadastro_manut.logradouro.value) {
                    erros += " - Logradouro\n";
                    if (erros && !el) {
                        el = document.cadastro_manut.logradouro;
                    }
                }

                if (!document.cadastro_manut.numero_logra.value) {
                    erros += " - Número\n";
                    if (erros && !el) {
                        el = document.cadastro_manut.numero_logra;
                    }
                }

                if (!document.cadastro_manut.bairro.value) {
                    erros += " - Bairro\n";
                    if (erros && !el) {
                        el = document.cadastro_manut.bairro;
                    }
                }

                if (!document.cadastro_manut.cidade.value) {
                    erros += " - Cidade\n";
                    if (erros && !el) {
                        el = document.cadastro_manut.cidade;
                    }
                }

                if (!document.cadastro_manut.uf.value) {
                    erros += " - UF (Unidade Federativa)\n";
                    if (erros && !el) {
                        el = document.cadastro_manut.uf;
                    }
                }

                if (erros) {
                    alert("Por favor, informe os seguintes dados: \n" + erros);
                    el.focus();
                    return false;
                }

                var erros = "";
                var el = null;
                var cpf = document.cadastro_manut.cpf.value;
                cpf = cpf.trim();
                cpf = "0".repeat(11 - cpf.length) + cpf;
                switch (cpf) {
                    case "0".repeat(11):
                    case "1".repeat(11):
                    case "2".repeat(11):
                    case "3".repeat(11):
                    case "4".repeat(11):
                    case "5".repeat(11):
                    case "6".repeat(11):
                    case "7".repeat(11):
                    case "8".repeat(11):
                    case "9".repeat(11):
                        erros += " - Número do CPF inválido\n";
                        if (erros && !el) {
                            el = document.cadastro_manut.cpf;
                        }
                        document.cadastro_manut.cpf.value = "";
                        break;
                    default:
                        var pos_comp = 9;
                        var pos_n = 9;
                        do {
                            var soma = 0;
                            for (var fator = 2; pos_n; fator++) {
                                soma += cpf.charAt(--pos_n) * fator;
                            }

                            digito = 11 - (soma % 11);
                            if (digito == 10 || digito == 11) {
                                digito = 0;
                            }

                            if (cpf.charAt(pos_comp) != digito) {
                                erros += " - Número do CPF inválido\n";
                                if (erros && !el) {
                                    el = document.cadastro_manut.cpf;
                                }
                                document.cadastro_manut.cpf.value = "";
                                break;
                            }
                            var pos_n = 10;
                        } while (++pos_comp <= 10);
                }

                if (document.cadastro_manut.email.value != document.cadastro_manut.email_conf.value) {
                    erros += " - E-mail de confirmação diferente do e-mail informado\n";
                    if (erros && !el) {
                        el = document.cadastro_manut.email_conf;
                    }
                    document.cadastro_manut.email_conf.value = "";
                }

                if (document.cadastro_manut.senha.value != document.cadastro_manut.senha_conf.value) {
                    erros += " - Senha de confirmação diferente da senha informada\n";
                    if (erros && !el) {
                        el = document.cadastro_manut.senha_conf;
                    }
                    document.cadastro_manut.senha_conf.value = "";
                }

                var faixa_cep_uf = [
                    {uf: "AC", min: "69900000", max: "69999999"},
                    {uf: "AL", min: "57000000", max: "57999999"},
                    {uf: "AP", min: "68900000", max: "68999999"},
                    {uf: "AM", min: "69000000", max: "69899999"},
                    {uf: "BA", min: "40000000", max: "48999999"},
                    {uf: "CE", min: "60000000", max: "63999999"},
                    {uf: "DF", min: "70000000", max: "73699999"},
                    {uf: "ES", min: "29000000", max: "29999999"},
                    {uf: "GO", min: "72800000", max: "76799999"},
                    {uf: "MA", min: "65000000", max: "65999999"},
                    {uf: "MT", min: "78000000", max: "78899999"},
                    {uf: "MS", min: "79000000", max: "79999999"},
                    {uf: "MG", min: "30000000", max: "39999999"},
                    {uf: "PA", min: "66000000", max: "68899999"},
                    {uf: "PB", min: "58000000", max: "58999999"},
                    {uf: "PR", min: "80000000", max: "87999999"},
                    {uf: "PE", min: "50000000", max: "56999999"},
                    {uf: "PI", min: "64000000", max: "64999999"},
                    {uf: "RJ", min: "20000000", max: "28999999"},
                    {uf: "RN", min: "59000000", max: "59999999"},
                    {uf: "RS", min: "90000000", max: "99999999"},
                    {uf: "RO", min: "78900000", max: "78999999"},
                    {uf: "RR", min: "69300000", max: "69399999"},
                    {uf: "SC", min: "88000000", max: "89999999"},
                    {uf: "SC", min: "00000000", max: "19999999"},
                    {uf: "SP", min: "01000000", max: "19999999"},
                    {uf: "SE", min: "49000000", max: "49999999"},
                    {uf: "TO", min: "77000000", max: "77999999"}
                ];

                for (var i = 0; i < faixa_cep_uf.length; i++) {
                    if (faixa_cep_uf[i]['uf'] === document.cadastro_manut.uf.value) {
                        var cep = document.cadastro_manut.cep;
                        if (cep.value < faixa_cep_uf[i]['min'] || cep.value > faixa_cep_uf[i]['max']) {
                            erros += " - CEP digitado inválido para o estado selecionado.\n";
                            if (erros && !el) {
                                el = document.cadastro_manut.cep;
                            }
                            document.cadastro_manut.cep.value = "";
                        }
                    }
                }

                if (erros) {
                    alert("Por favor, corrija os seguintes dados: \n" + erros);
                    el.focus();
                    return false;
                }
                return true;
            } catch (e) {
                alert(e);
            }
        }

        document.getElementById("cpf").onkeypress = validarTecla;
        document.getElementById("cep").onkeypress = validarTecla;
        document.getElementById("cep").onblur = obterCEP;
        document.cadastro_manut.nome_completo.focus();
    } catch (e) {
        alert(e);
    }
});

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

function obterCEP() {
    try {
        if (!this.value) {
            return false;
        }

        var re_cep = /^[\d]{8}$/;
        if (re_cep.test(this.value)) {
            requisitar("web_service/buscaCEP.php?cep=" + this.value);
        }
        return true;
    } catch (e) {
        alert(e);
    }
}

function requisitar(url) {
    try {
        var ajax = iniciaAjax();
        ajax.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                if (this.responseXML) {
                    var dados_cep = this.responseXML;
                    document.cadastro_manut.logradouro.value = dados_cep.getElementsByTagName("logradouro")[0].firstChild.nodeValue;
                    if (!document.cadastro_manut.logradouro.value) {
                        document.cadastro_manut.logradouro.removeAttribute("readonly");
                    } else {
                        document.cadastro_manut.logradouro.setAttribute("readonly", "readonly")
                    }

                    document.cadastro_manut.bairro.value = dados_cep.getElementsByTagName("bairro")[0].firstChild.nodeValue;
                    if (!document.cadastro_manut.bairro.value) {
                        document.cadastro_manut.bairro.removeAttribute("readonly");
                    } else {
                        document.cadastro_manut.bairro.setAttribute("readonly", "readonly")
                    }

                    document.cadastro_manut.cidade.value = dados_cep.getElementsByTagName("localidade")[0].firstChild.nodeValue;
                    if (!document.cadastro_manut.cidade.value) {
                        document.cadastro_manut.cidade.removeAttribute("readonly");
                    } else {
                        document.cadastro_manut.cidade.setAttribute("readonly", "readonly")
                    }

                    var uf = dados_cep.getElementsByTagName("uf")[0].firstChild.nodeValue;
                    if (uf) {
                        var optionsUF = document.cadastro_manut.uf.options;
                        var qtdeUF = optionsUF.length;
                        for (var i = 0; i < qtdeUF; i++) {
                            optionsUF[i].removeAttribute("selected");
                        }
                        optionsUF.namedItem(uf).setAttribute("selected", "selected");
                        document.cadastro_manut.uf.setAttribute("readonly", "readonly");
                    } else {
                        document.cadastro_manut.uf.removeAttribute("readonly");
                    }
                } else {
                    alert("CEP inexistente \n");
                    document.cadastro_manut.cep.value = "";
                    document.cadastro_manut.cep.focus();
                }
            }
        }
        ajax.open("GET", url, true);
        ajax.send(null);
    } catch (e) {
        alert(e);
    }
}

function iniciaAjax() {
    try {
        var ajax = null;
        if (window.XMLHttpRequest) {
            ajax = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            try {
                ajax = new ActiveXObject('Msxml2.XMLHTTP');
            } catch (e) {
                ajax = new ActiveXObject('Microsoft.XMLHTTP');
            }
        }
        return ajax;
    } catch (e) {
        alert(e);
    }
}