function ChamaAjax(data, response, err) {
    var Respostas;
    $.ajax({
        url: '../../function/API.php',
        type: 'POST',
        data: data,
        beforeSend: function () {
            console.log('Carregando...');
        },
        success: function (rsp) {
            var Resposta = JSON.parse(rsp);
            if (Resposta.Status != "Ok") {
                if (Resposta.Token == "999") {
                    var data = { funcao: "DesconectUser" };
                    ChamaAjax(data, function (Resposta) {
                        window.location.href = "../../index.php";
                    }, function (error) {
                        console.log(error);
                    });
                } else {
                    response(rsp);
                }
            } else {
                response(rsp);
            }
        },
        error: function (a, b, c) {
            alert('Erro: ' + a['status'] + ' ' + c);
        }
    });
}

function ChamaAjaxTrocaSenha(data, response, err) {
    var Respostas;
    $.ajax({
        url: '../../../function/API.php',
        type: 'POST',
        data: data,
        beforeSend: function () {
            console.log('Carregando...');
        },
        success: function (rsp) {

            var Resposta = JSON.parse(rsp);
            if (Resposta.Token == "999") {
                var data = { funcao: "DesconectUser" };
                ChamaAjax(data, function (Resposta) {
                    window.location.href = "../../index.php";
                }, function (error) {
                    console.log(error);
                });
            } else {
                response(rsp);
            }
        },
        error: function (a, b, c) {
            alert('Erro: ' + a['status'] + ' ' + c);
        }
    });
}