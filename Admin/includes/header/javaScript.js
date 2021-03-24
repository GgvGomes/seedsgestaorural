function DesconectUser() {
    var data = { funcao: "DesconectUser" };
    ChamaAjax(data, function (Resposta) {
        console.log(Resposta);
        window.location.href='https://seedgestaorural.com/admin';
    }, function (error) {
        console.log(error);
    });
}