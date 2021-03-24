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
            <div class="form-group col-lg-3">
                <label>Número da nota</label>
                <input type="number" class="form-control" id="Nota" placeholder="Número da nota">
            </div>
            <div class="form-group col-lg-3">
                <label>Data Compra</label>
                <input type="date" class="form-control" id="DataCompra" placeholder="Data Compra">
            </div>
            <div class="form-group col-lg-3">
                <label>Data Vencimento</label>
                <input type="date" class="form-control" id="DataVencimento" placeholder="Data Compra">
            </div>
            <div class="form-group col-lg-3">
                <label>Parcelas</label>
                <input type="number" class="form-control" id="Parcelas" placeholder="Parcelas">
            </div>
            <div class="form-group col-lg-3">
                <label>Fornecedor</label>
                <input type="number" class="form-control" id="Fornecedor" placeholder="Fornecedor">
            </div>
            <div class="form-group col-lg-3">
                <label>CNPJ Fornecedor</label>
                <input type="number" class="form-control" id="CNPJFornecedor" placeholder=" Fornecedor">
            </div>
            <div class="form-group col-lg-3">
                <label>Fazenda</label>
                <select class="selectpicker form-control" id="Fazenda">
                    <option value="">Fazenda</option>
                </select>
            </div>
            <div class="form-group col-lg-3">
                <label>Safra</label>
                <select class="selectpicker col-12" data-live-search="true" id="SafraEntrada">
                    <option value="">Safra</option>
                </select>
            </div>
        </div>

        <div class="form-group col-lg-12">
            <center>
                <h2>Adiciona Produtos</h2>
            </center>
            <hr>
            <div class="form-group col-lg-4">
                <label>Nome</label>
                <input type="text" class="form-control" id="Nome" name="nome" placeholder="Nome">
            </div>
            <div class="form-group col-lg-4">
                <label>Categoria</label>
                <!-- <input type="text" class="form-control" id="Categoria" name="Categoria" placeholder="Categoria"> -->
                <select class="selectpicker col-12" data-live-search="true" id="Categoria">
                    <option value="">Categoria</option>
                </select>
            </div>
            <div class="form-group col-lg-4">
                <label>Tipo de unidade</label>
                <select class="selectpicker col-12" data-live-search="true" id="TipoUnidade">
                    <option value="MG">MG</option>
                    <option value="ML">ML</option>
                    <option value="KG">KG</option>
                    <option value="UN">UN</option>
                    <option value="LT">LT</option>
                    <option value="TL">TL</option>
                </select>
            </div>
            <div class="form-group col-lg-4">
                <label>Quantidade</label>
                <input type="number" class="form-control" id="Quantidade" placeholder="Quantidade">
            </div>
            <div class="form-group col-lg-4">
                <label>Quantidade Minima</label>
                <input type="number" class="form-control" id="QuantidadeMin" placeholder="Quantidade Mininima">
            </div>

            <div class="form-group col-lg-4">
                <label>Valor</label>
                <input type="text" class="form-control" id="Valor" aria-describedby="emailHelp" name="Valor" placeholder="Valor">
            </div>
        </div>
        <div class="form-group col-lg-12">
            <button type="button" class="btn btn-primary" style="background-color: green;" onclick="AddItem()">Add Item</button>
        </div>
        <div class="col-lg-12">
            <center>
                <h2>Produtos adicionados</h2>
            </center>
            <hr>
            <div class="col-lg-12" id="ProdutosAdd">

            </div>
        </div>

        <div class="form-group col-lg-12">

            <button type="button" class="btn btn-primary" style="float: right;" onclick="CadastraAtualizaDeletaProdutos('1',IdProduto)">Salvar</button>
        </div>
        <div class="col-lg-12">
            <center>
                <h3 class="titulo text-lg-center">Produtos</h3>
                <hr>
            </center>
            <div class="col-lg-12 col-xs-12 tableFixHead">
                <table id="tableProdutos" style="width: 100%;">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Nome</th>
                            <th>Categoria</th>
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
        CarregaNome(null);
        ContAdd = 0;
        Acao = "1";
        IdNota= "";
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
        anode.setAttribute("class", "fa fa-edit Edit");
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
            console.log(Resposta);
            var Resposta = JSON.parse(Resposta);
            if (Resposta.Status == "Ok") {

                var Respostas = Resposta.Resposta;
                var cont = Resposta.Resposta.length;
                var Dados = [];
                for (passo = 0; passo < cont; passo++) {
                    $("#Categoria").append(new Option(Respostas[passo].Nome, Respostas[passo].Id));
                    // Dados.push(Respostas[passo].Nome);
                }
                $('#Categoria').selectpicker('refresh');
                // autocomplete(document.getElementById("Categoria"), Dados);
            }
        }, function(error) {
            console.log(error);
        });
    }

    function CarregaNome(Id) {
        var data = {
            funcao: "RetornaProdutos",
            Id: Id
        };
        ChamaAjax(data, function(Resposta) {
            console.log(Resposta);
            var Resposta = JSON.parse(Resposta);
            if (Resposta.Status == "Ok") {

                var Respostas = Resposta.Resposta;
                var cont = Resposta.Resposta.length;
                var Dados = [];
                for (passo = 0; passo < cont; passo++) {
                    Dados.push(Respostas[passo].Nome);
                }
                autocomplete(document.getElementById("Nome"), Dados);

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
                    $("#SafraEntrada").append(new Option(Respostas[passo].Safra, Respostas[passo].Safra));
                }
                $('#Fazenda').selectpicker('refresh');
                $('#SafraEntrada').selectpicker('refresh');

            } else {
                $('#SafraEntrada').selectpicker('refresh');
                $('#Fazenda').selectpicker('refresh');
            }
        }, function(error) {
            console.log(error);
        });
    }

    // ============== salva ==============

    function CadastraAtualizaDeletaNota(Id, Acao) {
        var data = {
            funcao: "CadastraAtualizaDeletaNota",
            Id: Id,
            Acao: Acao,
            Nota: $("#Nota").val(),
            DataCompra: $("#DataCompra").val(),
            DataVencimento: $("#DataVencimento").val(),
            Parcelas: $("#Parcelas").val(),
            Fornecedor: $("#Fornecedor").val(),
            CNPJFornecedor: $("#CNPJFornecedor").val(),
            Fazenda: $("#Fazenda option:selected").val(),
            SafraEntrada: $("#SafraEntrada option:selected").val()
        };
        ChamaAjax(data, function(Resposta) {
            console.log(Resposta);
            var Resposta = JSON.parse(Resposta);
            if (Resposta.Status == "Ok") {
                var Respostas = Resposta.Resposta;
                $("#alert").html(Respostas);
                if(Acao == "1"){
                    IdNota = Resposta.Id;
                }
            }
        }, function(error) {
            console.log(error);
        });
    }

    function CadastraAtualizaDeletaProdutos(Acao, Id) {

        var Nome = $("#Nome").val();
        var Quantidade = $("#Quantidade").val();
        var Fazenda = $("#Fazenda option:selected").val();

        // var NewCategoria = $("#NewCategoria").val();
        // var CategoriaSelect = $("#CategoriaSelect").val();
        var Categoria = $("#Categoria option:selected").val();
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
            // NewCategoria: NewCategoria,
            Valor: Valor,
            CategoriaSelect: Categoria,
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
                return;
            }
        }, function(error) {
            console.log(error);
        });
    }

    $("#tableProdutos").on("click", ".Edit", function() {
        IdNota = $(this).attr('id');
        Acao = "2";
        Retorna(IdFrota);
    });

    $("#tableProdutos").on("click", ".Delet", function() {
        IdNota = $(this).attr('id');
        Acao = "3";
        CadastraAtualizaDeletaFrota(Acao, IdFrota);
    });

    $('#Valor').mask('#.##0,00', {
    reverse: true
});
</script>