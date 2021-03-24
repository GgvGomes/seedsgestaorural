<?php
include '../../includes/conexoes/index.php';
?>
<script>
    IdTalhao = "";
    LocalizacaoMaps = "";
    Localizacoes = [];
    contaLoc = 0;
</script>
<div class="col-lg-12" style="background-color: white;padding-bottom: 5%;">
    <i class="fa fa-angle-double-down" style="font-size:36px;float: right;" id="ChamaProdutosSaida"></i>
    <h4 class="text-center">Cadastro de Talhões</h4>
    <hr>
    <div class="DivProdutosSaida">
        <div class="form-group col-lg-3">
            <label for="exampleInputEmail1">Zona</label>
            <select class="selectpicker form-control" id="Zona">
                <option value="">Zona</option>
            </select>
        </div>
        <div class="form-group col-lg-3">
            <label for="exampleInputEmail1">Variedade</label>
            <select class="selectpicker form-control" id="Variedade">
                <option value="">Variedade</option>
            </select>
            <div class="form-group col-lg-12">
                <label for="formGroupExampleInput">Nova Variedade</label>
                <input type="text" class="form-control" id="NewVariedade" placeholder="Nova Variedade">
            </div>
        </div>
        <div class="form-group col-lg-2">
            <label for="exampleInputEmail1">Hectare</label>
            <input type="text" class="form-control" id="Hectare" placeholder="Hectare">
        </div>
        <div class="col-lg-12" id="CarregaMap">

        </div>

        <div class="form-group col-lg-12" style="padding-top:5%">
            <button type="button" class="btn btn-primary" onclick="SalvaTalhao(IdTalhao,'1',Localizacoes)">Salvar</button>
            <button type="button" class="btn btn-primary" onclick="AdicionaLocalizacao(LocalizacaoMaps, contaLoc=contaLoc+1)" style="float: right;background-color: green;">Salva Localização</button>
        </div>
        <div class="col-lg-12" id="LocalizacoesSalvas" style="padding-top: 1%;background-color: whitesmoke;">
            <div class="col-lg-4"><label>Zona</label></div>
            <div class="col-lg-3"><label>Variedade</label></div>
            <div class="col-lg-3"><label>Hectare</label></div>
            <div class="col-lg-2"><label>Remover</label></div>
        </div>
        <div class="col-lg-12" id="LocalizacoesSalvas" style="padding-bottom: 1%;padding-top: 2%;background-color: whitesmoke;">

        </div>
        <div class="col-lg-12">
            <center>
                <h3 class="titulo text-lg-center">Talhões</h3>
                <hr>
            </center>
            <div class="col-lg-12 col-xs-12 tableFixHead">
                <table id="tableTalhoes" style="width: 100%;">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Zona</th>
                            <th>Editar</th>
                            <th>Deletar</th>
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
        RetornaZonas(null);
        $('#Safra').selectpicker('refresh');
        RetornatalhoesTalhoes(null);
        RetornaPlantacao(null);
        RetornaZona();
    });

    function AdicionaLocalizacao(LocalizacaoMaps, contaLoc) {
        Zona = $("#Zona option:selected").val();
        Variedade = $("#Variedade option:selected").val();
        NewVariedade = $("#NewVariedade").val();
        Hectare = $("#Hectare").val();
        if (NewVariedade != null && NewVariedade != "") {
            Variedade = NewVariedade;
        }

        if (LocalizacaoMaps == "" || LocalizacaoMaps == null) {
            $("#alert").html("Selecione a localização");
            $("#AlertModal").modal("show");
            return;
        }
        contaLoc = contaLoc - 1;
        MotaDivTalhoes(Zona, Variedade, LocalizacaoMaps, Hectare, contaLoc);
        LocalizacaoMaps = "";
    }

    function MotaDivTalhoes(Zona, Variedade, Localizacao, Hectare, Id) {
        let div = "<div class='col-12' id='" + Id + "'>" +
            "<div class='col-lg-4'><label>" + Zona + "</label></div>" +
            "<div class='col-lg-3'><label>" + Variedade + "</label></div>" +
            "<div class='col-lg-3'><label>" + Hectare + "</label></div>" +
            "<div class='col-lg-2'><center><i class='fa fa-close' onclick='DeletaLocalizacao(" + Id + ")' style='font-size:24px;color:red'></i></center></div>";
        Localizacoes[Id] = [Localizacao, $("#Hectare").val()];
        $("#LocalizacoesSalvas").append(div);
        $("#Hectare").val("");
        initMap(null, CorZona);
        console.log(Localizacoes);
    }

    function DeletaLocalizacao(Id) {
        Localizacoes[Id] = "";
        $("#" + Id).hide();
        console.log(Id);
    }

    function RetornaZonas(Id) {
        var data = {
            funcao: "RetornaZonas",
            Id: Id
        };

        ChamaAjax(data, function(rsp) {
            var Resposta = JSON.parse(rsp);
            var Respostas = Resposta.Resposta;
            if (Resposta.Status == "Ok") {
                var cont = Respostas.length;
                $("#Zona").empty();
                for (passo = 0; passo < cont; passo++) {
                    $("#Zona").append(new Option(Respostas[passo].Nome, Respostas[passo].Id));
                    if (Id != null && Id != "" && Id == Respostas[passo].Id == Id) {
                        CorZona = Respostas[passo].Cor;
                        initDrawing()
                    } else {
                        CorZona = Respostas[0].Cor;
                    }
                }

                CorZona = CorZona.replace("#", "");
                $("#CarregaMap").load('../talhao/map.php?Cor=' + CorZona);
                $('#Zona').selectpicker('refresh');
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

    function RetornaPlantacao(Id) {
        var data = {
            funcao: "RetornaPlantacao",
            Id: Id
        };
        ChamaAjax(data, function(Resposta) {
            console.log(Resposta);
            var Resposta = JSON.parse(Resposta);
            if (Resposta.Status == "Ok") {
                var Respostas = Resposta.Resposta;
                var cont = Resposta.Resposta.length;
                $("#Variedade").empty();
                $("#Variedade").append(new Option("Selecione a variedade", ""));
                if (passo > 0) {
                    for (passo = 0; passo < cont; passo++) {
                        $("#Variedade").append(new Option(Respostas[passo].Nome, Respostas[passo].Id));
                    }
                }
                $('#Variedade').selectpicker('refresh');
            } else {
                $('#Variedade').selectpicker('refresh');
            }
        }, function(error) {
            console.log(error);
        });
    }

    function RetornaZona(Id) {
        var data = {
            funcao: "RetornatalhoesZonas",
            Id: Id
        };

        ChamaAjax(data, function(rsp) {
            var Resposta = JSON.parse(rsp);
            var Respostas = Resposta.Resposta;
            if (Resposta.Status == "Ok") {
                var Respostas = Resposta.Resposta;
                var cont = Resposta.Resposta.length;
                $("#Zona").empty();
                for (passo = 0; passo < cont; passo++) {
                    $("#Zona").append(new Option(Respostas[passo].Nome, Respostas[passo].Id));
                }
                $('#Zona').selectpicker('refresh');
            } else {
                $("#alert").html(Resposta.Resposta);
                $("#AlertModal").modal("show");
            }
        }, function(error) {
            console.log(error);
        });
    }

    function SalvaTalhao(Id, Acao, Localizaocao) {
        Zona = $("#Zona option:selected").val();
        Variedade = $("#Variedade option:selected").val();
        NewVariedade = $("#NewVariedade").val();
        Hectare = $("#Hectare").val();
        var data = {
            funcao: "CadastraAtualizaDeletaTalhao",
            Id: Id,
            Acao: Acao,
            Variedade: Variedade,
            Zona: Zona,
            Hectare: Hectare,
            NewVariedade: NewVariedade,
            Localizaocao: Localizaocao
        };

        ChamaAjax(data, function(rsp) {

            $("#Exibe").load("../../pages/talhao/index.php");
        }, function(error) {
            console.log(error);
        });
    }

    function addTableTalhoes(Id, Fazendas) {
        var num = document.getElementById("tableTalhoes").rows.length;

        var x = document.createElement("tr");

        var a = document.createElement("td");
        var anode = document.createTextNode(Id);
        a.appendChild(anode);
        x.appendChild(a);

        a = document.createElement("td");
        anode = document.createTextNode(Fazendas);
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
        anode.setAttribute("class", "fa fa-close Delet");
        anode.setAttribute("style", "cursor:pointer");
        a.appendChild(anode);
        x.appendChild(a);

        document.getElementById("tableTalhoes").appendChild(x);
    }

    $("#tableTalhoes").on("click", ".Editar", function() {
        IdTalhao = $(this).attr('id');
        RetornatalhoesTalhoes(IdTalhao);
    });

    $("#tableTalhoes").on("click", ".Delet", function() {
        IdTalhao = $(this).attr('id');
        SalvaTalhao(IdTalhao, "3", "");
    });

    function RetornatalhoesTalhoes(Id) {
        var data = {
            funcao: "RetornatalhoesTalhoes",
            Id: Id
        };

        ChamaAjax(data, function(rsp) {
            console.log(rsp);
            var Resposta = JSON.parse(rsp);
            var Respostas = Resposta.Resposta;
            if (Resposta.Status == "Ok") {
                if (Id == null || Id == "") {
                    var cont = Respostas.length;
                    $('#tableTalhoes tr').slice(1).remove();
                    for (passo = 0; passo < cont; passo++) {
                        addTableTalhoes(Respostas[passo].Id, Respostas[passo].NomeZona);
                    }
                } else {
                    RetornaZonas(Respostas[0].Zona);
                    initDrawing(Respostas[0].Localizacao);
                    $("#Safra").val(Respostas[0].Safra);
                    $("#Fazenda").val(Respostas[0].Fazenda).change();
                    $("#Hectare").val(Respostas[0].Hectare);
                    $("#Nome").val(Respostas[0].Nome);
                    $('#Fazenda').selectpicker('refresh');
                }
            } else {
                // $("#alert").html(Resposta.Resposta);
                // $("#AlertModal").modal("show");
            }
        }, function(error) {
            console.log(error);
        });
    }
</script>