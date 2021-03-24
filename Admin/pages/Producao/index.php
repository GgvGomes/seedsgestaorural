<?php
include '../../includes/conexoes/index.php';
?>
<?php include '../../modais/alert.php'; ?>
<div class="col-lg-12" style="background-color: white;">
    <i class="fa fa-angle-double-down" style="font-size:36px;float: right;" id="ChamaProducao"></i>
    <h2 class="text-center">Produção</h2>
    <hr>
    <div class="DivCliclo">
        <div class="col-lg-12">
            <div class="col-lg-4 col-md-6">
                <div class="panel panel-primary" style="color: white">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-xs-3">
                                <i class="fa fa-bar-chart fa-5x"></i>
                            </div>
                            <div class="col-xs-9 text-right">
                                <div class="valor" id="GastosMes"><label id="Mes">R$ 0,00</label></div>
                                <div>Produtividade / Semana</div>
                            </div>
                        </div>
                    </div>
                    <a href="#">
                        <div class="panel-footer">
                            <span class="pull-left">Detalhes</span>
                            <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                            <div class="clearfix"></div>
                        </div>
                    </a>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="panel panel-green" style="background-color: green;color: white">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-xs-3">
                                <i class="fa fa-bar-chart fa-5x"></i>
                            </div>
                            <div class="col-xs-9 text-right">
                                <div class="valor" id="GastosMes"><label id="Mes">R$ 0,00</label></div>
                                <div>Produtividade / Mês</div>
                            </div>
                        </div>
                    </div>
                    <a href="#">
                        <div class="panel-footer">
                            <span class="pull-left">Detalhes</span>
                            <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                            <div class="clearfix"></div>
                        </div>
                    </a>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="panel panel-green" style="background-color: goldenrod;color: white">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-xs-3">
                                <i class="fa fa-bar-chart fa-5x"></i>
                            </div>
                            <div class="col-xs-9 text-right">
                                <div class="valor" id="GastosMes"><label id="Mes">R$ 0,00</label></div>
                                <div>Produtividade / Safra</div>
                            </div>
                        </div>
                    </div>
                    <a href="#">
                        <div class="panel-footer">
                            <span class="pull-left">Detalhes</span>
                            <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                            <div class="clearfix"></div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
        <div class="form-group col-lg-3">
            <label for="formGroupExampleInput">Talhão</label>
            <select class="selectpicker form-control" id="Talhao">
                <option value="">Talhão</option>
            </select>
        </div>
        <div class="form-group col-lg-3">
            <label for="exampleInputPassword1">Tipo de unidade</label>
            <select class="selectpicker col-12" data-live-search="true" id="TipoUnidade">
                <option value="Toneladas">Toneladas</option>
                <option value="Sacas">Sacas</option>
                <option value="Caixas">Caixas</option>
            </select>
        </div>
        <div class="form-group col-lg-3">
            <label for="exampleInputEmail1">Quantidade</label>
            <input type="text" class="form-control" id="Quantidade" placeholder="Quantidade">
        </div>
        <div class="form-group col-lg-12">
            <button type="button" class="btn btn-primary" onclick="CadastraProducao()">Salvar</button>
        </div>
        <div class="col-lg-12">
            <center>
                <h3 class="titulo text-lg-center">Produções</h3>
                <hr>
            </center>
            <div class="col-lg-12 col-xs-12 tableFixHead">
                <table id="tableProducao" style="width: 100%;">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Produto</th>
                            <th>Talhão</th>
                            <th>Unidade</th>
                            <th>Quantidade</th>
                            <th>Data</th>
                            <th>Usuário</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="<?php echo $urlPrincipal; ?>js/javaScript.js"></script>
<script>
    jQuery(function($) {
        RetornaCiclo();
        RetornaProducoes();
        RetornaFazendas(null);
        RetornatalhoesTalhoes(null);
        $('#TipoUnidade').selectpicker('refresh');
    });

    function addTableProducao(Id, Plantacao, Talhao, Unidade, Quantidade, Data, Usuario) {
        var num = document.getElementById("tableProducao").rows.length;

        var x = document.createElement("tr");

        var a = document.createElement("td");
        var anode = document.createTextNode(Id);
        a.appendChild(anode);
        x.appendChild(a);

        a = document.createElement("td");
        anode = document.createTextNode(Plantacao);
        a.appendChild(anode);
        x.appendChild(a);

        a = document.createElement("td");
        anode = document.createTextNode(Talhao);
        a.appendChild(anode);
        x.appendChild(a);

        a = document.createElement("td");
        anode = document.createTextNode(Unidade);
        a.appendChild(anode);
        x.appendChild(a);

        a = document.createElement("td");
        anode = document.createTextNode(Quantidade);
        a.appendChild(anode);
        x.appendChild(a);

        a = document.createElement("td");
        anode = document.createTextNode(Data);
        a.appendChild(anode);
        x.appendChild(a);

        a = document.createElement("td");
        anode = document.createTextNode(Usuario);
        a.appendChild(anode);
        x.appendChild(a);

        document.getElementById("tableProducao").appendChild(x);
    }

    function RetornaProducoes() {
        var data = {
            funcao: "RetornaProducoes"
        };
        ChamaAjax(data, function(Resposta) {
            console.log(Resposta);
            var Resposta = JSON.parse(Resposta);
            if (Resposta.Status == "Ok") {

                var Respostas = Resposta.Resposta;

                var cont = Resposta.Resposta.length;
                $('#tableProducao tr').slice(1).remove();
                for (passo = 0; passo < cont; passo++) {
                    addTableProducao(
                        Respostas[passo].Id,
                        Respostas[passo].Plantacao,
                        Respostas[passo].Talhao,
                        Respostas[passo].Unidade,
                        Respostas[passo].Quantidade,
                        Respostas[passo].Data,
                        Respostas[passo].Usuario
                    );
                }
            } else {
                $('#tableProducao tr').slice(1).remove();
            }
        }, function(error) {
            console.log(error);
        });
    }

    function RetornatalhoesTalhoes(Id) {
        var data = {
            funcao: "RetornatalhoesTalhoes",
            Id: Id
        };
        ChamaAjax(data, function(rsp) {

            var Resposta = JSON.parse(rsp);
            var Respostas = Resposta.Resposta;
            if (Resposta.Status == "Ok") {
                $("#Talhao").empty();
                $('#Talhao').selectpicker('refresh');
                var cont = Respostas.length;
                for (passo = 0; passo < cont; passo++) {
                    $("#Talhao").append(new Option(Respostas[passo].Nome, Respostas[passo].Id));
                }
                $('#Talhao').selectpicker('refresh');

            } else {
                $("#alert").html(Resposta.Resposta);
                $("#AlertModal").modal("show");
            }
        }, function(error) {
            console.log(error);
        });
    }

    function CadastraProducao() {
        var data = {
            funcao: "CadastraProducao",
            Talhao: $("#Talhao option:selected").val(),
            Unidade: $("#TipoUnidade option:selected").val(),
            Quantidade: $("#Quantidade").val(),
        };
        ChamaAjax(data, function(Resposta) {
            var Resposta = JSON.parse(Resposta);
            var Respostas = Resposta.Resposta;
            if (Resposta.Status == "Ok") {

                alert(Respostas);
                RetornaProducoes();
            } else {
                alert(Respostas);
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

    function tableCliclo(Id, Usuario, Data) {
        var num = document.getElementById("tableCliclo").rows.length;

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
        anode = document.createTextNode(Data);
        a.appendChild(anode);
        x.appendChild(a);

        document.getElementById("tableCliclo").appendChild(x);
    }

    function ResetCiclo(Senha) {
        Fazenda = $("#Fazenda option:selected").val();
        var data = {
            funcao: "ResetaCiclo",
            Senha: Senha,
            Fazenda: Fazenda
        };

        ChamaAjax(data, function(rsp) {
            var Resposta = JSON.parse(rsp);
            alert(Resposta.Resposta);
            if (Resposta.Status == "Ok") {

            } else {
                // $('#tableFazenda tr').slice(1).remove();
            }
        }, function(error) {
            console.log(error);
        });
    }

    function RetornaCiclo(Senha) {
        var data = {
            funcao: "RetornaCiclo"
        };
        ChamaAjax(data, function(rsp) {
            var Resposta = JSON.parse(rsp);
            if (Resposta.Status == "Ok") {
                Respostas = Resposta.Resposta;
                console.log(Respostas);
                var cont = Respostas.length;
                $('#tableCliclo tr').slice(1).remove();
                for (passo = 0; passo < cont; passo++) {
                    tableCliclo(Respostas[passo].Id, Respostas[passo].Usuario,
                        Respostas[passo].Data);
                }
            } else {
                $('#tableCliclo tr').slice(1).remove();
            }
        }, function(error) {
            console.log(error);
        });
    }
</script>