<?php
include '../../includes/conexoes/index.php';
?>
<?php include '../../modais/alert.php'; ?>
<div class="col-lg-12" style="background-color: white;">
    <i class="fa fa-angle-double-down" style="font-size:36px;float: right;" id="ChamaUsuarios"></i>
    <h4 class="text-center">Rest Cliclo</h4>
    <hr>
    <div class="DivCliclo">
        <div class="form-group col-lg-3">
            <label for="exampleInputEmail1">Senha</label>
            <input type="password" class="form-control" id="Senha" placeholder="Senha">
        </div>
        <div class="form-group col-lg-3">
            <label for="exampleInputEmail1">Fazenda</label>
            <select class="selectpicker form-control" id="Fazenda">
                <option value="">Fazenda</option>
            </select>
        </div>
        <div class="form-group col-lg-3">
            <label for="formGroupExampleInput">Talhão</label>
            <select class="selectpicker form-control" id="Talhao">
                <option value="">Talhão</option>
            </select>
        </div>
        <div class="form-group col-lg-12">
            <button type="button" class="btn btn-primary" onclick="ResetCiclo($('#Senha').val())">Salvar</button>
        </div>
        <div class="col-lg-12">
            <center>
                <h3 class="titulo text-lg-center">Rest Cliclo</h3>
                <hr>
            </center>
            <div class="col-lg-12 col-xs-12 tableFixHead">
                <table id="tableCliclo" style="width: 100%;">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Usuarios</th>
                            <th>Data</th>
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
<script>
    jQuery(function($) {
        RetornaCiclo();
        RetornaFazendas(null);
        RetornatalhoesTalhoes(null);
    });
    function RetornatalhoesTalhoes(Id) {
    var data = {
        funcao: "RetornatalhoesTalhoes",
        Id: Id
    };
    ChamaAjax(data, function (rsp) {

        var Resposta = JSON.parse(rsp);
        var Respostas = Resposta.Resposta;
        if (Resposta.Status == "Ok") {
            $("#Talhao").empty();
            $('#Talhao').selectpicker('refresh');
            var cont = Respostas.length;
            $("#Talhao").append(new Option("Talhão", ""));
            for (passo = 0; passo < cont; passo++) {
                $("#Talhao").append(new Option(Respostas[passo].Nome, Respostas[passo].Id));
            }
            $('#Talhao').selectpicker('refresh');

        } else {
            $("#alert").html(Resposta.Resposta);
            $("#AlertModal").modal("show");
        }
    }, function (error) {
        console.log(error);
    });
}
    function RetornaFazendas(Id) {
        var data = {
            funcao: "RetornaFazendas",
            Id: Id
        };
        ChamaAjax(data, function(Resposta) {
            var Resposta = JSON.parse(Resposta);
            if (Resposta.Status == "Ok") {
                var Respostas = Resposta.Resposta;
                var cont = Resposta.Resposta.length;
                for (passo = 0; passo < cont; passo++) {
                    $("#Fazenda").append(new Option(Respostas[passo].Nome, Respostas[passo].Id));
                }
                $('#Fazenda').selectpicker('refresh');
            } else {
                $('#Fazenda').selectpicker('refresh');
            }
        }, function(error) {
            console.log(error);
        });
    }

    function tableCliclo(Id, Usuario, Data) {
        var num = document.getElementById("tableCliclo").rows.length;

        var x = document.createElement("tr");

        var a = document.createElement("td");
        var anode = document.createTextNode(Id);
        a.appendChild(anode);
        x.appendChild(a);

        a = document.createElement("td");
        anode = document.createTextNode(Usuario);
        a.appendChild(anode);
        x.appendChild(a);

        a = document.createElement("td");
        anode = document.createTextNode(Data);
        a.appendChild(anode);
        x.appendChild(a);

        document.getElementById("tableCliclo").appendChild(x);
    }

    function ResetCiclo(Senha) {
        Fazenda = $("#Fazenda option:selected").val();
        var data = {
            funcao: "ResetaCiclo",
            Senha: Senha,
            Fazenda: Fazenda
        };

        ChamaAjax(data, function(rsp) {
            var Resposta = JSON.parse(rsp);
            alert(Resposta.Resposta);
            if (Resposta.Status == "Ok") {

            } else {
                // $('#tableFazenda tr').slice(1).remove();
            }
        }, function(error) {
            console.log(error);
        });
    }

    function RetornaCiclo(Senha) {
        var data = {
            funcao: "RetornaCiclo"
        };
        ChamaAjax(data, function(rsp) {
            var Resposta = JSON.parse(rsp);
            if (Resposta.Status == "Ok") {
                Respostas = Resposta.Resposta;
                console.log(Respostas);
                var cont = Respostas.length;
                $('#tableCliclo tr').slice(1).remove();
                for (passo = 0; passo < cont; passo++) {
                    tableCliclo(Respostas[passo].Id, Respostas[passo].Usuario,
                        Respostas[passo].Data);
                }
            } else {
                $('#tableCliclo tr').slice(1).remove();
            }
        }, function(error) {
            console.log(error);
        });
    }
</script>