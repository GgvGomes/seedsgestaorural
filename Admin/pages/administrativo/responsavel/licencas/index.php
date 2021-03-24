<script>
    var IdLicenca = "";
</script>
<div class="col-lg-12" style="padding: 1%;background-color: white;margin-bottom: 5%;">
    <i class="fa fa-angle-double-down" style="font-size:36px;float: right;cursor: pointer;" id="ChamaLicencas"></i>
    <h4 class="text-center">Licenças</h4>
    <hr>
    <div class="DivLincencas">
        <div class="form-group col-lg-12">

            <div class="form-group col-lg-3">
                <label for="exampleInputEmail1">Nome</label>
                <input type="text" class="form-control" id="Nome" aria-describedby="emailHelp" name="Nome" placeholder="Nome">
            </div>
            <div class="form-group col-lg-3">
                <label for="exampleInputEmail1">Usuários</label>
                <input type="text" class="form-control" id="Usuarios" aria-describedby="emailHelp" name="Usuario" placeholder="Usuários">
            </div>
            <div class="form-group col-lg-3">
                <label for="exampleInputEmail1">Fazendas</label>
                <input type="text" class="form-control" id="Fazendas" aria-describedby="emailHelp" name="Fazendas" placeholder="Fazendas">
            </div>
            <div class="form-group col-lg-3">
                <label for="exampleInputEmail1">Valor</label>
                <input type="text" class="form-control" id="Fazendas" aria-describedby="emailHelp" name="Fazendas" placeholder="Fazendas">
            </div>
        </div>
        <div class="form-group col-lg-12">
            <button type="button" onclick="salvaAtualizaDeletaLicenca(IdLicenca, '1')" id="salvaUsuario" class="btn btn-primary">Salvar</button>
        </div>
        <div class="col-lg-12">
            <center>
                <h3 class="titulo text-lg-center">Licenças</h3>
                <hr>
            </center>
            <div class="col-lg-12 col-xs-12">
                <table id="tableLincencas" style="width: 100%;">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Nome</th>
                            <th>Deletar</th>
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