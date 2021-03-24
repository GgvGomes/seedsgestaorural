jQuery(function ($) {
    RetornaFazendas(null);
    RetornaResponsavel(null);
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
        letste = google.maps.event.addListener(drawingManager, 'polygoncomplete', function (polygon) {
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

function addTableFazenda(Id, nome, responsavel) {
    var num = document.getElementById("tableFazenda").rows.length;

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
    anode = document.createTextNode(responsavel);
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

    document.getElementById("tableFazenda").appendChild(x);
}
$("#tableFazenda").on("click", ".Delet", function () {
    IdUsuario = $(this).attr('id');
    CadastraAtualizaDeletaFazendas(IdUsuario, "3");
});
$("#tableFazenda").on("click", ".Edit", function () {
    IdUsuario = $(this).attr('id');
    IdFazenda = IdUsuario;
    RetornaFazendas(IdUsuario);
});
function RetornaFazendas(Id) {
    var data = { funcao: "RetornaFazendas", Id: Id };
    ChamaAjax(data, function (Resposta) {
        var Resposta = JSON.parse(Resposta);
        if (Resposta.Status == "Ok") {
            var Respostas = Resposta.Resposta;
            if (Id != null && Id != "") {
                $("#Nome").val(Respostas[0].Nome);
                initMap(Respostas[0].Localizacao);
            } else {
                var cont = Resposta.Resposta.length;
                $('#tableFazenda tr').slice(1).remove();
                for (passo = 0; passo < cont; passo++) {
                    addTableFazenda(Respostas[passo].Id, Respostas[passo].Nome, Respostas[passo].Responsavel);
                }
            }
        } else {
            $('#tableFazenda tr').slice(1).remove();
        }
    }, function (error) {
        console.log(error);
    });
}
function RetornaResponsavel(Id) {
    var data = { funcao: "RetornaResponsaveis", Id: Id };
    ChamaAjax(data, function (Resposta) {
        var Resposta = JSON.parse(Resposta);
        if (Resposta.Status == "Ok") {
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
function initMap(codenacoes) {
    if (codenacoes == "" || codenacoes == null) {
        codenacoes = {
            lat: -15.699223460885563,
            lng: -47.98856710942802
        };
    }
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
function CadastraAtualizaDeletaFazendas(Id, Acao, LocalizacaoMaps) {

    var Nome = $("#Nome").val();
    if (Nome == "" || Nome == null) {
        alert("Informe o nome");
        $("#alert").html("Infome o nome");
        $("#AlertModal").modal("show");
        return;
    }
    CorteAtual = $("#CorteAtual").val();
    var IdResponsavel = $("#Responsavel option:selected").val();
    var AnoOne = $("#AnoOne option:selected").val();
    var AnoTwo = $("#AnoTwo option:selected").val();
    if (Id != null && Id != "" && Acao != "0" && Acao != "3") {
        Acao = '2';
    }
    var data = {
        funcao: "CadastraAtualizaDeletaFazendas", CorteAtual: CorteAtual,
        Localizacao: LocalizacaoMaps, Id: Id, Acao: Acao, IdResponsavel: IdResponsavel, Nome: Nome,
        AnoOne: AnoOne, AnoTwo: AnoTwo
    };
    ChamaAjax(data, function (Resposta) {
        console.log(Resposta);
        var Resposta = JSON.parse(Resposta);
        if (Resposta.Status == "Ok") {
            $("#alert").html(Resposta.Resposta);
            $("#AlertModal").modal("show");
            $("#Nome").val("");
            RetornaResponsavel(null);
            RetornaFazendas(null);
            initMap("");
        }
    }, function (error) {
        console.log(error);
    });
}