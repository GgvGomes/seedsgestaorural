jQuery(function ($) {
    $('#Permissao').selectpicker('refresh');
    $('#Fazenda').selectpicker('refresh');
    RetornaFazendas(null);
    RetornaResponsavel(null);
    CarregaTableUsuarios(null);
    CarregaTableUsCarregaUsuariosFazendasuarios();
});
function RetornaFazendas(Id) {
    var data = { funcao: "RetornaFazendas", Id: Id };
    ChamaAjax(data, function (Resposta) {
        var Resposta = JSON.parse(Resposta);
        if (Resposta.Status == "Ok") {
            var Respostas = Resposta.Resposta;
            var cont = Resposta.Resposta.length;
            for (passo = 0; passo < cont; passo++) {
                $("#FazendaVinculo").append(new Option(Respostas[passo].Nome, Respostas[passo].Id));
                $("#Fazenda").append(new Option(Respostas[passo].Nome, Respostas[passo].Id));
            }
            $('#FazendaVinculo').selectpicker('refresh');
            $('#Fazenda').selectpicker('refresh');
        } else {
            $('#FazendaVinculo').selectpicker('refresh');
            $('#Fazenda').selectpicker('refresh');
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
function CadastraAtualizaDeletaUsuarios(Id, Acao) {

    var Nome = $("#Nome").val();
    var IdResponsavel = $("#Responsavel option:selected").val();
    var Permissao = $("#Permissao option:selected").val();
    var Fazenda = $("#Fazenda option:selected").val();
    if (Id != null && Id != "" && Acao != "0" && Acao != "3") {
        Acao = '2';
    }
    var data = { funcao: "CadastraAtualizaDeletaUsuarios", Id: Id, Acao: Acao, Fazenda: Fazenda, Permissao: Permissao, IdResponsavel: IdResponsavel, Nome: Nome };

    ChamaAjax(data, function (Resposta) {

        var Resposta = JSON.parse(Resposta);
        if (Resposta.Status == "Ok") {
            $("#alert").html(Resposta.Resposta);
            $("#AlertModal").modal("show");
            $("#Nome").val("");
            RetornaResponsavel(null);
            RetornaFazendas(null);
            CarregaTableUsuarios(null);
        }
    }, function (error) {
        console.log(error);
    });
}
function UsuarioFazenda(Id, Acao) {
    var UserName = $("#UserName option:selected").val();
    var FazendaVinculo = $("#FazendaVinculo option:selected").val();

    var data = { funcao: "UsuarioFazenda", Acao: Acao, UserId: UserName, FazendaId: FazendaVinculo, Id: Id };
    console.log(data);
    ChamaAjax(data, function (Resposta) {
        console.log(Resposta);
        var Resposta = JSON.parse(Resposta);
        if (Resposta.Status == "Ok") {
            $("#alert").html(Resposta.Resposta);
            $("#AlertModal").modal("show");

            CarregaTableUsCarregaUsuariosFazendasuarios();
        }
    }, function (error) {
        console.log(error);
    });
}
function CarregaTableUsuarios(Id) {
    var data = { funcao: "CarregaUsuarios", Id: Id };
    ChamaAjax(data, function (Resposta) {
        var Resposta = JSON.parse(Resposta);
        if (Resposta.Status == "Ok") {
            console.log(Resposta);
            var Respostas = Resposta.Resposta;
            if (Id != null && Id != "") {
                $("#Responsavel").val(Respostas[0].Responsavel_Id).change();
                $("#Permissao").val(Respostas[0].Permissao).change();
                $("#Nome").val(Respostas[0].Nome);
                $('#Responsavel').selectpicker('refresh');
                $('#Permissao').selectpicker('refresh');
            } else {
                var cont = Resposta.Resposta.length;
                $('#tableUsuarios tr').slice(1).remove();
                for (passo = 0; passo < cont; passo++) {
                    $("#UserName").append(new Option(Respostas[passo].Nome, Respostas[passo].Id));
                    addTableUsuarios(Respostas[passo].Id, Respostas[passo].Nome);
                }
                $('#UserName').selectpicker('refresh');

            }
        }
    }, function (error) {
        console.log(error);
    });
}
function CarregaTableUsCarregaUsuariosFazendasuarios() {
    var data = { funcao: "CarregaUsuariosFazendas" };
    ChamaAjax(data, function (Resposta) {
        var Resposta = JSON.parse(Resposta);
        if (Resposta.Status == "Ok") {
            console.log(Resposta);
            var Respostas = Resposta.Resposta;
            var cont = Resposta.Resposta.length;
            $('#tableVinculos tr').slice(1).remove();
            for (passo = 0; passo < cont; passo++) {

                addTableUsuariosFazendas(Respostas[passo].Id, Respostas[passo].Username, Respostas[passo].Fazenda);
            }

        }else{
            $('#tableVinculos tr').slice(1).remove();
        }
    }, function (error) {
        console.log(error);
    });
}
function addTableUsuarios(Id, nome) {
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
function addTableUsuariosFazendas(Id, Usuario, Fazenda) {
    var num = document.getElementById("tableVinculos").rows.length;

    var x = document.createElement("tr");

    var a = document.createElement("td");
    var anode = document.createTextNode(Id);
    a.appendChild(anode);
    x.appendChild(a);

    a = document.createElement("td");
    anode = document.createTextNode(Usuario);
    a.appendChild(anode);
    x.appendChild(a);

    a = document.createElement("td");
    anode = document.createTextNode(Fazenda);
    a.appendChild(anode);
    x.appendChild(a);

    a = document.createElement("td");
    anode = document.createElement('i');
    anode.setAttribute("id", Id);
    anode.setAttribute("class", "fa fa-close Delet");
    anode.setAttribute("style", "cursor:pointer");
    a.appendChild(anode);
    x.appendChild(a);

    document.getElementById("tableVinculos").appendChild(x);
}
$("#tableVinculos").on("click", ".Delet", function () {
    IdUsuario = $(this).attr('id');
    UsuarioFazenda(IdUsuario, "3");
});
$("#tableUsuarios").on("click", ".Delet", function () {
    IdUsuario = $(this).attr('id');
    CadastraAtualizaDeletaUsuarios(IdUsuario, "3");
});
$("#tableUsuarios").on("click", ".Edit", function () {
    IdUsuario = $(this).attr('id');
    IdResponsavel = IdUsuario;
    CarregaTableUsuarios(IdUsuario)
});
$("#ChamaVinculo").click(function () {
    if ($(".DivVinculo").is(":visible")) {
        $(".DivVinculo").hide();
    } else {
        $(".DivVinculo").show();
    }
});
$("#ChamaUsuarios").click(function () {
    if ($(".DivUsuarios").is(":visible")) {
        $(".DivUsuarios").hide();
    } else {
        $(".DivUsuarios").show();
    }
});
$(".DivVinculo").hide();
$(".DivUsuarios").hide();