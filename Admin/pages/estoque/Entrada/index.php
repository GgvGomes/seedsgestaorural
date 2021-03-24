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
                <div class="form-group col-lg-12">
                    <label for="exampleInputEmail1">Nome</label>
                    <input type="text" class="form-control" id="Nome" aria-describedby="emailHelp" name="nome" placeholder="Nome">
                </div>
                <div class="form-group col-lg-12">
                    <label for="formGroupExampleInput">Novo nome</label>
                    <input type="text" class="form-control" id="NewCategoria" placeholder="Nova Categoria">
                </div>
            </div>
            <div class="form-group col-lg-3">
                <div class="form-group col-lg-12">
                    <label for="formGroupExampleInput">Insumos</label>
                    <select class="selectpicker form-control" id="CategoriaSelect">
                        <option value="">Insumos</option>
                    </select>
                </div>
                <div class="form-group col-lg-12">
                    <label for="formGroupExampleInput">Insumos</label>
                    <input type="text" class="form-control" id="NewCategoria" placeholder="Novo Insumos">
                </div>
            </div>
            <div class="form-group col-lg-2">
                <label for="exampleInputPassword1">Tipo de unidade</label>
                <select class="selectpicker col-12" data-live-search="true" id="TipoUnidade">
                    <option value="MG">MG</option>
                    <option value="ML">ML</option>
                    <option value="KG">KG</option>
                    <option value="UN">UN</option>
                    <option value="LT">LT</option>
                </select>
            </div>
            <div class="form-group col-lg-2">
                <label for="exampleInputEmail1">Quantidade</label>
                <input type="text" class="form-control" id="Quantidade" placeholder="Quantidade">
            </div>

            <div class="form-group col-lg-2">
                <label for="exampleInputEmail1">Valor</label>
                <input type="text" class="form-control" id="Valor" aria-describedby="emailHelp" name="Valor" placeholder="Valor">
            </div>
        </div>
        <div class="form-group col-lg-12">
            <div class="form-group col-lg-4">
                <label for="formGroupExampleInput">Fazenda</label>
                <select class="selectpicker form-control" id="Fazenda">
                    <option value="">Fazenda</option>
                </select>
            </div>
        </div>
        <div class="form-group col-lg-2">
            <label for="exampleInputPassword1">Safra</label>
            <select class="selectpicker col-12" data-live-search="true" id="SafraEntrada">
                <option value="">Safra</option>
            </select>
        </div>
        <div class="form-group col-lg-12">
            <button type="button" class="btn btn-primary" onclick="CadastraAtualizaDeletaProdutos('1',IdProduto)">Salvar</button>
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
        $('#SafraEntrada').selectpicker('refresh');
        RetornaFazendas(null);
    });

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