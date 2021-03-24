<script>
    IdResponsavel = "";
</script>
<div class="col-lg-12" style="padding: 2%;background-color: white;margin-bottom: 5%;">
    <i class="fa fa-angle-double-down" style="font-size:36px;float: right;cursor: pointer" id="ChamaUsuarios"></i>
    <h4 class="text-center">Usuários</h4>
    <hr>
    <div class="DivUsuarios">
        <div class="form-group col-lg-12">
            <div class="form-group col-lg-3">
                <label for="exampleInputEmail1">Username</label>
                <input type="text" class="form-control" id="Username" aria-describedby="emailHelp" name="Username" placeholder="Username">
                <small id="emailHelp" class="form-text text-muted">Senha padrão - @trocarsenha123</small>
            </div>
            <div class="form-group col-lg-4">
                <label for="exampleInputEmail1">Nome</label>
                <input type="text" class="form-control" id="NomeResponsavel" aria-describedby="emailHelp" name="nome" placeholder="Nome">
            </div>
            <div class="form-group col-lg-3">
                <label for="exampleInputEmail1">CPF</label>
                <input type="text" class="form-control" id="Cpf" aria-describedby="emailHelp" name="CPF" placeholder="CPF">
            </div>

        </div>
        <div class="form-group col-lg-12">
            <div class="form-group col-lg-3">
                <label for="exampleInputEmail1">Email</label>
                <input type="email" class="form-control" id="Email" aria-describedby="emailHelp" name="Email" placeholder="Email">
            </div>
            <div class="form-group col-lg-3">
                <label for="exampleInputEmail1">Celular</label>
                <input type="text" class="form-control" id="Celular" aria-describedby="emailHelp" placeholder="Celular">
            </div>
        </div>
        <div class="form-group col-lg-12">
            <div class="form-group col-lg-2">
                <label for="exampleInputEmail1">CEP</label>
                <input type="text" class="form-control" id="Cep" aria-describedby="emailHelp" name="CEP" placeholder="CEP">
            </div>
            <div class="form-group col-lg-3">
                <label for="exampleInputEmail1">Endereço</label>
                <input type="text" class="form-control" id="Endereco" aria-describedby="emailHelp" name="Endereco" placeholder="Endereco">
            </div>
            <div class="form-group col-lg-1">
                <label for="exampleInputEmail1">Numero</label>
                <input type="text" class="form-control" id="Numero" aria-describedby="emailHelp" name="Numero" placeholder="Numero">
            </div>
            <div class="form-group col-lg-3">
                <label for="exampleInputEmail1">Complemento</label>
                <input type="text" class="form-control" id="Complemento" aria-describedby="emailHelp" name="Complemento" placeholder="Complemento">
            </div>
            <div class="form-group col-lg-3">
                <label for="exampleInputEmail1">Bairro</label>
                <input type="text" class="form-control" id="Bairro" aria-describedby="emailHelp" name="Bairro" placeholder="Bairro">
            </div>
        </div>
        <div class="form-group col-lg-12">
            <div class="form-group col-lg-2">
                <label for="exampleInputPassword1">UF</label>
                <select class="selectpicker col-12" data-live-search="true" id="Uf">
                    <option value="">Selecione um estado</option>
                </select>
            </div>
            <div class="form-group col-lg-4">
                <label for="exampleInputPassword1">Cidade</label>
                <select class="selectpicker col-12" data-live-search="true" id="Cidade">
                    <option value="">Selecione uma cidade</option>
                </select>
            </div>
            <div class="form-group col-lg-3">
                <label for="exampleInputPassword1">Lincença</label>
                <select class="selectpicker col-12" data-live-search="true" id="Licenca">
                    <option value="">Selecione a licença</option>
                </select>
            </div>
            <div class="form-group col-lg-3">
                <label for="exampleInputPassword1">Status</label><br>
                <label class="switch">
                    <input type="checkbox" id="Status" checked="checked">
                    <span class="slider round"></span>
                </label>
            </div>
        </div>
        <div class="form-group col-lg-12">
            <button type="button" onclick="salvaAtualizaDeletaResponsavel(IdResponsavel, '1')" class="btn btn-primary">Salvar</button>
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
                            <th>Status</th>
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