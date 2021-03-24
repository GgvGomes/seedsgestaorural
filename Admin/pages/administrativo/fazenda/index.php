<?php
include '../../../includes/conexoes/index.php';
?>
<div class="col-lg-12" style="background-color: white;margin-top: 2%;">
    <i class="fa fa-angle-double-down" style="font-size:36px;float: right;" id="ChamaVinculo"></i>
    <h4 class="text-center">Vincula fazenda</h4>
    <hr>
    <div class="DivVinculo">

        <div class="form-group col-lg-3">
            <label for="exampleInputPassword1">Username</label>
            <select class="selectpicker col-12" data-live-search="true" id="UserName">
            </select>
        </div>
        <div class="form-group col-lg-3">
            <label for="exampleInputPassword1">Fazenda</label>
            <select class="selectpicker col-12" data-live-search="true" id="FazendaVinculo">
            </select>
        </div>

        <div class="form-group col-lg-12">
            <button type="button" onclick="UsuarioFazenda(null,'1')" class="btn btn-primary">Salvar</button>
        </div>
        <div class="col-lg-12">
            <center>
                <h3 class="titulo text-lg-center">Vinculos de fazenda com Usuários</h3>
                <hr>
            </center>
            <div class="col-lg-12 col-xs-12 tableFixHead">
                <table id="tableVinculos" style="width: 100%;">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Username</th>
                            <th>Fazenda</th>
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
<script src="<?php echo $urlPrincipal; ?>js/javaScript.js"></script>
<script src="<?php echo $urlPrincipal; ?>pages/administrativo/usurarios/javaScript.js"></script>