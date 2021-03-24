jQuery(function($) {
    Retorna(null);
    Select();
    $('#Status').selectpicker('refresh');
    $('#Responsavel').selectpicker('refresh');
    IdFrota = "";
    Acao = "1";
});

function Retorna(Id) {
    var data = {
        funcao: "RetornaFrotas",
        Id: Id
    };
    ChamaAjax(data, function(Resposta) {
            var Resposta = JSON.parse(Resposta);
            var Respostas = Resposta.Resposta;

            if (Id == "" || Id == null) {
                if (Resposta.Status == "Ok") {
                    var cont = Resposta.Resposta.length;
                    for (passo = 0; passo < cont; passo++) {
                        addTableFrotas(
                            Respostas[passo].Id, Respostas[passo].Marca,
                            Respostas[passo].Modelo, Respostas[passo].Placa,
                            Respostas[passo].Nome
                        );
                    }
                }
            } else {
                $("#Nome").val(Respostas[0].Nome);
                $("#Marca").val(Respostas[0].Marca);
                $("#Modelo").val(Respostas[0].Modelo);
                $("#Placa").val(Respostas[0].Placa);
                $("#NumeroFrota").val(Respostas[0].NumeroFrota);
                $("#KmInicial").val(Respostas[0].KmInicial);
                $("#KmAtual").val(Respostas[0].KmAtual);
                $("#Ano").val(Respostas[0].Ano);
                $("#Responsavel").val(Respostas[0].Responsavel).change();
                $("#Status").val(Respostas[0].Status).change();
            }
        },
        function(error) {
            console.log(error);
        });
}

function Select() {
    var data = {
        funcao: "PegaResponsavel",
    };
    ChamaAjax(data, function(Resposta) {
        var Resposta = JSON.parse(Resposta);

        if (Resposta.Status == "Ok") {
            var Respostas = Resposta.Resposta;
            var cont = Resposta.Resposta.length;
            for (passo = 0; passo < cont; passo++) {
                $("#Responsavel").append(new Option(Respostas[passo].Nome, Respostas[passo].Id));
            }
            $('#Responsavel').selectpicker('refresh');
        }
    }, function(error) {
        console.log(error);
    });
}

function addTableFrotas(Id, Marca, Modelo, Placa, Nome) {
    var num = document.getElementById("tableProdutos").rows.length;

    var x = document.createElement("tr");

    var a = document.createElement("td");
    var anode = document.createTextNode(Id);
    a.appendChild(anode);
    x.appendChild(a);

    a = document.createElement("td");
    anode = document.createTextNode(Marca);
    a.appendChild(anode);
    x.appendChild(a);

    a = document.createElement("td");
    anode = document.createTextNode(Nome);
    a.appendChild(anode);
    x.appendChild(a);

    a = document.createElement("td");
    anode = document.createTextNode(Modelo);
    a.appendChild(anode);
    x.appendChild(a);

    a = document.createElement("td");
    anode = document.createTextNode(Placa);
    a.appendChild(anode);
    x.appendChild(a);

    a = document.createElement("td");
    anode = document.createElement('i');
    anode.setAttribute("id", Id);
    anode.setAttribute("class", "fa fa-edit Edit");
    anode.setAttribute("style", "cursor:pointer");
    a.appendChild(anode);
    x.appendChild(a);

    a = document.createElement("td");
    anode = document.createElement('i');
    anode.setAttribute("id", Id);
    anode.setAttribute("class", "fa fa-close Delet");
    anode.setAttribute("style", "cursor:pointer");
    a.appendChild(anode);
    x.appendChild(a);

    document.getElementById("tableProdutos").appendChild(x);
}

function CadastraAtualizaDeletaFrota(Acao, Id) {
    var data = {
        funcao: "CadastraAtualizaDeletaFrota",
        Acao: Acao,
        Id: Id,
        Nome: $("#Nome").val(),
        Marca: $("#Marca").val(),
        Modelo: $("#Modelo").val(),
        Placa: $("#Placa").val(),
        Ano: $("#Ano").val(),
        NumeroFrota: $("#NumeroFrota").val(),
        KmInicial: $("#KmInicial").val(),
        Responsavel: $("#Responsavel option:selected").val(),
        Status: $("#Status option:selected").val(),
        KmAtual: $("#KmAtual").val()
    };

    ChamaAjax(data, function(rsp) {
        var Resposta = JSON.parse(rsp);

        if (Resposta.Status == "Ok") {
            $("#alert").html(Resposta.Resposta);
            $("#AlertModal").modal("show");

            $("#Nome").val("");
            $("#Marca").val("");
            $("#Modelo").val("");
            $("#Placa").val("");
            $("#NumeroFrota").val("");
            $("#KmInicial").val("");
            $("#KmAtual").val("");
            $("#Responsavel").val("").change();
            $("#Ano").val("").change();
            $("#Status").val("").change();
            $('#Status').selectpicker('refresh');
            $('#Responsavel').selectpicker('refresh');

            IdFrota = "";
            $('#tableProdutos tr').slice(1).remove();
            Retorna(null);
        } else {
            $("#alert").html(Resposta.Resposta);
            $("#AlertModal").modal("show");
        }
    }, function(error) {
        console.log(error);
    });
}
$("#tableProdutos").on("click", ".Edit", function() {
    IdFrota = $(this).attr('id');
    Acao = "2";
    Retorna(IdFrota);
});

$("#tableProdutos").on("click", ".Delet", function() {
    IdFrota = $(this).attr('id');
    Acao = "3";
    CadastraAtualizaDeletaFrota(Acao, IdFrota);
});

$('#Placa').mask('AAA-0000', {
    reverse: false
});