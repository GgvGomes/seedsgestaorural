jQuery(function ($) {
    $('#Talhao').selectpicker('refresh');
    $('#Fazenda').selectpicker('refresh');
    $('#TipoUnidade').selectpicker('refresh');
    $('#TipoUnidadeSaida').selectpicker('refresh');
    CarregaCategorias();
    CarregaTableUsCarregaProdutos(null);
    CarregaTableUsCarregaProdutosSaida(null);
});

function CadastraAtualizaDeletaProdutos(Acao, Id) {

    var Nome = $("#Nome").val();
    var Quantidade = $("#Quantidade").val();
    var NewCategoria = $("#NewCategoria").val();
    var Valor = $("#Valor").val();
    var CategoriaSelect = $("#CategoriaSelect option:selected").val();
    var TipoUnidade = $("#TipoUnidade option:selected").val();
    if (Id != null && Id != "" && Acao != "3") {
        Acao = '2';
    }
    var data = {
        funcao: "CadastraAtualizaDeletaProdutos", Acao: Acao, Id: Id,
        Quantidade: Quantidade, NewCategoria: NewCategoria, Valor: Valor,
        CategoriaSelect: CategoriaSelect, TipoUnidade: TipoUnidade, Nome: Nome
    };

    ChamaAjax(data, function (rsp) {

        var Resposta = JSON.parse(rsp);
        console.log(Resposta);
        if (Resposta.Status == "Ok") {
            $("#alert").html(Resposta.Resposta);
            $("#AlertModal").modal("show");
            $("#Nome").val("");
            CarregaTableUsCarregaProdutos(null);
            CarregaCategorias(null);
        } else {
            $("#alert").html(Resposta.Resposta);
            $("#AlertModal").modal("show");
        }
    }, function (error) {
        console.log(error);
    });
}
function SaidaDeEstoque(Id) {

    var Quantidade = $("#QuantidadeSaida").val();
    var Talhao = $("#Talhao option:selected").val();

    var data = {
        funcao: "SaidaDeEstoque", Id: Id, Quantidade: Quantidade, Talhao: Talhao
    };

    ChamaAjax(data, function (rsp) {

        var Resposta = JSON.parse(rsp);
        if (Resposta.Status == "Ok") {
            $("#alert").html(Resposta.Resposta);
            $("#AlertModal").modal("show");
            $("#QuantidadeSaida").val("");
            $("#NomeSaida").val("");
            $("#CategoriaSelectSaida").val("").change();
            $("#TipoUnidadeSaida").val("").change();
            CarregaTableUsCarregaProdutosSaida(null);
            CarregaCategorias();
        } else {
            $("#alert").html(Resposta.Resposta);
            $("#AlertModal").modal("show");
        }
    }, function (error) {
        console.log(error);
    });
}

function addTableUsuariosProdutos(Id, Nome, Categoria) {
    var num = document.getElementById("tableProdutos").rows.length;

    var x = document.createElement("tr");

    var a = document.createElement("td");
    var anode = document.createTextNode(Id);
    a.appendChild(anode);
    x.appendChild(a);

    a = document.createElement("td");
    anode = document.createTextNode(Nome);
    a.appendChild(anode);
    x.appendChild(a);

    a = document.createElement("td");
    anode = document.createTextNode(Categoria);
    a.appendChild(anode);
    x.appendChild(a);

    a = document.createElement("td");
    anode = document.createElement('i');
    anode.setAttribute("id", Id);
    anode.setAttribute("class", "fa fa-edit Editar");
    anode.setAttribute("style", "cursor:pointer");
    a.appendChild(anode);
    x.appendChild(a);

    a = document.createElement("td");
    anode = document.createElement('i');
    anode.setAttribute("id", Id);
    anode.setAttribute("class", "fa fa-clone  Clona");
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

function addTableUsuariosProdutosSaida(Id, Nome, Categoria, TipoUnidade, Quantidade) {
    var num = document.getElementById("tableProdutosSaida").rows.length;

    var x = document.createElement("tr");

    var a = document.createElement("td");
    var anode = document.createTextNode(Id);
    a.appendChild(anode);
    x.appendChild(a);

    a = document.createElement("td");
    anode = document.createTextNode(Nome);
    a.appendChild(anode);
    x.appendChild(a);

    a = document.createElement("td");
    anode = document.createTextNode(Categoria);
    a.appendChild(anode);
    x.appendChild(a);

    a = document.createElement("td");
    anode = document.createTextNode(TipoUnidade);
    a.appendChild(anode);
    x.appendChild(a);

    a = document.createElement("td");
    anode = document.createTextNode(Quantidade);
    a.appendChild(anode);
    x.appendChild(a);

    a = document.createElement("td");
    anode = document.createElement('i');
    anode.setAttribute("id", Id);
    anode.setAttribute("class", "fa fa-edit Editar");
    anode.setAttribute("style", "cursor:pointer");
    a.appendChild(anode);
    x.appendChild(a);

    document.getElementById("tableProdutosSaida").appendChild(x);
}

$("#tableProdutos").on("click", ".Clona", function () {
    IdProduto = $(this).attr('id');
    CarregaTableUsCarregaProdutos(IdProduto);
    IdProduto = "";
});

$("#tableProdutos").on("click", ".Editar", function () {
    IdProduto = $(this).attr('id');
    CarregaTableUsCarregaProdutos(IdProduto);
});
$("#tableProdutosSaida").on("click", ".Editar", function () {
    IdProdutoSaida = $(this).attr('id');
    CarregaTableUsCarregaProdutosSaida(IdProdutoSaida);
});

$("#tableProdutos").on("click", ".Delet", function () {
    IdProduto = $(this).attr('id');
    CadastraAtualizaDeletaProdutos("3", IdProduto);
    IdProduto = "";
});

function CarregaTableUsCarregaProdutos(Id) {
    var data = { funcao: "RetornaProdutos", Id: Id };
    ChamaAjax(data, function (Resposta) {

        var Resposta = JSON.parse(Resposta);
        if (Resposta.Status == "Ok") {

            var Respostas = Resposta.Resposta;
            if (Id == null || Id == "") {
                var cont = Resposta.Resposta.length;
                $('#tableProdutos tr').slice(1).remove();
                for (passo = 0; passo < cont; passo++) {
                    addTableUsuariosProdutos(Respostas[passo].Id, Respostas[passo].Nome, Respostas[passo].Categoria);

                }
            } else {

                $("#Nome").val(Respostas[0].Nome);
                $("#Quantidade").val(Respostas[0].Quantidade);
                $("#Valor").val(Respostas[0].Valor);
                $("#CategoriaSelect").val(Respostas[0].IdCategoria).change();
                $("#TipoUnidade").val(Respostas[0].TipoUnidade).change();
                $('#CategoriaSelect').selectpicker('refresh');
                $('#TipoUnidade').selectpicker('refresh');
            }

        } else {
            $('#tableProdutos tr').slice(1).remove();
        }
    }, function (error) {
        console.log(error);
    });
}

function CarregaTableUsCarregaProdutosSaida(Id) {
    var data = { funcao: "RetornaProdutosSaida", Id: Id };
    ChamaAjax(data, function (Resposta) {
        console.log(Resposta);
        var Resposta = JSON.parse(Resposta);
        if (Resposta.Status == "Ok") {

            var Respostas = Resposta.Resposta;
            if (Id == null || Id == "") {
                var cont = Resposta.Resposta.length;
                $('#tableProdutosSaida tr').slice(1).remove();
                for (passo = 0; passo < cont; passo++) {
                    addTableUsuariosProdutosSaida(
                        Respostas[passo].Id,
                        Respostas[passo].Nome,
                        Respostas[passo].Categoria,
                        Respostas[passo].TipoUnidade,
                        Respostas[passo].Quantidade
                    );
                }
            } else {

                $("#NomeSaida").val(Respostas[0].Nome);
                $("#QuantidadeSaida").val(Respostas[0].Quantidade);
                $("#CategoriaSelectSaida").val(Respostas[0].IdCategoria).change();
                $("#TipoUnidadeSaida").val(Respostas[0].TipoUnidade).change();
                $('#CategoriaSelectSaida').selectpicker('refresh');
                $('#TipoUnidadeSaida').selectpicker('refresh');
            }

        } else {
            $('#tableProdutosSaida tr').slice(1).remove();
        }
    }, function (error) {
        console.log(error);
    });
}

function CarregaCategorias() {
    var data = { funcao: "RetornaCategorias" };
    ChamaAjax(data, function (Resposta) {
        var Resposta = JSON.parse(Resposta);
        if (Resposta.Status == "Ok") {

            var Respostas = Resposta.Resposta;
            var cont = Resposta.Resposta.length;
            for (passo = 0; passo < cont; passo++) {

                $("#CategoriaSelect").append(new Option(Respostas[passo].Nome, Respostas[passo].Id));
                $("#CategoriaSelectSaida").append(new Option(Respostas[passo].Nome, Respostas[passo].Id));
            }
            $('#CategoriaSelect').selectpicker('refresh');
            $('#CategoriaSelectSaida').selectpicker('refresh');
        } else {
            $('#CategoriaSelect').selectpicker('refresh');
            $('#CategoriaSelectSaida').selectpicker('refresh');
        }
    }, function (error) {
        console.log(error);
    });
}