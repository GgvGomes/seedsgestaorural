jQuery(function ($) {
    RetornaReset(null);
});
function addTableFazenda(Id, Usuario, Data) {
    var num = document.getElementById("tableCliclo").rows.length;

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


    document.getElementById("tableCliclo").appendChild(x);
}
function RetornaReset(Id) {
    var data = { funcao: "RetornaReset", Id: Id };
    ChamaAjax(data, function (Resposta) {
        var Resposta = JSON.parse(Resposta);
        if (Resposta.Status == "Ok") {
            var Respostas = Resposta.Resposta;
            if (Id != null && Id != "") {
                $("#Nome").val(Respostas[0].Nome);
            } else {
                var cont = Resposta.Resposta.length;
                $('#tableCliclo tr').slice(1).remove();
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
function ResetCiclo(Id, Acao) {

    var Senha = $("#Senha").val();

    var data = { funcao: "ResetCiclo" };
    ChamaAjax(data, function (Resposta) {
        console.log(Resposta);
        var Resposta = JSON.parse(Resposta);
        if (Resposta.Status == "Ok") {
            $("#alert").html(Resposta.Resposta);
            $("#AlertModal").modal("show");
            $("#Senha").val("");
            RetornaReset(null);
        }
    }, function (error) {
        console.log(error);
    });
}