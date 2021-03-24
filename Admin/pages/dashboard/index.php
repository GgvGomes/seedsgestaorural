<script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCvA6oCDvgYXss9bGDdNPcbIkiLfFpcucQ&callback=initMap&libraries=drawing&v=weekly" defer></script>

<div class="col-lg-12">
    <div class="col-lg-4 col-md-6">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-bar-chart fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="valor" id="ComprasMes"><label id="Dia">R$ 0,00</label></div>
                        <div>Compras Mês</div>
                    </div>
                </div>
            </div>
            <a href="#">
                <div class="panel-footer Relatorios">
                    <span class="pull-left">Detalhes</span>
                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                    <div class="clearfix"></div>
                </div>
            </a>
        </div>
    </div>

    <div class="col-lg-4 col-md-6">
        <div class="panel panel-green" style="background-color: red;color: white">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-bar-chart fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right Relatorios">
                        <div class="valor" id="GastosMes"><label id="Mes">R$ 0,00</label></div>
                        <div>Gastos Més</div>
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
                        <div class="valor" id="ProducaoMes"><label id="Mes"> 0,00</label></div>
                        <div>Produtividade / Mês</div>
                    </div>
                </div>
            </div>
            <a href="#">
                <div class="panel-footer Producao">
                    <span class="pull-left">Detalhes</span>
                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                    <div class="clearfix"></div>
                </div>
            </a>
        </div>
    </div>
</div>

<div class="col-lg-12">
    <div id="map" style="width: 100%;height: 500px;"></div>
</div>
<?php include './estoqueMinimo.php'; ?>
<script>
    jQuery(function($) {
        RetornatalhoesTalhoes(null);
        map;
        RetornaProdutosEntradaSaida();

    });
    $(".Producao").click(function() {
        $("#h1Tela").text("Produção");
        $("#liTela").text("Produção");
        $("#Exibe").load("../../pages/Producao/index.php");
        $("#MenuMob").animate({
            left: '-100%'
        }), 5000;
    });
    $(".Relatorios").click(function() {
        $("#h1Tela").text("Relatórios");
        $("#liTela").text("Relatórios");
        $("#Exibe").load("../../pages/relatorios/index.php");
        $("#MenuMob").animate({
            left: '-100%'
        }), 5000;
    });

    function RetornaProdutosEntradaSaida() {
        var data = {
            funcao: "RetornaProdutosEntradaSaida"
        };

        ChamaAjax(data, function(rsp) {

            var Resposta = JSON.parse(rsp);

            $("#ComprasMes").html(Resposta.Entrada);
            $("#GastosMes").html(Resposta.Saida);
            $("#ProducaoMes").html(Resposta.Producao);


        }, function(error) {
            console.log(error);
        });
    }

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

    function RetornatalhoesTalhoes(Id) {
        var data = {
            funcao: "RetornatalhoesTalhoesDashboard",
            Id: Id
        };
        ChamaAjax(data, function(rsp) {
            console.log(rsp);
            var Resposta = JSON.parse(rsp);
            var Respostas = Resposta.Resposta;
            if (Resposta.Status == "Ok") {
                initMap(Respostas[0].Localizaocao);
            } else {
                $("#alert").html(Resposta.Resposta);
                $("#AlertModal").modal("show");
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