jQuery(function ($) {
    RetornaFazendas(null);
    RetornaResponsavel(null);
});
function addTableFazenda(Id, nome, responsavel) {
    var num = document.getElementById("tableFazenda").rows.length;

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
    anode = document.createTextNode(responsavel);
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

    document.getElementById("tableFazenda").appendChild(x);
}
$("#tableFazenda").on("click", ".Delet", function () {
    IdUsuario = $(this).attr('id');
    CadastraAtualizaDeletaFazendas(IdUsuario, "3");
});
$("#tableFazenda").on("click", ".Edit", function () {
    IdUsuario = $(this).attr('id');
    IdFazenda = IdUsuario;
    RetornaFazendas(IdUsuario);
});
function RetornaFazendas(Id) {
    var data = { funcao: "RetornaFazendas", Id: Id };
    ChamaAjax(data, function (Resposta) {
        console.log(Resposta);
        var Resposta = JSON.parse(Resposta);
        if (Resposta.Status == "Ok") {
            var Respostas = Resposta.Resposta;
            if (Id != null && Id != "") {
                $("#Nome").val(Respostas[0].Nome);
            } else {
                var cont = Resposta.Resposta.length;
                $('#tableFazenda tr').slice(1).remove();
                for (passo = 0; passo < cont; passo++) {
                    addTableFazenda(Respostas[passo].Id, Respostas[passo].Nome, Respostas[passo].Responsavel);
                }
            }
        } else {
            $('#tableFazenda tr').slice(1).remove();
        }
    }, function (error) {
        console.log(error);
    });
}
function RetornaResponsavel(Id) {
    var data = { funcao: "RetornaResponsavel", Id: Id };
    ChamaAjax(data, function (Resposta) {

        var Resposta = JSON.parse(Resposta);
        if (Resposta.Status == "Ok") {
            console.log(Resposta.Resposta[0]);
            var Respostas = Resposta.Resposta;
            var cont = Resposta.Resposta.length;
            for (passo = 0; passo < cont; passo++) {
                $("#Responsavel").append(new Option(Respostas[passo].Nome, Respostas[passo].Id));
            }
            $('#Responsavel').selectpicker('refresh');
        } else {
            console.log(Resposta);
        }
    }, function (error) {
        console.log(error);
    });
}
function CadastraAtualizaDeletaFazendas(Id, Acao) {

    var Nome = $("#Nome").val();
    var IdResponsavel = $("#Responsavel option:selected").val();
    if (Id != null && Id != "" && Acao != "0" && Acao != "3") {
        Acao = '2';
    }
    var data = { funcao: "CadastraAtualizaDeletaFazendas", Id: Id, Acao: Acao, IdResponsavel: IdResponsavel, Nome: Nome };
    ChamaAjax(data, function (Resposta) {
        console.log(Resposta);
        var Resposta = JSON.parse(Resposta);
        if (Resposta.Status == "Ok") {
            $("#alert").html(Resposta.Resposta);
            $("#AlertModal").modal("show");
            $("#Nome").val("");
            RetornaResponsavel(null);
            RetornaFazendas(null);
        }
    }, function (error) {
        console.log(error);
    });
}