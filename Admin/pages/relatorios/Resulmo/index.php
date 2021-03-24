<div class="col-lg-12">
    <div class="col-lg-6 col-md-6">
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
                <div class="panel-footer">
                    <span class="pull-left">Detalhes</span>
                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                    <div class="clearfix"></div>
                </div>
            </a>
        </div>
    </div>

    <div class="col-lg-6 col-md-6">
        <div class="panel panel-green" style="background-color: red;color: white">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-bar-chart fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
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
</div>
<script>
    jQuery(function($) {
        RetornaProdutosEntradaSaida();
    });

    function RetornaProdutosEntradaSaida() {
        var data = {
            funcao: "RetornaProdutosEntradaSaida"
        };

        ChamaAjax(data, function(rsp) {
            var Resposta = JSON.parse(rsp);

            $("#ComprasMes").html(Resposta.ValorEntrada);
            $("#GastosMes").html(Resposta.ValorSaida);

        }, function(error) {
            console.log(error);
        });
    }
</script>