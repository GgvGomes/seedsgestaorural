<?php
include '../../includes/conexoes/index.php';
?>
<script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCvA6oCDvgYXss9bGDdNPcbIkiLfFpcucQ&callback=initMap&libraries=drawing&v=weekly" defer></script>
<script>
    IdTalhao = "";
    LocalizacaoMaps = "";
</script>
<div class="col-lg-12" style="background-color: white;padding-bottom: 5%;">
    <i class="fa fa-angle-double-down" style="font-size:36px;float: right;" id="ChamaProdutosSaida"></i>
    <h4 class="text-center">Cadastro de Zona</h4>
    <hr>
    <div class="DivProdutosSaida">
        <div class="form-group col-lg-3">
            <label for="exampleInputEmail1">Fazenda</label>
            <select class="selectpicker form-control" id="Fazenda">
                <option value="">Fazenda</option>
            </select>
        </div>
        <div class="form-group col-lg-2">
            <label for="exampleInputEmail1">Nome Zona</label>
            <input type="text" class="form-control" id="Nome" name="nome" placeholder="Nome Zona">
        </div>
        <div class="form-group col-lg-2">
            <label for="exampleInputEmail1">Nome Zona</label>
            <input type="color" class="form-control" id="Cor" name="Cor">
        </div>
        <div class="col-lg-12">
            <div id="map" style="width: 100%;height: 300px;"></div>
        </div>
        <div class="form-group col-lg-12">
            <button type="button" class="btn btn-primary" onclick="SalvaZona(IdTalhao,'1')">Salvar</button>
        </div>
        <div class="col-lg-12">
            <center>
                <h3 class="titulo text-lg-center">Zona</h3>
                <hr>
            </center>
            <div class="col-lg-12 col-xs-12 tableFixHead">
                <table id="tableZonas" style="width: 100%;">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Nome</th>
                            <th>Fazenda</th>
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
        $('#Safra').selectpicker('refresh');
        RetornaZonas(null);
        RetornaFazendas(null);
        map;
        initDrawing = (map) => {
            drawingManager = new google.maps.drawing.DrawingManager({
                map: map,
                drawingMode: google.maps.drawing.OverlayType.CICLE,
                drawingControl: true,
                drawingControlOptions: {
                    position: google.maps.ControlPosition.TOP_CENTER,
                    drawingModes: ['polygon']
                },
                polygonOptions: {
                    fillColor: '#f00',
                    strokeColor: '#f00'
                }
            });
            letste = google.maps.event.addListener(drawingManager, 'polygoncomplete', function(polygon) {
                const coords = polygon.getPath().getArray().map(coord => {
                    return {
                        lat: coord.lat(),
                        lng: coord.lng()
                    }
                });

                LocalizacaoMaps = JSON.stringify(coords, null, 1);
                // SAVE COORDINATES HERE
            });

        };
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

                $("#Fazenda").append(new Option("Selecione a fazenda", ""));
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


    function SalvaZona(Id, Acao) {
        Nome = $("#Nome").val();
        Fazenda = $("#Fazenda option:selected").val();
        if (Id != null && Id != "" && Acao!="3") {
            Acao = "2";
        }
        var data = {
            funcao: "CadastraAtualizaDeletaZona",
            Id: Id,
            Nome: Nome,
            Acao: Acao,
            Fazenda: Fazenda,
            Cor: $("#Cor").val()
        };

        ChamaAjax(data, function(rsp) {
            console.log(rsp);
            IdTalhao="";
            var Resposta = JSON.parse(rsp);
            if (Resposta.Status == "Ok") {
                RetornaZonas(null);
            }
            $("#alert").html(Resposta.Resposta);
            $("#AlertModal").modal("show");
        }, function(error) {
            console.log(error);
        });
    }

    function addTableZona(Id, Nome, Fazendas) {
        var num = document.getElementById("tableZonas").rows.length;

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

        document.getElementById("tableZonas").appendChild(x);
    }

    $("#tableZonas").on("click", ".Editar", function() {
        IdTalhao = $(this).attr('id');
        RetornaZonas(IdTalhao);
    });

    $("#tableZonas").on("click", ".Delet", function() {
        IdTalhao = $(this).attr('id');
        SalvaZona(IdTalhao, "3");
    });

    function RetornaZonas(Id) {
        var data = {
            funcao: "RetornaZonas",
            Id: Id
        };

        ChamaAjax(data, function(rsp) {

            console.log(rsp);
            var Resposta = JSON.parse(rsp);
            var Respostas = Resposta.Resposta;
            if (Resposta.Status == "Ok") {
                if (Id == null || Id == "") {
                    var cont = Respostas.length;
                    $('#tableZonas tr').slice(1).remove();
                    for (passo = 0; passo < cont; passo++) {
                        addTableZona(Respostas[passo].Id, Respostas[passo].Nome, Respostas[passo].NomeFazenda);
                    }
                } else {

                    initMap(Respostas[0].Localizaocao);
                    $("#Cor").val(Respostas[0].Cor);
                    $("#Fazenda").val(Respostas[0].Fazenda_Id).change();
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


    function initMap(codenacoes) {
        const myLatlng = {
            lat: -15.699223460885563,
            lng: -47.98856710942802
        };
        const map = new google.maps.Map(document.querySelector("#map"), {
            zoom: 4,
            center: myLatlng,
        });
        initDrawing(map);
        // Define the LatLng coordinates for the polygon's path.
        const triangleCoords = codenacoes;
        // Construct the polygon.
        const bermudaTriangle = new google.maps.Polygon({
            paths: triangleCoords,
            strokeColor: "#FF0000",
            strokeOpacity: 0.8,
            strokeWeight: 2,
            fillColor: "#FF0000",
            fillOpacity: 0.35,
        });
        bermudaTriangle.setMap(map);
    }
</script>