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
        //==0 - WEB ====== 1 - APP ==========
        $Dispositivo = $_POST["Dispositivo"];
        $Usuario = $_POST["Usuario"];
        $Token = $_POST["Token"];
        if (($Usuario == null || $Usuario == "") && $funcao != "Logar") {
            $retorno = array("Status" => "erro", "Resposta" => "Nenhum usuário detectado");
            echo json_encode($retorno);
            return;
        } else {

            $url = "https://seedgestaorural.com/API/Token/index.php";

            $obj = http_build_query(array(

                "verificacao" => $verificacao,

                "funcao" => "ValidaToken",

                "Token" => "$Token",

                "Usuario" => "$Usuario",

                "Dispositivo" => "$Dispositivo",

            ));

            $curl  = curl_init();

            curl_setopt($curl, CURLOPT_URL, $url);

            curl_setopt($curl, CURLOPT_POST, 1);

            curl_setopt($curl, CURLOPT_POSTFIELDS, $obj);

            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

                   $RetonaToken = curl_exec($curl);
            curl_close($curl);
           $RetonaToken= str_replace("Array","",$RetonaToken);
            $RetonaToken = json_decode($RetonaToken); 
            if ($RetonaToken->Status == "Ok") {
                if ($funcao == "RetornaEstados") {
                    RetornaEstados($Usuario, $link);
                } else if ($funcao == "RetornaMunicipios") {
                    $Uf = $_POST["Uf"];
                    RetornaMunicipios($Usuario, $Uf, $link);
                } else {
                    $retorno = array("Status" => "erro", "Resposta" => "Nenhuma função encontrada");
                    echo json_encode($retorno);
                }
            }else{
             $retorno = array("Status" => "erro", "Resposta" => "Token Inválido","Token"=>"999");
                echo json_encode($retorno);
            }
        }
    }
} else {
    $retorno = array("Status" => "erro", "Resposta" => "Chave de acesso");
    echo json_encode($retorno);
}
function RetornaMunicipios($Usuario, $Uf, $link)
{
    $sql = "select * from Municipio where Uf='$Uf'";
    $result = $link->query($sql);
    $respostas = array();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $resposta = array(
                "Id" => $row['Id'],
                "Nome" => $row['Nome']
            );
            array_push($respostas, $resposta);
        }
        resgistraLog($link, "endereco", "RetornaEstados", "Ok",  "Retorna Municipio", $Usuario);
        $retorno = array("Status" => "Ok", "Resposta" => $respostas);
    } else {
        resgistraLog($link, "endereco", "RetornaEstados", "Erro",  "Retorna Municipio", $Usuario);
        $retorno = array("Status" => "Erro", "Resposta" => "Nenhum Municipio encontrado");
    }
    echo json_encode($retorno);
}
function RetornaEstados($Usuario, $link)
{

    $sql = "select * from Estado";
    $result = $link->query($sql);
    $respostas = array();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $resposta = array(
                "Id" => $row['Id'],
                "Uf" => $row['Uf']
            );
            array_push($respostas, $resposta);
        }
        resgistraLog($link, "endereco", "RetornaEstados", "Ok",  "Retorna estados", $Usuario);
        $retorno = array("Status" => "Ok", "Resposta" => $respostas);
    } else {
        resgistraLog($link, "endereco", "RetornaEstados", "Erro",  "Retorna estados", $Usuario);
        $retorno = array("Status" => "Erro", "Resposta" => "Nenhum estado encontrado");
    }
    echo json_encode($retorno);
}
