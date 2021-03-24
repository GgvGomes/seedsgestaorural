<?php
include "../conexoes/index.php";
include "../logs/index.php";
header('Access-Control-Allow-Origin: *');
$verificacao = filter_input(INPUT_POST, 'verificacao');

if ($verificacao == $chaveApi) {
    $funcao = filter_input(INPUT_POST, 'funcao');
    if ($funcao == null || $funcao == "") {
        $retorno = array("Status" => "erro", "Resposta" => "Nenhuma função encontrada");
        echo json_encode($retorno);
    } else {
        if ($funcao == "EnviaEmail") {
            $UsuarioCadastro = $_POST["UsuarioCadastro"];
            $SenhaCadastro = $_POST["SenhaCadastro"];
            $Destinatario = $_POST["Destinatario"];
            $Assunto = $_POST["Assunto"];
            $Tamplate = $_POST["Tamplate"];
            $linkBotao = $_POST["linkBotao"];
            EnviaEmail($linkBotao, $urlApiA6, $chaveApiA6, $Tamplate, $UsuarioCadastro, $SenhaCadastro, $Destinatario, $Assunto, $Logo, $Port, $Host, $Password, $Username);
        } else if ($funcao == "ResetaSenha") {
            $Usuario = $_POST["Usuario"];
            $Id = $_POST["Id"];
            ResetaSenha($Usuario, $Id, $link);
        } else if ($funcao == "RetornaLicenca") {
            $Id = $_POST["Id"];
            RetornaLicenca($Usuario, $Id, $link);
        } else if ($funcao == "RetornaResponsavel") {
            $Id = $_POST["Id"];
            RetornaResponsavel($Usuario, $Id, $link);
        } else {
            $retorno = array("Status" => "erro", "Resposta" => "Nenhuma função encontrada");
            echo json_encode($retorno);
        }
    }
} else {
    $retorno = array("Status" => "erro", "Resposta" => "Chave de acesso");
    echo json_encode($retorno);
}
function EnviaEmail($linkBotao, $urlApiA6, $chaveApiA6, $Tamplate, $UsuarioCadastro, $SenhaCadastro, $Destinatario, $Assunto, $Logo, $Port, $Host, $Password, $Username)
{

    $url = $urlApiA6 . "EnviaEmail/EnvioOficialA6.php";
    $obj = http_build_query(array(
        "verificacao" => $chaveApiA6,
        "Host" => "$Host",
        "Username" => "$Username",
        "Password" => "$Password",
        "destinatario" => "$Destinatario",
        "assunto" => "$Assunto",
        "logo" => "$Logo",
        "Port" =>  "$Port",
        "LinkBotao" => "$linkBotao",
        "FuncaoTemplate" => "$Tamplate",
        "UsuarioCadastro" => "$UsuarioCadastro",
        "SenhaCadastro" => "$SenhaCadastro",
        "funcao" => "EnviaEmail"
    ));

    $curl  = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $obj);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $Resposta = curl_exec($curl);
    curl_close($curl); 
            echo $Resposta; 
}
