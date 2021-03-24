<table class="col-12" id="tableEntrada" style="font-size: 17px;">
    <thead>
        <tr>
            <th>Id</th>
            <th>Fazenda</th>
            <th>Produto</th>
            <th>Quantidade</th>
            <th>TipoUnidade</th>
            <th>Categoria</th>
            <th>Valor</th>
            <th>DataCadatro</th>
        </tr>
    </thead>
    <tbody>
    </tbody>
</table>
<script>
    jQuery(function($) {
        CarregaEntradaPai();
    });

    function CarregaEntradaPai() {
        var data = {
            funcao: "RetornaEntrada"
        };
        ChamaAjax(data, function(Resposta) {
            var Resposta = JSON.parse(Resposta);
            if (Resposta.Status == "Ok") {
                var Respostas = Resposta.Resposta;
                var cont = Resposta.Resposta.length;
                $('#tableEntrada tr').slice(1).remove();

                for (passo = 0; passo < cont; passo++) {
                    add(Respostas[passo].Id, Respostas[passo].Fazenda,
                        Respostas[passo].Produto, Respostas[passo].QuantidadeInicial,
                        Respostas[passo].TipoUnidade, Respostas[passo].Categoria,
                        Respostas[passo].Valor, Respostas[passo].DataCadatro);
                }

            } else {

            }
        }, function(error) {
            console.log(error);
        });
    }

    function add(Id, Fazenda, Produto, Quantidade, TipoUnidade, Categoria, Valor, DataCadatro) {
        var num = document.getElementById("tableEntrada").rows.length;

        var x = document.createElement("tr");

        var a = document.createElement("td");
        var anode = document.createTextNode(Id);
        a.appendChild(anode);
        x.appendChild(a);

        a = document.createElement("td");
        anode = document.createTextNode(Fazenda);
        a.appendChild(anode);
        x.appendChild(a);

        a = document.createElement("td");
        anode = document.createTextNode(Produto);
        a.appendChild(anode);
        x.appendChild(a);

        a = document.createElement("td");
        anode = document.createTextNode(Quantidade);
        a.appendChild(anode);
        x.appendChild(a);

        a = document.createElement("td");
        anode = document.createTextNode(TipoUnidade);
        a.appendChild(anode);
        x.appendChild(a);

        a = document.createElement("td");
        anode = document.createTextNode(Categoria);
        a.appendChild(anode);
        x.appendChild(a);

        a = document.createElement("td");
        anode = document.createTextNode(Valor);
        a.appendChild(anode);
        x.appendChild(a);

        a = document.createElement("td");
        anode = document.createTextNode(DataCadatro);
        a.appendChild(anode);
        x.appendChild(a);


        document.getElementById("tableEntrada").appendChild(x);
    }
</script>