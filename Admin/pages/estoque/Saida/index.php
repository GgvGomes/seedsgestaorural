<?php
include '../../includes/conexoes/index.php';
?>
<script>
    var IdProdutoSaida = "";
</script>
<div class="col-lg-12" style="background-color: white;padding-bottom: 5%;">
    <i class="fa fa-angle-double-down" style="font-size:36px;float: right;" id="ChamaProdutosSaida"></i>
    <h4 class="text-center">Saídas de produtos</h4>
    <hr>
    <div class="DivProdutosSaida">
        <div class="form-group col-lg-3">
            <label for="exampleInputEmail1">Nome</label>
            <input type="text" class="form-control" disabled id="NomeSaida" aria-describedby="emailHelp" name="nome" placeholder="Nome">
        </div>
        <div class="form-group col-lg-3">
            <label for="formGroupExampleInput">Categoria</label>
            <select class="selectpicker form-control" disabled id="CategoriaSelectSaida">
                <option value="">Categoria</option>
            </select>
        </div>
        <div class="form-group col-lg-2">
            <label for="exampleInputPassword1">Tipo de unidade</label>
            <select class="selectpicker col-12" data-live-search="true" id="TipoUnidadeSaida" disabled>
                <option value="MG">MG</option>
                <option value="ML">ML</option>
                <option value="KG">KG</option>
                <option value="LT">LT</option>
                <option value="UN">UN</option>
                <option value="ML">ML</option>
            </select>
        </div>
        <div class="form-group col-lg-2">
            <label for="exampleInputEmail1">Quantidade de saída</label>
            <input type="text" class="form-control" id="QuantidadeSaida" placeholder="Quantidade Saída">
            <small id="emailHelp" class="form-text text-muted">Informe a quantidade de saída.</small>
        </div>
        <div class="form-group col-lg-3">
            <label for="formGroupExampleInput">Zona</label>
            <select class="selectpicker form-control" id="Talhao">
                <option value="">Zona</option>
            </select>
        </div>
        <div class="form-group col-lg-3" id="ZonaSelectPai">
            <div class="form-group col-lg-12">
                <label for="formGroupExampleInput">Talhão</label>
                <select class="selectpicker form-control" id="ZonaSelect">
                    <option value="">Talhão</option>
                </select>
            </div>
            <div class="form-group col-lg-12">
                <label for="formGroupExampleInput">Talhão</label>
                <input type="text" class="form-control" id="NewZona" placeholder="Novo Talhão">
            </div>
        </div>
        <div class="form-group col-lg-3">
            <div class="form-group col-lg-12">
                <label for="formGroupExampleInput">Veículo</label>
                <select class="selectpicker form-control" id="VeiculoSelect">
                    <option value="">Veiculo</option>
                </select>
            </div>
            <div class="form-group col-lg-12">
                <label for="formGroupExampleInput">Novo Veículo</label>
                <input type="text" class="form-control" id="NewVeiculo" placeholder="Nova Veículo">
            </div>
        </div>
        <div class="form-group col-lg-2">
            <label for="exampleInputPassword1">Safra</label>
            <select class="selectpicker col-12" data-live-search="true" id="SafraEntrada">
                <option value="">Safra</option>
            </select>
        </div>
        <div class="form-group col-lg-12">
            <button type="button" class="btn btn-primary" onclick="SaidaDeEstoque(IdProdutoSaida)">Salvar</button>
        </div>
        <div class="col-lg-12">
            <center>
                <h3 class="titulo text-lg-center">Produtos</h3>
                <hr>
            </center>
            <div class="col-lg-12 col-xs-12 tableFixHead">
                <table id="tableProdutosSaida" style="width: 100%;">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Nome</th>
                            <th>Categoria</th>
                            <th>Tipo Unidade</th>
                            <th>Quantidade</th>
                            <th>Selecionar</th>
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
    $('#ZonaSelect').selectpicker('refresh');
    $('#VeiculoSelect').selectpicker('refresh');
    $("#Talhao").change(function() {
        RetornaZona();
    });
    //

    function RetornaVeiculos() {
        var data = {
            funcao: "RetornaVeiculos"
        };
        ChamaAjax(data, function(rsp) {
            console.log(rsp);
            var Resposta = JSON.parse(rsp);
            var Respostas = Resposta.Resposta;
            if (Resposta.Status == "Ok") {
                $("#VeiculoSelect").empty();
                $('#VeiculoSelect').selectpicker('refresh');
                var cont = Respostas.length;
                $("#VeiculoSelect").append(new Option("Selecione o veículo", ""));
                for (passo = 0; passo < cont; passo++) {
                    $("#VeiculoSelect").append(new Option(Respostas[passo].Nome, Respostas[passo].Id));
                }
                $('#VeiculoSelect').selectpicker('refresh');
            } else {
                $("#alert").html(Resposta.Resposta);
                $("#AlertModal").modal("show");
            }
        }, function(error) {
            console.log(error);
        });
    }
    function RetornaZona() {
        var data = {
            funcao: "RetornatalhoesZonas",
            Talhao: $("#Talhao option:selected").val()
        };
        ChamaAjax(data, function(rsp) {
            console.log(rsp);
            var Resposta = JSON.parse(rsp);
            var Respostas = Resposta.Resposta;
            if (Resposta.Status == "Ok") {
                $("#ZonaSelect").empty();
                $("#ZonaSelect").append(new Option("Selecione a zona", ""));
                $('#ZonaSelect').selectpicker('refresh');
                var cont = Respostas.length;
                for (passo = 0; passo < cont; passo++) {
                    $("#ZonaSelect").append(new Option(Respostas[passo].Nome, Respostas[passo].Id));
                }
                $('#ZonaSelect').selectpicker('refresh');

            } else {
                $("#alert").html(Resposta.Resposta);
                $("#AlertModal").modal("show");
            }
        }, function(error) {
            console.log(error);
        });
    }
</script>