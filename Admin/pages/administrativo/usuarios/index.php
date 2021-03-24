<?php
include '../../../includes/conexoes/index.php';
?>

<script>
    IdUsuario = "";
</script>

<div class="col-lg-12" style="background-color: white;">
    <i class="fa fa-angle-double-down" style="font-size:36px;float: right;" id="ChamaUsuarios"></i>
    <h4 class="text-center">Usuários</h4>
    <hr>
    <div class="DivUsuarios">
        <div class="form-group col-lg-3">
            <label for="exampleInputEmail1">Nome</label>
            <input type="text" class="form-control" id="Nome" aria-describedby="emailHelp" name="nome" placeholder="Nome">
            <small id="emailHelp" class="form-text text-muted">Senha padrão - @trocarsenha123</small>
        </div>
        <div class="form-group col-lg-3">
            <label for="exampleInputPassword1">Permissão</label>
            <select class="selectpicker col-12" data-live-search="true" id="Permissao">
                <option value="0">Proprietário</option>
                <option value="1">Gerente</option>
                <option value="2">Operador</option>
                <option value="2">Manutenção</option>
                <option value="2">Teceirizado</option>
            </select>
        </div>

        <div class="form-group col-lg-3">
            <label for="exampleInputPassword1">Responsável</label>
            <select class="selectpicker col-12" data-live-search="true" id="Responsavel">
            </select>
        </div>
        <div class="form-group col-lg-12">
            <button type="button" onclick="CadastraAtualizaDeletaUsuarios(IdUsuario, '1')" class="btn btn-primary">Salvar</button>
        </div>
        <div class="col-lg-12">
            <center>
                <h3 class="titulo text-lg-center">Usuários</h3>
                <hr>
            </center>
            <div class="col-lg-12 col-xs-12 tableFixHead">
                <table id="tableUsuarios" style="width: 100%;">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Nome</th>
                            <th>Resetar</th>
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
<script src="<?php echo $urlPrincipal; ?>js/javaScript.js"></script>
<script src="<?php echo $urlPrincipal; ?>pages/administrativo/usurarios/javaScript.js"></script>