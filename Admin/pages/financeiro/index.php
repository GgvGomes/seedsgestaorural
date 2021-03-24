<?php
include '../../includes/conexoes/index.php';
?>
<script>
    var IdProduto = "";
</script>
<div class="col-lg-12" style="background-color: white;margin-bottom: 2%;">
    <i class="fa fa-angle-double-down" style="font-size:36px;float: right;" id="ChamaCategoria"></i>
    <h4 class="text-center">Produtos</h4>
    <hr>
    <div class="DivCategoria">
        <div class="form-group col-lg-12">
            <div class="col-lg-12">
                <div class="form-group col-lg-3">
                    <label for="exampleInputEmail1">Marca</label>
                    <input type="text" class="form-control" id="Marca" placeholder="Marca">
                </div>
                <div class="form-group col-lg-2">
                    <label for="exampleInputEmail1">Modelo</label>
                    <input type="date" class="form-control" id="Modelo" placeholder="Modelo">
                </div>
                <div class="form-group col-lg-2">
                    <label for="exampleInputEmail1">Placa</label>
                    <input type="date" class="form-control" id="Placa" placeholder="Placa">
                </div>
                <div class="form-group col-lg-2">
                    <label for="exampleInputEmail1">Numero Frota</label>
                    <input type="number" class="form-control" id="NumeroFrota" placeholder="Numero Frota">
                </div>
                <div class="form-group col-lg-3">
                    <label for="exampleInputEmail1">Km Inicial</label>
                    <input type="number" class="form-control" id="Km Inicial" placeholder="Km Inicial">
                </div>
                <div class="form-group col-lg-3">
                    <label for="exampleInputEmail1">Km Atual</label>
                    <input type="number" class="form-control" id="KmAtual" placeholder="Km Atual">
                </div>
                <div class="form-group col-lg-3">
                    <label for="exampleInputEmail1">Ano</label>
                    <input type="number" class="form-control" id="Ano" placeholder="Ano">
                </div>
            </div>

        </div>


        <div class="form-group col-lg-12">
            <button type="button" class="btn btn-primary" onclick="CadastraAtualizaDeletaProdutos('1',IdProduto)">Salvar</button>
        </div>
        <div class="col-lg-12">
            <center>
                <h3 class="titulo text-lg-center">Frota</h3>
                <hr>
            </center>
            <div class="col-lg-12 col-xs-12 tableFixHead">
                <table id="tableProdutos" style="width: 100%;">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Marca</th>
                            <th>Modelo</th>
                            <th>Placa</th>
                            <th>Editar</th>
                            <th>Clonar</th>
                            <th>Deleta</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script>
    jQuery(function($) {
        $('#TipoUnidade').selectpicker('refresh');
        RetornaFazendas(null);
        CarregaCategorias();
        ContAdd = 0;
    });

    function AddItem() {
        var content = "";
        ContAdd = ContAdd + 1;
        $.get('../Entranda/Produto.php', {
            Nome: $("#Nome").val(),
            Categoria: $("#Categoria").val(),
            TipoUnidade: $("#TipoUnidade").val(),
            Quantidade: $("#Quantidade").val(),
            QuantidadeMin: $("#QuantidadeMin").val(),
            Valor: $("#Valor").val(),
            ContAdd: ContAdd
        }, function(data) {
            content = data;
            $('#ProdutosAdd').prepend(content);
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

    function CarregaCategorias() {
        var data = {
            funcao: "RetornaCategorias"
        };
        ChamaAjax(data, function(Resposta) {
            var Resposta = JSON.parse(Resposta);
            if (Resposta.Status == "Ok") {

                var Respostas = Resposta.Resposta;
                var cont = Resposta.Resposta.length;
                var Dados = [];
                for (passo = 0; passo < cont; passo++) {
                    Dados.push(Respostas[passo].Nome);
                }
                autocomplete(document.getElementById("Categoria"), Dados);

            } else {}
        }, function(error) {
            console.log(error);
        });
    }

    function CadastraAtualizaDeletaProdutos(Acao, Id) {

        var Nome = $("#Nome").val();
        var Quantidade = $("#Quantidade").val();
        var NewCategoria = $("#NewCategoria").val();
        var Fazenda = $("#Fazenda option:selected").val();

        var CategoriaSelect = $("#CategoriaSelect option:selected").val();
        var Valor = $("#Valor").val();
        var TipoUnidade = $("#TipoUnidade option:selected").val();
        if (Id != null && Id != "" && Acao != "3") {
            Acao = '2';
        }
        var data = {
            funcao: "CadastraAtualizaDeletaProdutos",
            Acao: Acao,
            Id: Id,
            Quantidade: Quantidade,
            NewCategoria: NewCategoria,
            Valor: Valor,
            CategoriaSelect: CategoriaSelect,
            TipoUnidade: TipoUnidade,
            Nome: Nome,
            Fazenda: Fazenda
        };

        ChamaAjax(data, function(rsp) {

            var Resposta = JSON.parse(rsp);
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
        }, function(error) {
            console.log(error);
        });
    }

    function RetornaFazendas(Id) {
        var data = {
            funcao: "RetornaFazendas",
            Id: Id
        };
        ChamaAjax(data, function(Resposta) {
            var Resposta = JSON.parse(Resposta);
            if (Resposta.Status == "Ok") {
                var Respostas = Resposta.Resposta;
                var cont = Resposta.Resposta.length;
                $("#Fazenda").empty();
                $("#Fazenda").append(new Option("Fazenda", ));
                for (passo = 0; passo < cont; passo++) {
                    $("#Fazenda").append(new Option(Respostas[passo].Nome, Respostas[passo].Id));
                }
                $('#Fazenda').selectpicker('refresh');

            } else {
                $('#Fazenda').selectpicker('refresh');
            }
        }, function(error) {
            console.log(error);
        });
    }
</script>