<?php
include '../../includes/conexoes/index.php';
?>
<script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCvA6oCDvgYXss9bGDdNPcbIkiLfFpcucQ&callback=initMap&libraries=drawing&v=weekly" defer></script>

<script>
    var IdFazenda = "";
    LocalizacaoMaps = "";
</script>
<div class="col-lg-12" style="background-color: white;">
    <i class="fa fa-angle-double-down" style="font-size:36px;float: right;" id="ChamaUsuarios"></i>
    <h2 class="text-center">Fazendas</h2>
    <hr>
    <div class="DivUsuarios">
        <div class="form-group col-lg-3">
            <label for="exampleInputEmail1">Nome</label>
            <input type="text" class="form-control" id="Nome" aria-describedby="emailHelp" name="nome" placeholder="Nome">
        </div>
        <div class="form-group col-lg-3">
            <label for="exampleInputPassword1">Responsável</label>
            <select class="selectpicker col-12" data-live-search="true" id="Responsavel">
            </select>
        </div>
        <div class="form-group col-lg-4">
            <label for="exampleInputEmail1">Safra</label>
            <div class="form-group col-lg-12" style="margin:0px;padding:0px">
                <select class="selectpicker col-5" data-live-search="true" id="AnoOne">
                    <option value="">Safra</option>
                    <option value="20">20</option>
                    <option value="21">21</option>
                    <option value="22">22</option>
                    <option value="23">23</option>
                    <option value="24">24</option>
                    <option value="25">25</option>
                    <option value="26">26</option>
                    <option value="27">27</option>
                    <option value="28">28</option>
                    <option value="29">29</option>
                    <option value="30">30</option>
                </select>
                <select class="selectpicker col-5" data-live-search="true" id="AnoTwo">
                    <option value="">Safra</option>
                    <option value="20">20</option>
                    <option value="21">21</option>
                    <option value="22">22</option>
                    <option value="23">23</option>
                    <option value="24">24</option>
                    <option value="25">25</option>
                    <option value="26">26</option>
                    <option value="27">27</option>
                    <option value="28">28</option>
                    <option value="29">29</option>
                    <option value="30">30</option>
                </select>
            </div>
        </div>
        <div class="col-lg-12">
            <div id="map" style="width: 100%;height: 500px;"></div>
        </div>
        <div class="form-group col-lg-12">
            <button type="button" class="btn btn-primary" onclick="CadastraAtualizaDeletaFazendas(IdFazenda, '1', LocalizacaoMaps)">Salvar</button>
        </div>
        <div class="col-lg-12">
            <center>
                <h3 class="titulo text-lg-center">Fazendas</h3>
                <hr>
            </center>
            <div class="col-lg-12 col-xs-12 tableFixHead">
                <table id="tableFazenda" style="width: 100%;">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Nome</th>
                            <th>Responsável</th>
                            <th>Deleta</th>
                            <th>Editar</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php include '../../modais/alert.php'; ?>
<script>
    jQuery(function($) {
        $('#AnoOne').selectpicker('refresh');
        $('#AnoTwo').selectpicker('refresh');
    });
</script>
<script src="<?php echo $urlPrincipal; ?>js/javaScript.js"></script>
<script src="<?php echo $urlPrincipal; ?>pages/fazendas/javaScript.js"></script>