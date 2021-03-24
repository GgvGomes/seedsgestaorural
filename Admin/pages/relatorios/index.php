<?php
include '../../includes/conexoes/index.php';
?>
<?php include '../../modais/alert.php'; ?>
<div class="col-lg-12">
    <?php include './Resulmo/index.php'; ?>
    <?php include './Entrada/index.php'; ?>
    <?php include './Saida/index.php'; ?>
</div>
<script>
    jQuery(function($) {
        Nivel = "0";
        IdNivelZero = "";
        IdNivelUm = "";
        IdNivelDois = "";
        IdNivelTres = "";
        IdNivelQuatro = "";
        SaidaFluxoCaixa(Nivel, IdNivelZero, IdNivelUm, IdNivelDois, IdNivelTres, IdNivelQuatro);
    });

    function addSFluxo(Id, Produto, Unidade, Quantidade, Valor) {
        var num = document.getElementById("tableSaidaFluxo").rows.length;

        var x = document.createElement("tr");

        var a = document.createElement("td");
        var anode = document.createTextNode(Id);
        a.appendChild(anode);
        x.appendChild(a);

        a = document.createElement("td");
        anode = document.createTextNode(Produto);
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
        anode = document.createTextNode(Valor);
        a.appendChild(anode);
        x.appendChild(a);

        document.getElementById("tableSaidaFluxo").appendChild(x);
    }

    function SaidaFluxoCaixa(Nivel, IdNivelZero, IdNivelUm, IdNivelDois, IdNivelTres, IdNivelQuatro) {
        var data = {
            funcao: "SaidaFluxoCaixa",
            IdNivelZero: IdNivelZero,
            IdNivelUm: IdNivelUm,
            IdNivelDois: IdNivelDois,
            IdNivelTres: IdNivelTres,
            IdNivelQuatro: IdNivelQuatro,
            Nivel: Nivel
        };
        ChamaAjax(data, function(Resposta) {
            console.log(Resposta);
            var Resposta = JSON.parse(Resposta);
            if (Resposta.Status == "Ok") {
                if (Nivel == "5") {
                    Respostas = Resposta.Resposta;

                    cont = Respostas.length;
                    $('#tableSaidaFluxo tr').slice(1).remove();
                    for (passo = 0; passo < cont; passo++) {
                        addSFluxo(Respostas[passo].Id, Respostas[passo].Produto,
                        Respostas[passo].Unidade,Respostas[passo].Quantidade,Respostas[passo].Valor);
                    }
                } else {
                    $("#DivCanvasPai").html("");
                    CanvasPai = '<canvas id="SaidaPai" onclick="VoltaPai(' + Nivel + ')" width="300" height="300"></canvas>';
                    $("#DivCanvasPai").html(CanvasPai);
                    var ctx = document.getElementById("SaidaPai");
                    var SaidaPai = new Chart(ctx, {
                        type: 'doughnut',
                        data: {}
                    });
                    RespostasPai = Resposta.Resposta.Pai;
                    cont = RespostasPai.length;
                    Valores = [];
                    Nome = [];
                    Cores = [];

                    for (passo = 0; passo < cont; passo++) {
                        Valores.push(RespostasPai[passo].Valor);
                        Nome.push(RespostasPai[passo].Nome);
                        Cores.push(getRandomColor());
                        addLabels(SaidaPai, RespostasPai[passo].Nome);
                    }
                    addData(SaidaPai, Nome, Cores, Valores);

                    $("#DivCanvasFilho").html("");
                    RespostasFilho = Resposta.Resposta.Filho;
                    cont = RespostasFilho.length;
                    for (passo = 0; passo < cont; passo++) {
                        Valores = [];
                        Nome = [];
                        Cores = [];
                        IdPai = RespostasFilho[passo].IdPai;
                        CanvasPai = '<div class="col-lg-6"  onclick="AvancaFilho(' + Nivel.trim() + ',' + IdPai + ')"><center><h3>' +
                            RespostasFilho[passo].NomePai + '</h3></center><canvas id="' + IdPai + '"></canvas></div>';
                        $("#DivCanvasFilho").append(CanvasPai);
                        var ctx = document.getElementById(IdPai);
                        var IdPai = new Chart(ctx, {
                            type: 'doughnut',
                            data: {}
                        });

                        RespostasFilhoDados = RespostasFilho[passo].Dados;
                        contDados = RespostasFilhoDados.length;

                        for (passoDados = 0; passoDados < contDados; passoDados++) {
                            Valores.push(RespostasFilhoDados[passoDados].Valor);
                            Nome.push(RespostasFilhoDados[passoDados].Nome);
                            Cores.push(getRandomColor());
                            addLabels(IdPai, RespostasFilhoDados[passoDados].Nome);
                        }

                        addData(IdPai, Nome, Cores, Valores);
                    }
                }


            } else {

            }
        }, function(error) {
            console.log(error);
        });
    }

    function AvancaFilho(Nivel, Id) {
        if (Nivel == "0") {
            IdNivelZero = Id;
            Nivel = "1";
            $("#DivTableSaida").hide();
        } else if (Nivel == "1") {
            IdNivelUm = Id;
            Nivel = "2";
            $("#DivTableSaida").hide();
        } else if (Nivel == "2") {
            IdNivelDois = Id;
            Nivel = "3";
            $("#DivTableSaida").hide();
        } else if (Nivel == "3") {
            IdNivelTres = Id;
            Nivel = "4";
            $("#DivTableSaida").hide();
        } else if (Nivel == "4") {
            IdNivelQuatro = Id;
            Nivel = "5";
            $("#DivTableSaida").show();
        }

        SaidaFluxoCaixa(Nivel, IdNivelZero, IdNivelUm, IdNivelDois, IdNivelTres, IdNivelQuatro);

    }



    function VoltaPai(Nivel) {
        if (Nivel == "1") {
            Nivel = "0";
        } else if (Nivel == "2") {
            Nivel = "1";
        } else if (Nivel == "3") {
            Nivel = "2";
        } else if (Nivel == "4") {
            Nivel = "3";
        } else if (Nivel == "5") {
            Nivel = "4";
            $("#DivTableSaida").hide();
        }
        SaidaFluxoCaixa(Nivel, IdNivelZero, IdNivelUm, IdNivelDois, IdNivelTres, IdNivelQuatro);
    }



    function getRandomColor() {
        var letters = '0123456789ABCDEF';
        var color = '#';
        for (var i = 0; i < 6; i++) {
            color += letters[Math.floor(Math.random() * 16)];
        }
        return color;
    }

    function addLabels(chart, labels) {
        chart.data.labels.push(labels);
        chart.update();

    }

    function addData(chart, label, color, data) {
        chart.data.datasets.push({
            label: label,
            backgroundColor: color,
            data: data
        });
        chart.update();
    }
    $("#DivTableSaida").hide();
</script>
<script src="<?php echo $urlPrincipal; ?>js/javaScript.js"></script>
<!-- <script src="<?php echo $urlPrincipal; ?>pages/relatorios/javaScript.js"></script> -->