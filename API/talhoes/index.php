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
                if ($funcao == "CadastraAtualizaDeletaTalhao") {
                    $Id = $_POST["Id"];
                    $Nome = $_POST["Nome"];
                    $Variedade = $_POST["Variedade"];
                    $NewVariedade = $_POST["NewVariedade"];
                    $Localizaocao = $_POST["Localizaocao"];
                    $Acao = $_POST["Acao"];
                    $Hectare = $_POST["Hectare"];
                    $Zona = $_POST["Zona"];
                    $Status = $_POST["Status"];
                    CadastraAtualizaDeletaTalhao($Usuario, $Acao, $Localizaocao, $NewVariedade, $Variedade, $Hectare, $Id, $Zona, $Status, $link);
                } else if ($funcao == "ResetaCiclo") {
                    $Senha = $_POST["Senha"];
                    $fazenda = $_POST["Fazenda"];
                    ResetaCiclo($link, $Usuario, $Senha, $fazenda);
                } else if ($funcao == "RetornaCiclo") {
                    RetornaCiclo($link, $Usuario);
                } else if ($funcao == "RetornatalhoesTalhoesDashboard") {
                    $Id = $_POST["Id"];
                    RetornatalhoesTalhoesDashboard($Usuario, $Id, $link);
                } else if ($funcao == "RetornaVeiculos") {
                    RetornaVeiculos($Usuario, $link);
                } else if ($funcao == "RetornatalhoesZonas") {
                    $Talhao = $_POST["Talhao"];
                    RetornatalhoesZonas($Usuario, $link, $Talhao);
                } else if ($funcao == "RetornatalhoesTalhoes") {
                    $Id = $_POST["Id"];
                    RetornatalhoesTalhoes($Usuario, $Id, $link);
                } else if ($funcao == "RetornaPlantacao") {
                    $Id = $_POST["Id"];
                    RetornaPlantacao($Usuario, $Id, $link);
                } else {
                    $retorno = array("Status" => "erro", "Resposta" => "Nenhuma função encontrada");
                    echo json_encode($retorno);
                }
            } else {
                $retorno = array("Status" => "erro", "Resposta" => "Token Inválido", "Token" => "999");
                echo json_encode($retorno);
            }
        }
    }
} else {
    $retorno = array("Status" => "erro", "Resposta" => "Chave de acesso");
    echo json_encode($retorno);
}
function RetornaPlantacao($Usuario, $Id, $link)
{
    if ($Id == null || $Id == "") {
        $sql = "select * from Plantacao";
    } else {
        $sql = "select * from Plantacao where Id='$Id'";
    }
    $result = $link->query($sql);
    $Respostas = array();
    while ($row = $result->fetch_assoc()) {
        $Reposta = array("Id" => $row["Id"], "Nome" => $row["Nome"]);
        array_push($Respostas, $Reposta);
    }
    $retorno = array("Status" => "Ok", "Resposta" => $Respostas);
    echo json_encode($retorno);
}
function RetornaVeiculos($Usuario, $link)
{
    $sql = "select Responsavel_Id from Usuarios where Id='$Usuario'";
    $result = $link->query($sql);
    $row = $result->fetch_assoc();
    $reponsavel = $row["Responsavel_Id"];
    $sql = "select * from Veiculos where Responsavel_Id='$reponsavel'";
    $result = $link->query($sql);
    $Respostas = array();
    while ($row = $result->fetch_assoc()) {
        $Reposta = array("Id" => $row["Id"], "Nome" => $row["Nome"], "Responsavel_Id" => $row["Responsavel_Id"]);
        array_push($Respostas, $Reposta);
    }
    $retorno = array("Status" => "Ok", "Resposta" => $Respostas);
    echo json_encode($retorno);
}
function RetornatalhoesZonas($Usuario, $link, $Talhao)
{
    $sql = "select * from Zonas where Talhao_Id='$Talhao'";

    $result = $link->query($sql);
    $Respostas = array();
    while ($row = $result->fetch_assoc()) {
        $Reposta = array("Id" => $row["Id"], "Nome" => $row["Nome"], "Talhao_Id" => $row["Talhao_Id"]);
        array_push($Respostas, $Reposta);
    }
    $retorno = array("Status" => "Ok", "Resposta" => $Respostas);
    echo json_encode($retorno);
}
function RetornaCiclo($link, $Usuario)
{
    $sql = "select HC.Id,US.Nome as Usuario,HC.Data from HistoricoCiclo HC
     JOIN Usuarios US ON HC.Usuario = US.Id";
    $result = $link->query($sql);
    if ($result->num_rows > 0) {
        $Respostas = array();
        while ($row = $result->fetch_assoc()) {
            $Resposta = array(
                "Id" => $row["Id"],
                "Usuario" => $row["Usuario"],
                "Data" => date('d-m-Y', strtotime($row["Data"]))
            );
            array_push($Respostas, $Resposta);
        }
        resgistraLog($link, "talhões", "RetornaCiclo", "Ok",  "Retorna Reset", $Usuario);
        $retorno = array("Status" => "Ok", "Resposta" => $Respostas);
    } else {
        resgistraLog($link, "talhões", "RetornaCiclo", "Erro",  "Nenhum Reset Encontrado", $Usuario);
        $retorno = array("Status" => "Erro", "Resposta" => "Nenhum reset encontrado");
    }
    echo json_encode($retorno);
}
function ResetaCiclo($link, $Usuario, $Senha, $fazenda)
{
    $sql = "select Id from Usuarios where Id='$Usuario' and Senha='$Senha' and Permissao in ('0','999')";
    $result = $link->query($sql);
    if ($result->num_rows > 0) {
        $sql = "UPDATE Talhoes SET Corte=Corte+1 WHERE Status='0'";
        $sqlFazenda = "UPDATE Fazenda SET Corte=Corte+1 WHERE Id='$fazenda'";
        if ($link->query($sql) === TRUE || $link->query($sqlFazenda) === TRUE) {
            $data = date("Y-m-d");
            $sql = "INSERT INTO HistoricoCiclo (Usuario, Data) VALUES ('$Usuario', '$data')";
            if ($link->query($sql) === TRUE) {
                resgistraLog($link, "talhoes", "ResetaCiclo", "Ok", "Ciclo resetado", $Usuario);
                $retorno = array("Status" => "Ok", "Resposta" => "Ciclo resetado");
            } else {
                resgistraLog($link, "talhoes", "ResetaCiclo", "Erro", "Erro  ao salvar histórico ciclo", $Usuario);
                $retorno = array("Status" => "Erro", "Resposta" => "Erro  ao salvar histórico ciclo");
            }
        } else {
            resgistraLog($link, "talhoes", "ResetaCiclo", "Erro", "Erro  ao resetar ciclo", $Usuario);
            $retorno = array("Status" => "Erro", "Resposta" => "Erro ao resetar ciclo");
        }
    } else {
        resgistraLog($link, "talhoes", "ResetaCiclo", "Erro", "Usuário inválido para reset", $Usuario);
        $retorno = array("Status" => "Erro", "Resposta" => "Usuário inválido para reset");
    }
    echo json_encode($retorno);
}
function RetornatalhoesTalhoesDashboard($Usuario, $Id, $link)
{
    $sql = "select * from Talhoes where Status<>'3'";
    $result = $link->query($sql);
    $respostas = array();
    $Localizaocoes = array();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $Localizaocao = json_decode(str_replace('\"', '', $row['Localizaocao']));
            array_push($Localizaocoes, $Localizaocao);
        }
        $resposta = array(
            "Localizaocao" => $Localizaocoes
        );
        array_push($respostas, $resposta);
        resgistraLog($link, "talhões", "RetornatalhoesTalhoes", "Ok",  "Retorna talhões", $Usuario);
        $retorno = array("Status" => "Ok", "Resposta" => $respostas);
    } else {
        resgistraLog($link, "talhões", "RetornatalhoesTalhoes", "Erro",  "Nenhuma talhões", $Usuario);
        $retorno = array("Status" => "Erro", "Resposta" => "Nenhuma categoria");
    }
    echo json_encode($retorno);
}
function RetornatalhoesTalhoes($Usuario, $Id, $link)
{
    if ($Id == null || $Id == "") {
        $sql = "select T.*,Z.Nome as NomeZona from Talhoes T
        JOIN Zonas Z on T.Zona = Z.Id
         where T.Status<>'3'";
    } else {
        $sql = "select * from Talhoes where Status<>'3' and Id='$Id'";
    }
    $result = $link->query($sql);
    $respostas = array();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $resposta = array(
                "Id" => $row['Id'],
                "Nome" => $row['Nome'],
                "Corte" => $row['Corte'],
                "Hectare" => $row['Hectare'],
                "Variedade" => $row['Variedade'],
                "Localizaocao" => json_decode(str_replace('\"', '', $row['Localizaocao'])),
                "Zona" => $row['Zona'],
                "NomeZona" => $row['NomeZona']
            );
            array_push($respostas, $resposta);
        }
        resgistraLog($link, "talhões", "RetornatalhoesTalhoes", "Ok",  "Retorna talhões", $Usuario);
        $retorno = array("Status" => "Ok", "Resposta" => $respostas);
    } else {
        resgistraLog($link, "talhões", "RetornatalhoesTalhoes", "Erro",  "Nenhuma talhões", $Usuario);
        $retorno = array("Status" => "Erro", "Resposta" => "Nenhum talhão encontrado");
    }
    echo json_encode($retorno);
}
function CadastraAtualizaDeletaTalhao($Usuario, $Acao, $Localizaocao, $NewVariedade, $Variedade, $Hectare, $Id, $Zona, $Status, $link)
{

    if ($Variedade == null || $Variedade == "") {
        $sql = "INSERT INTO Plantacao (Nome)
        VALUES ('$NewVariedade')";
        $link->query($sql);
        $last_id = $link->insert_id;
        $Variedade = $last_id;
    }

    $sql = "select F.Corte from Fazenda F
    JOIN Zonas Z on F.Id = Z.Fazenda_Id where Z.Id='$Zona'";

    $result = $link->query($sql);
    $row = $result->fetch_assoc();
    $Corte = $row["Corte"];


    if ($Acao == "1") {
        foreach ($Localizaocao as $Values) {
            $loc = $Values[0];
            $sql = "INSERT INTO Talhoes
        (Corte,Hectare,Localizaocao,Zona,Variedade,Status)
        VALUES
        ('$Corte','$Values[1]','$loc','$Zona','$Variedade','0')";
            $link->query($sql);
        }
        $StatusAcao = "Cadastrado";
    } else if ($Acao == "2") {
        $sql = "UPDATE Talhoes SET
        Nome='$Nome',Hectare='$Hectare',Localizaocao='$Localizaocao',Safra='$Safra',Fazenda='$Fazenda',Status='$Status'
        WHERE Id='$Id'";
        $link->query($sql);
        $StatusAcao = "Atualizados";
    } else {
        $sql = "UPDATE Talhoes SET Status='3' WHERE Id='$Id'";
        $link->query($sql);
        $StatusAcao = "Deletado";
    }

    resgistraLog($link, "talhoes", "CadastraAtualizaDeleta", "Ok",  "Talhão : " . $StatusAcao, $Usuario);
    $retorno = array("Status" => "Ok", "Resposta" => "Talhão : " . $sql);

    echo json_encode($retorno);
}
