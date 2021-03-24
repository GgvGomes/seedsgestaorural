<?php
include '../../includes/conexoes/index.php';
?>
<script>
    var IdFrota = "";
</script>
<div class="col-lg-12" style="background-color: white;margin-bottom: 2%;">
    <i class="fa fa-angle-double-down" style="font-size:36px;float: right;" id="ChamaCategoria"></i>
    <h4 class="text-center">Frota</h4>
    <hr>
    <div class="DivCategoria">
        <div class="form-group col-lg-12">
            <div class="col-lg-12">
                <div class="form-group col-lg-3">
                    <label>Nome</label>
                    <input type="text" class="form-control" id="Marca" placeholder="Nome">
                </div>
                <div class="form-group col-lg-3">
                    <label>Nome</label>
                    <input type="text" class="form-control" id="Nome" placeholder="Marca">
                </div>
                <div class="form-group col-lg-2">
                    <label>Modelo</label>
                    <input type="date" class="form-control" id="Modelo" placeholder="Modelo">
                </div>
                <div class="form-group col-lg-2">
                    <label>Placa</label>
                    <input type="text" class="form-control" id="Placa" placeholder="Placa">
                </div>
                <div class="form-group col-lg-2">
                    <label>Numero Frota</label>
                    <input type="number" class="form-control" id="NumeroFrota" placeholder="Numero Frota">
                </div>
                <div class="form-group col-lg-3">
                    <label>Km Inicial</label>
                    <input type="number" class="form-control" id="KmInicial" placeholder="Km Inicial">
                </div>
                <div class="form-group col-lg-3">
                    <label>Km Atual</label>
                    <input type="number" class="form-control" id="KmAtual" placeholder="Km Atual">
                </div>
                <div class="form-group col-lg-3">
                    <label>Ano</label>
                    <input type="number" class="form-control" id="Ano" placeholder="Ano">
                </div>
                <div class="form-group col-lg-3">
                    <label>Status</label>
                    <select class="selectpicker col-12" data-live-search="true" id="Status">
                        <option value="0">Ativo</option>
                        <option value="1">Inativo</option>
                    </select>
                </div>
                <?php
                if ($Permissao_Seeds == 999) {
                ?>
                    <div class="form-group col-lg-3">
                        <label>Responsável</label>
                        <select class="selectpicker col-12" data-live-search="true" id="Responsavel">
                        </select>
                    </div>
                <?php
                }
                ?>
            </div>

        </div>


        <div class="form-group col-lg-12">
            <button type="button" class="btn btn-primary" onclick="CadastraAtualizaDeletaFrota(Acao , IdFrota)">Salvar</button>
        </div>
        <div class="col-lg-12">
            <center>
                <h3 class="titulo text-lg-center">Frota</h3>
                <hr>
            </center>
            <div class="col-lg-12 col-xs-12 tableFixHead">
                <table id="tableProdutos" style="width: 100%;">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Nome</th>
                            <th>Marca</th>
                            <th>Modelo</th>
                            <th>Placa</th>
                            <th>Editar</th>
                            <th>Deleta</th>
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
<script src="<?php echo $urlPrincipal; ?>pages/frotas/javaScript.js"></script>