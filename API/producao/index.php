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
            $RetonaToken = str_replace("Array", "", $RetonaToken);
            $RetonaToken = json_decode($RetonaToken);

            if ($RetonaToken->Status == "Ok") {
                if ($funcao == "RetornaProducoes") {
                    RetornaProducoes($link, $Usuario);
                } else if ($funcao == "CadastraProducao") {
                    $Talhao = $_POST["Talhao"];
                    $Unidade = $_POST["Unidade"];
                    $Quantidade = $_POST["Quantidade"];
                    CadastraProducao($link, $Usuario,  $Quantidade, $Unidade, $Talhao);
                } else {
                    $retorno = array("Status" => "erro", "Resposta" => "Nenhuma função encontrada");
                    echo json_encode($retorno);
                }
            } else {
                $retorno = array("Status" => "erro", "Resposta" => $RetonaToken->Resposta, "Token" => "999");
                echo json_encode($retorno);
            }
        }
    }
} else {
    $retorno = array("Status" => "erro", "Resposta" => "Chave de acesso");
    echo json_encode($retorno);
}
function RetornaProducoes($link, $Usuario)
{
    $sql = "select PD.Id,TL.Nome as Talhao,PD.Unidade,PD.Quantidade,PD.Data from Producao PD
    ,US.Nome as Usuario,PT.Nome as Plantacao
    JOIN Talhoes TL on TL.Id=PD.TalhaoId
    JOIN Plantacao PT on PT.Id=TL.Plantacao
    JOIN Usuarios US on US.Id = PD.Usuario";
    $result = $link->query($sql);
    $respostas = array();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $resposta = array(
                "Id" => $row['Id'],
                "Plantacao" => $row['Plantacao'],
                "Talhao" => $row['Talhao'],
                "Unidade" => $row['Unidade'],
                "Quantidade" => $row['Quantidade'],
                "Data" => date('d-m-Y', strtotime($row['Data'])),
                "Usuario" => $row['Usuario']
            );
            array_push($respostas, $resposta);
        }
        resgistraLog($link, "producao", "RetornaProducoes", "Ok",  "Retorna Produção", $Usuario);
        $retorno = array("Status" => "Ok", "Resposta" => $respostas);
    } else {
        resgistraLog($link, "producao", "RetornaProducoes", "Erro",  "Nenhuma produto", $Usuario);
        $retorno = array("Status" => "Erro", "Resposta" => "Nenhuma produto");
    }
    echo json_encode($retorno);
}
function CadastraProducao($link, $Usuario, $Quantidade, $Unidade, $Talhao)
{
    $data = date('Y-m-d');
    $sql = "INSERT INTO Producao
    (TalhaoId,Unidade,Quantidade,Data, Usuario)
     VALUES
     ('$Talhao','$Unidade','$Quantidade','$data','$Usuario')";
    if ($link->query($sql) === TRUE) {
        resgistraLog($link, "producao", "CadastraProducao", "Ok",  "Produtoção inserida", $Usuario);
        $retorno = array("Status" => "Ok", "Resposta" =>  "Produtoção inserida");
    } else {
        resgistraLog($link, "producao", "CadastraProducao", "Erro", "Erro ao cadastrar : " . $sql, $Usuario);
        $retorno = array("Status" => "Erro", "Resposta" => "Erro ao cadastrar");
    }

    echo json_encode($retorno);
}
