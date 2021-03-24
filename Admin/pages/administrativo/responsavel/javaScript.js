jQuery(function ($) {
    $("#Cpf").mask("000.000.000-00");
    $("#Celular").mask("(00) 0 0000-0000");
    $("#Cep").mask("00000-000");
    $(".DivLincencas").hide();
    $(".DivUsuarios").hide();
    CarregaTableLicencas(null);
    RetornaResponsavel(null);
    limpa_formulário_cep();
    CarregaCidades(null, null);
    CarregaEstados();

    $("#Cep").blur(function () {
        //Nova variável "cep" somente com dígitos.
        var cep = $(this).val().replace(/\D/g, '');

        //Verifica se campo cep possui valor informado.
        if (cep != "") {

            //Expressão regular para validar o CEP.
            var validacep = /^[0-9]{8}$/;

            //Valida o formato do CEP.
            if (validacep.test(cep)) {

                //Preenche os campos com "..." enquanto consulta webservice.
                $("#Endereco").val("...");
                $("#Bairro").val("...");
                $("#Cidade").val("...");
                $("#Uf").val("...");
                // $("#Ibge").val("...");

                //Consulta o webservice viacep.com.br/
                $.getJSON("https://viacep.com.br/ws/" + cep + "/json/?callback=?", function (dados) {

                    if (!("erro" in dados)) {
                        //Atualiza os campos com os valores da consulta.
                        $("#Endereco").val(dados.logradouro);
                        $("#Bairro").val(dados.bairro);
                        $("#Uf").val(dados.uf).change();
                        CarregaCidades(dados.uf, dados.localidade);


                    } //end if.
                    else {
                        //CEP pesquisado não foi encontrado.
                        limpa_formulário_cep();
                        alert("CEP não encontrado.");
                    }
                });
            } //end if.
            else {
                //cep é inválido.
                limpa_formulário_cep();
                alert("Formato de CEP inválido.");
            }
        } //end if.
        else {
            //cep sem valor, limpa formulário.
            limpa_formulário_cep();
        }
    });
    $('#Cidade').selectpicker('refresh');
});
$("#Uf").change(function () {
    CarregaCidades($(this).val(), null);
});
function CarregaEstados() {
    var data = { funcao: "RetornaEstados" };
    ChamaAjax(data, function (Resposta) {

        var Resposta = JSON.parse(Resposta);

        if (Resposta.Status == "Ok") {
            var Respostas = Resposta.Resposta;
            var cont = Resposta.Resposta.length;
            for (passo = 0; passo < cont; passo++) {
                $("#Uf").append(new Option(Respostas[passo].Uf, Respostas[passo].Uf));
            }
            $('#Uf').selectpicker('refresh');

        }
    }, function (error) {
        console.log(error);
    });
}
function CarregaCidades(Uf, Cidade) {
    var data = { funcao: "RetornaMunicipios", Uf: Uf };
    ChamaAjax(data, function (Resposta) {
        var Resposta = JSON.parse(Resposta);
        if (Resposta.Status == "Ok") {
            var Respostas = Resposta.Resposta;
            var cont = Resposta.Resposta.length;
            $("#Cidade").empty();
            for (passo = 0; passo < cont; passo++) {
                $("#Cidade").append(new Option(Respostas[passo].Nome, Respostas[passo].Nome));
            }
            $('#Cidade').selectpicker('refresh');
            if (Cidade != null && Cidade != "") {
                $("#Cidade").val(Cidade).change();
            }
        }
    }, function (error) {
        console.log(error);
    });
}
function limpa_formulário_cep() {
    // Limpa valores do formulário de cep.
    $("#rua").val("");
    $("#bairro").val("");
    $("#cidade").val("");
    $("#uf").val("");
    $("#ibge").val("");
}
function addTableLicencas(Id, nome) {
    var num = document.getElementById("tableLincencas").rows.length;

    var x = document.createElement("tr");

    var a = document.createElement("td");
    var anode = document.createTextNode(Id);
    a.appendChild(anode);
    x.appendChild(a);

    a = document.createElement("td");
    anode = document.createTextNode(nome);
    a.appendChild(anode);
    x.appendChild(a);

    a = document.createElement("td");
    anode = document.createElement('i');
    anode.setAttribute("id", Id);
    anode.setAttribute("class", "fa fa-close DeletLicencia");
    anode.setAttribute("style", "cursor:pointer");
    a.appendChild(anode);
    x.appendChild(a);

    a = document.createElement("td");
    anode = document.createElement('i');
    anode.setAttribute("id", Id);
    anode.setAttribute("class", "fa fa-edit EditLicencia");
    anode.setAttribute("style", "cursor:pointer");
    a.appendChild(anode);
    x.appendChild(a);

    document.getElementById("tableLincencas").appendChild(x);
}
function addTableResponsavel(Id, nome, status) {
    var num = document.getElementById("tableUsuarios").rows.length;

    var x = document.createElement("tr");

    var a = document.createElement("td");
    var anode = document.createTextNode(Id);
    a.appendChild(anode);
    x.appendChild(a);

    a = document.createElement("td");
    anode = document.createTextNode(nome);
    a.appendChild(anode);
    x.appendChild(a);

    a = document.createElement("td");
    anode = document.createTextNode(status);
    a.appendChild(anode);
    x.appendChild(a);

    a = document.createElement("td");
    anode = document.createElement('i');
    anode.setAttribute("id", Id);
    anode.setAttribute("class", "fa fa-close Delet");
    anode.setAttribute("style", "cursor:pointer");
    a.appendChild(anode);
    x.appendChild(a);

    a = document.createElement("td");
    anode = document.createElement('i');
    anode.setAttribute("id", Id);
    anode.setAttribute("class", "fa fa-edit Edit");
    anode.setAttribute("style", "cursor:pointer");
    a.appendChild(anode);
    x.appendChild(a);

    document.getElementById("tableUsuarios").appendChild(x);
}
function CarregaTableLicencas(Id) {
    var data = { funcao: "RetornaLicenca", Id: Id };
    ChamaAjax(data, function (Resposta) {
        var Resposta = JSON.parse(Resposta);
        if (Resposta.Status == "Ok") {
            var Respostas = Resposta.Resposta;
            if (Id != null && Id != "") {
                $("#Usuarios").val(Respostas[0].Usuarios);
                $("#Fazendas").val(Respostas[0].Fazendas);
                $("#Nome").val(Respostas[0].Nome);
            } else {
                var cont = Resposta.Resposta.length;
                $('#tableLincencas tr').slice(1).remove();
                for (passo = 0; passo < cont; passo++) {
                    $("#Licenca").append(new Option(Respostas[passo].Nome, Respostas[passo].Id));
                    addTableLicencas(Respostas[passo].Id, Respostas[passo].Nome);
                }
                $('#Licenca').selectpicker('refresh');
            }
        }
    }, function (error) {
        console.log(error);
    });
}
function RetornaResponsavel(Id) {
    var data = { funcao: "RetornaResponsavel", Id: Id };
    ChamaAjax(data, function (Resposta) {
        console.log(Resposta);
        var Resposta = JSON.parse(Resposta);
        if (Resposta.Status == "Ok") {
            var Respostas = Resposta.Resposta;
            if (Id != null && Id != "") {
                $("#Username").val(Respostas[0].UserName);
                $("#NomeResponsavel").val(Respostas[0].Nome);
                $("#Cpf").val(Respostas[0].CPF);
                $("#Email").val(Respostas[0].Email);
                $("#Celular").val(Respostas[0].Celular);
                $("#Cep").val(Respostas[0].CEP);
                $("#Endereco").val(Respostas[0].Endereco);
                $("#Numero").val(Respostas[0].Numero);
                $("#Complemento").val(Respostas[0].Complemento);
                $("#Bairro").val(Respostas[0].Bairro);
                $("#Uf").val(Respostas[0].UF).change();
                CarregaCidades(Respostas[0].UF, Respostas[0].Cidade)
                $("#Licenca").val(Respostas[0].Lincenca).change();

                if (Respostas[0].Status == "0") {
                    $("#Status").prop('checked', true);
                }
            } else {
                var cont = Resposta.Resposta.length;
                $('#tableUsuarios tr').slice(1).remove();
                for (passo = 0; passo < cont; passo++) {
                    addTableResponsavel(
                        Respostas[passo].Id,
                        Respostas[passo].Nome,
                        Respostas[0].Pagamentos[0].Status,
                        Respostas[0].Pagamentos[0].UrlBoleto
                    );
                }
                $('#Licenca').selectpicker('refresh');
            }
        } else {
            $('#tableUsuarios tr').slice(1).remove();
        }
    }, function (error) {
        console.log(error);
    });
}
$("#tableLincencas").on("click", ".DeletLicencia", function () {
    IdUsuario = $(this).attr('id');
    salvaAtualizaDeletaLicenca(IdUsuario, "0");
});
$("#tableLincencas").on("click", ".EditLicencia", function () {
    IdUsuario = $(this).attr('id');
    IdLicenca = IdUsuario;
    CarregaTableLicencas(IdUsuario);
});
$("#tableUsuarios").on("click", ".Delet", function () {
    IdUsuario = $(this).attr('id');
    salvaAtualizaDeletaResponsavel(IdUsuario, "3");
});
$("#tableUsuarios").on("click", ".Edit", function () {
    IdUsuario = $(this).attr('id');
    IdResponsavel = IdUsuario;
    RetornaResponsavel(IdUsuario)
});
function salvaAtualizaDeletaLicenca(Id, Acao) {
    var Usuarios = $("#Usuarios").val();
    var Fazendas = $("#Fazendas").val();
    var Nome = $("#Nome").val();
    if (Id != null && Id != "" && Acao != "0") {
        Acao = '2';
    }
    var data = { funcao: "CadastraAtualizaDeletaLicenca", Id: Id, Acao: Acao, Usuarios: Usuarios, Nome: Nome, Fazendas: Fazendas };
    ChamaAjax(data, function (Resposta) {
        var Resposta = JSON.parse(Resposta);
        if (Resposta.Status == "Ok") {
            $("#Usuarios").val("");
            $("#Fazendas").val("");
            $("#Nome").val("");
            CarregaTableLicencas(null);
            alert(Resposta.Resposta);
        }
    }, function (error) {
        console.log(error);
    });
}
function salvaAtualizaDeletaResponsavel(Id, Acao) {
    if ($("#Status").is(":checked") == true) {
        var Status = '0';
    } else {
        var Status = '1';
    }
    var Username = $("#Username").val();
    var Nome = $("#NomeResponsavel").val();
    var Cpf = $("#Cpf").val();
    var Email = $("#Email").val();
    var Celular = $("#Celular").val();
    var Cep = $("#Cep").val();
    var Endereco = $("#Endereco").val();
    var Numero = $("#Numero").val();
    var Complemento = $("#Complemento").val();
    var Bairro = $("#Bairro").val();
    var UF = $("#Uf option:selected").val();
    var Cidade = $("#Cidade").val();
    var Licenca = $("#Licenca option:selected").val();
    if (Id != null && Id != "" && Acao != "3") {
        Acao = '2';
    }
    var data = {
        funcao: "CadastraAtualizaDeletaReponsavel", Id: Id, Acao: Acao, Username: Username, Nome: Nome,
        Cpf: Cpf, Email: Email, Celular: Celular, Cep: Cep, Endereco: Endereco, Numero: Numero, Complemento: Complemento,
        Bairro: Bairro, UF: UF, Cidade: Cidade, Licenca: Licenca, Status: Status
    };

    ChamaAjax(data, function (Resposta) {
        console.log(Resposta);
        var Resposta = JSON.parse(Resposta);
        if (Resposta.Status == "Ok") {
            RetornaResponsavel(null);
            $("#Username").val("");
            $("#NomeResponsavel").val("");
            $("#Cpf").val("");
            $("#Email").val("");
            $("#Celular").val("");
            $("#Cep").val("");
            $("#Endereco").val("");
            $("#Numero").val("");
            $("#Complemento").val("");
            $("#Bairro").val("");
            $("#Uf").val("");
            $("#Cidade").val("");
            $("#Licenca").val("");
            alert(Resposta.Resposta);
        } else {
            alert(Resposta.Resposta);
        }
    }, function (error) {
        console.log(error);
    });
}
$("#ChamaLicencas").click(function () {
    if ($(".DivLincencas").is(":visible")) {
        $(".DivLincencas").hide();
    } else {
        $(".DivLincencas").show();
    }
});
$("#ChamaUsuarios").click(function () {
    if ($(".DivUsuarios").is(":visible")) {
        $(".DivUsuarios").hide();
    } else {
        $(".DivUsuarios").show();
    }
});