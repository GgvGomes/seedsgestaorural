<?php
include "../conexoes/index.php";
include "../logs/index.php";
header('Access-Control-Allow-Origin: *');

$verificacao = $_REQUEST['verificacao'];
if ($verificacao == $chaveApi) {
    $funcao = $_REQUEST['funcao'];
    if ($funcao == null || $funcao == "") {
        $retorno = array("Status" => "erro", "Resposta" => "Nenhuma função encontrada");
        echo json_encode($retorno);
    } else {
        //==0 - WEB ====== 1 - APP ==========
        $Dispositivo = $_REQUEST["Dispositivo"];
        $Usuario = $_REQUEST["Usuario"];
        $Token = $_REQUEST["Token"];
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
                if ($funcao == "RetornaEntrada") {
                    RetornaEntrada($link, $Usuario);
                } else if ($funcao == "ResetaCiclo") {
                    $Senha = $_REQUEST["Senha"];
                    ResetaCiclo($link, $Usuario, $Senha);
                } else if ($funcao == "RetornaCiclo") {
                    RetornaCiclo($link, $Usuario);
                } else if ($funcao == "RetornaResumo") {
                    RetornaResumo($link, $Usuario);
                } else if ($funcao == "SaidaFluxoCaixa") {
                    $IdNivelDois = $_REQUEST["IdNivelDois"];
                    $IdNivelUm = $_REQUEST["IdNivelUm"];
                    $Nivel = $_REQUEST["Nivel"];
                    $IdNivelZero = $_REQUEST["IdNivelZero"];
                    $IdNivelTres = $_REQUEST["IdNivelTres"];
                    $IdNivelQuatro = $_REQUEST["IdNivelQuatro"];
                    SaidaFluxoCaixa($link, $Usuario, $Nivel, $IdNivelZero, $IdNivelUm, $IdNivelDois, $IdNivelTres, $IdNivelQuatro);
                } else if ($funcao == "RetornatalhoesTalhoes") {
                    $Id = $_REQUEST["Id"];
                    RetornatalhoesTalhoes($Usuario, $Id, $link);
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
function RetornaEntrada($link, $Usuario)
{
    $sql = "select Responsavel_Id from Usuarios where Id='$Usuario'";
    $result = $link->query($sql);
    $row = $result->fetch_assoc();
    $reponsavel = $row["Responsavel_Id"];

    $data = date("01-m-Y 00:00:00");
    $dataFim = date("31-m-Y 00:00:00");

    $sql = "SELECT
    PD.Valor,PD.Responsavel_Id,FZ.Nome as Fazenda,PD.Nome as Produto,PD.QuantidadeInicial,PD.TipoUnidade,
    PD.DataCadatro,CP.Nome as Categoria,PD.Id
    FROM Fazenda FZ
    JOIN Produtos PD on PD.Fazenda_Id = FZ.Id
    JOIN Categorias_Produtos CP on CP.Id = PD.Categoria
    WHERE FZ.Responsavel_Id='$reponsavel' and PD.DataCadatro >= '$data' and PD.DataCadatro <= '$dataFim'
    ORDER BY PD.Fazenda_Id ASC";
    $result = $link->query($sql);

    $valorEntrada = 0.0;
    $Respostas = array();
    $IdFazenda = "";
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $Resposta = array(
                "Id" => $row["Id"],
                "Fazenda" => $row["Fazenda"],
                "Produto" => $row["Produto"],
                "Valor" => number_format($row["Valor"], 2, ",", "."),
                "QuantidadeInicial" => $row["QuantidadeInicial"],
                "TipoUnidade" => $row["TipoUnidade"],
                "DataCadatro" => date('d-m-Y', strtotime($row["DataCadatro"])),
                "Categoria" => $row["Categoria"],
            );
            array_push($Respostas, $Resposta);
        }
    }
    $retorno = array("Status" => "Ok", "Resposta" => $Respostas);

    echo json_encode($retorno);
}
function RetornaResumo($link, $Usuario)
{
    $sql = "select Responsavel_Id,Permissao from Usuarios where Id='$Usuario'";
    $result = $link->query($sql);
    $row = $result->fetch_assoc();
    $Responsavel_Id = $row["Responsavel_Id"];
    $data = date("Y-m-01 00:00:00");
    $dataFim = date("Y-m-31 00:00:00");
    //======== ENTRADA =======
    if ($row["Permissao"] == "999") {
        $sql = "SELECT Valor from HistoricoDeProdutos
    where DataCadatro >= '$data' and DataCadatro <= '$dataFim' and Tipo='1' and Status='0'";
    } else {
        $sql = "SELECT Valor from HistoricoDeProdutos
        where  Responsavel_Id='$Responsavel_Id' and  DataCadatro >= '$data' and DataCadatro <= '$dataFim' and Tipo='1' and Status='0'";
    }
    $result = $link->query($sql);
    $Entrada = 0.0;
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            // $valorEntrada = $valorEntrada + 1;
            $Entrada = $row["Valor"] + $Entrada;
        }
    } else {
        $Entrada = 0.0;
    }
    // ==== SAIDA ====
    if ($row["Permissao"] == "999") {
        $sql = "SELECT Quantidade,Id_Produto_Saida from HistoricoDeProdutos
    where DataCadatro >= '$data' and DataCadatro <= '$dataFim' and Tipo='0'";
    } else {
        $sql = "SELECT Quantidade,Id_Produto_Saida from HistoricoDeProdutos
        where  Responsavel_Id='$Responsavel_Id' and  DataCadatro >= '$data' and DataCadatro <= '$dataFim' and Tipo='0'";
    }
    $result = $link->query($sql);
    $Saida = 0.0;
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $Id_Produto_Saida = $row["Id_Produto_Saida"];
            $sqlProduto = "SELECT Valor,QuantidadeIncial from HistoricoDeProdutos
            where  Id_Produto_Saida='$Id_Produto_Saida'";
            $resultProduto = $link->query($sqlProduto);
            $rowProduto = $resultProduto->fetch_assoc();

            $Saida = (($rowProduto["Valor"] / $rowProduto["QuantidadeIncial"]) * $row["Quantidade"]) + $Saida;
        }
    } else {
        $Saida = 0;
    }
    $data = date("Y-m-01");
    $Producao = 0.0;
    $sql = "SELECT Quantidade,Unidade FROM Producao WHERE Data>='$data' and Data<='$dataFim'";
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $Producao = $Producao + $row["Quantidade"];
            $Unidade = $row["Unidade"];
        }
    }

    resgistraLog($link, "financeiro", "RetornaSaida", "Ok",  "Retorna saidas", $Usuario);
    $retorno = array("Status" => "Ok",
    "Entrada" =>  number_format($Entrada, 2, ",", "."),
    "Saida" => "R$ " . number_format($Saida, 2, ",", "."),
    "Producao" => number_format($Producao, 2, ",", ".")." ".$Unidade
);

    echo json_encode($retorno);
}
function RetornaSaida($link, $Usuario)
{
    $sql = "select Responsavel_Id from Usuarios where Id='$Usuario'";
    $result = $link->query($sql);
    $row = $result->fetch_assoc();
    $Responsavel_Id = "";
    if ($row["Responsavel_Id"] != "" || $row["Responsavel_Id"] != null) {
        $Responsavel_Id = $Usuario;
        $sql = "select
        HP.Id,HP.DataCadatro,TL.Nome as Talhao,HP.Corte,HP.Quantidade,
        US.Nome,
        PD.Nome,PD.TipoUnidade
        from HistoricoDeProdutos HP
        JOIN Usuarios US on HP.Usuario = US.Id
        JOIN Produtos PD on HP.Produto_Id = PD.Id
        JOIN Talhao TL on HP.Talhao_Id = TL.Id
        where US.Responsavel_Id='$Responsavel_Id'";
    } else {
        $Responsavel_Id = $row["Responsavel_Id"];
        $sql = "select
        HP.Id,HP.DataCadatro,TL.Nome as Talhao,HP.Corte,HP.Quantidade,
        US.Nome as Usuario,
        PD.Nome as Produto,PD.TipoUnidade
        from HistoricoDeProdutos HP
        JOIN Usuarios US on HP.Usuario = US.Id
        JOIN Produtos PD on HP.Produto_Id = PD.Id
        JOIN Talhao TL on HP.Talhao_Id = TL.Id
        where US.Responsavel_Id='$Responsavel_Id'
        or US.Responsavel_Id='$Usuario'";
    }
    $result = $link->query($sql);
    if ($result->num_rows > 0) {
        $Respostas = array();
        while ($row = $result->fetch_assoc()) {
            $Resposta = array(
                "Id" => $row["Id"],
                "Talhao" => $row["Talhao"],
                "Quantidade" => $row["Quantidade"],
                "Corte" => $row["Corte"],
                "Produto" => $row["Produto"],
                "TipoUnidade" => $row["TipoUnidade"],
                "Usuario" => $row["Usuario"],
                "Data" => date('d-m-Y', strtotime($row["DataCadatro"]))
            );
            array_push($Respostas, $Resposta);
        }
        resgistraLog($link, "financeiro", "RetornaSaida", "Ok",  "Retorna saidas", $Usuario);
        $retorno = array("Status" => "Ok", "Resposta" => $Respostas);
    } else {
        resgistraLog($link, "financeiro", "RetornaSaida", "Erro",  "Retorna saidas", $Usuario);
        $retorno = array("Status" => "Erro", "Resposta" => "Nenhum saída encontrada");
    }
    echo json_encode($retorno);
}
function SaidaFluxoCaixa($link, $Usuario, $Nivel, $IdNivelZero, $IdNivelUm, $IdNivelDois, $IdNivelTres, $IdNivelQuatro)
{
    $sql = "select Permissao,Responsavel_Id from Usuarios where Id='$Usuario'";
    $result = $link->query($sql);
    $row = $result->fetch_assoc();
    $Responsavel_Id = $row['Responsavel_Id'];
    $Permissao = $row['Permissao'];
    if ($Nivel == "5") {
        $sql = "SELECT
        PD.QuantidadeInicial,PD.Valor,HP.Quantidade,PD.Nome as NomeProduto,PD.TipoUnidade as Unidade,HP.Id,
        CP.Nome as Nome, HP.Zona_Id as NomePai, VL.Nome as NomeFilhos
            FROM HistoricoDeProdutos HP
        JOIN Produtos PD on PD.Id = HP.Produto_Id
        JOIN Fazenda FZ on PD.Fazenda_Id = FZ.Id
        JOIN Talhoes TL on TL.Id = HP.Talhao_Id
        JOIN Zonas ZN on ZN.Id = HP.Zona_Id
        JOIN Categorias_Produtos CP on CP.Id = PD.Categoria
        JOIN Veiculos VL on VL.Id = HP.Veiculo_Id
        where PD.Fazenda_Id='$IdNivelZero' and HP.Talhao_Id='$IdNivelUm' and HP.Zona_Id='$IdNivelDois'
        and PD.Categoria='$IdNivelTres' and HP.Veiculo_Id='$IdNivelQuatro'
        ORDER BY HP.Veiculo_Id ASC";

        $result = $link->query($sql);
        $Respostas = array();
        while ($row = $result->fetch_assoc()) {
            $Valor = (($row["Valor"] / $row["QuantidadeInicial"]) * $row["Quantidade"]);
            $Resposta = array(
                "Id" => $row["Id"],
                "Produto" => $row["NomeProduto"],
                "Unidade" => $row["Unidade"],
                "Quantidade" => $row["Quantidade"],
                "Valor" => "R$ " . number_format($Valor, 2, ",", ".")
            );
            array_push($Respostas, $Resposta);
        }
        $retorno = array("Status" => "Ok", "Resposta" => $Respostas);
        echo json_encode($retorno);
        return;
    }

    if ($Permissao == "999") {
        if ($Nivel == "0") {
            $sql = "SELECT
            FZ.Id, FZ.Nome as NomePai, HP.Quantidade, HP.Produto_Id,FZ.Id as IdOrdenacaoPai, PD.Valor,
            PD.QuantidadeInicial
            from Fazenda FZ
            JOIN Produtos PD on PD.Fazenda_Id = FZ.Id
            JOIN HistoricoDeProdutos HP on HP.Produto_Id = PD.Id
            ORDER BY FZ.Id ASC";
        } else if ($Nivel == "1") {
            $sql = "SELECT
            PD.QuantidadeInicial,PD.Valor,HP.Quantidade,
            HP.Talhao_Id as IdOrdenacaoPai,TL.Nome as NomePai
            FROM HistoricoDeProdutos HP
            JOIN Produtos PD on PD.Id = HP.Produto_Id
            JOIN Talhoes TL on TL.Id = HP.Talhao_Id
            where PD.Fazenda_Id='$IdNivelZero'
            ORDER BY HP.Talhao_Id ASC";
        } else if ($Nivel == "2") {
            $sql = "SELECT
            PD.QuantidadeInicial,PD.Valor,HP.Quantidade,ZN.Id as IdOrdenacaoPai,
            ZN.Nome as NomePai
            FROM HistoricoDeProdutos HP
            JOIN Produtos PD on PD.Id = HP.Produto_Id
            JOIN Talhoes TL on TL.Id = HP.Talhao_Id
            JOIN Zonas ZN on ZN.Id = HP.Zona_Id
            where PD.Fazenda_Id='$IdNivelZero' and HP.Talhao_Id='$IdNivelUm'
            ORDER BY HP.Zona_Id ASC";
        } else if ($Nivel == "3") {
            $sql = "SELECT
            PD.QuantidadeInicial,PD.Valor,HP.Quantidade,CP.Id as IdOrdenacaoPai,
            CP.Nome as NomePai
            FROM HistoricoDeProdutos HP
                        JOIN Produtos PD on PD.Id = HP.Produto_Id
                        JOIN Fazenda FZ on PD.Fazenda_Id = FZ.Id
                        JOIN Talhoes TL on TL.Id = HP.Talhao_Id
                        JOIN Zonas ZN on ZN.Id = HP.Zona_Id
                        JOIN Categorias_Produtos CP on CP.Id = PD.Categoria
                        where PD.Fazenda_Id='$IdNivelZero' and HP.Talhao_Id='$IdNivelUm' and HP.Zona_Id='$IdNivelDois'
                        ORDER BY PD.Categoria ASC";
        } else if ($Nivel == "4") {
            $sql = "SELECT
            PD.QuantidadeInicial,PD.Valor,HP.Quantidade,HP.Veiculo_Id as IdOrdenacaoPai,
            VL.Nome as NomePai
            FROM HistoricoDeProdutos HP
                        JOIN Produtos PD on PD.Id = HP.Produto_Id
                        JOIN Fazenda FZ on PD.Fazenda_Id = FZ.Id
                        JOIN Talhoes TL on TL.Id = HP.Talhao_Id
                        JOIN Zonas ZN on ZN.Id = HP.Zona_Id
                        JOIN Categorias_Produtos CP on CP.Id = PD.Categoria
                        JOIN Veiculos VL on VL.Id = HP.Veiculo_Id
                        where PD.Fazenda_Id='$IdNivelZero' and HP.Talhao_Id='$IdNivelUm' and HP.Zona_Id='$IdNivelDois'
                        and PD.Categoria='$IdNivelTres'
                        ORDER BY HP.Veiculo_Id ASC";
        }
    } else {
        if ($Nivel == "0") {
            $sql = "SELECT
            FZ.Id, FZ.Nome as NomePai, HP.Quantidade, HP.Produto_Id,FZ.Id as IdOrdenacaoPai, PD.Valor,
            PD.QuantidadeInicial
            from Fazenda FZ
            JOIN Produtos PD on PD.Fazenda_Id = FZ.Id
            JOIN HistoricoDeProdutos HP on HP.Produto_Id = PD.Id
            where FZ.Responsavel_Id='$Responsavel_Id'
            ORDER BY FZ.Id ASC";
        } else if ($Nivel == "1") {
            $sql = "SELECT
            PD.QuantidadeInicial,PD.Valor,HP.Quantidade,
            HP.Talhao_Id as IdOrdenacaoPai,TL.Nome as NomePai
            FROM HistoricoDeProdutos HP
            JOIN Produtos PD on PD.Id = HP.Produto_Id
            JOIN Talhoes TL on TL.Id = HP.Talhao_Id
            where PD.Fazenda_Id='$IdNivelZero' and FZ.Responsavel_Id='$Responsavel_Id'
            ORDER BY HP.Talhao_Id ASC";
        } else if ($Nivel == "2") {
            $sql = "SELECT
            PD.QuantidadeInicial,PD.Valor,HP.Quantidade,ZN.Id as IdOrdenacaoPai,
            ZN.Nome as NomePai
            FROM HistoricoDeProdutos HP
            JOIN Produtos PD on PD.Id = HP.Produto_Id
            JOIN Talhoes TL on TL.Id = HP.Talhao_Id
            JOIN Zonas ZN on ZN.Id = HP.Zona_Id
            where PD.Fazenda_Id='$IdNivelZero' and HP.Talhao_Id='$IdNivelUm' and FZ.Responsavel_Id='$Responsavel_Id'
            ORDER BY HP.Zona_Id ASC";
        } else if ($Nivel == "3") {
            $sql = "SELECT
            PD.QuantidadeInicial,PD.Valor,HP.Quantidade,CP.Id as IdOrdenacaoPai,
            CP.Nome as NomePai
            FROM HistoricoDeProdutos HP
                        JOIN Produtos PD on PD.Id = HP.Produto_Id
                        JOIN Fazenda FZ on PD.Fazenda_Id = FZ.Id
                        JOIN Talhoes TL on TL.Id = HP.Talhao_Id
                        JOIN Zonas ZN on ZN.Id = HP.Zona_Id
                        JOIN Categorias_Produtos CP on CP.Id = PD.Categoria
                        where PD.Fazenda_Id='$IdNivelZero' and HP.Talhao_Id='$IdNivelUm' and HP.Zona_Id='$IdNivelDois' and FZ.Responsavel_Id='$Responsavel_Id'
                        ORDER BY PD.Categoria ASC";
        } else if ($Nivel == "4") {
            $sql = "SELECT
            PD.QuantidadeInicial,PD.Valor,HP.Quantidade,HP.Veiculo_Id as IdOrdenacaoPai,
            VL.Nome as NomePai
            FROM HistoricoDeProdutos HP
                        JOIN Produtos PD on PD.Id = HP.Produto_Id
                        JOIN Fazenda FZ on PD.Fazenda_Id = FZ.Id
                        JOIN Talhoes TL on TL.Id = HP.Talhao_Id
                        JOIN Zonas ZN on ZN.Id = HP.Zona_Id
                        JOIN Categorias_Produtos CP on CP.Id = PD.Categoria
                        JOIN Veiculos VL on VL.Id = HP.Veiculo_Id
                        where PD.Fazenda_Id='$IdNivelZero' and HP.Talhao_Id='$IdNivelUm' and HP.Zona_Id='$IdNivelDois'
                        and PD.Categoria='$IdNivelTres' and FZ.Responsavel_Id='$Responsavel_Id'
                        ORDER BY HP.Veiculo_Id ASC";
        }
    }
    $idTempPai = "";
    $idTempFilho = "";
    $NomePai = "";
    $NomeFilho = "";
    $result = $link->query($sql);
    $Respostas = array("Pai" => array(), "Filho" => array());
    if ($result->num_rows > 0) {
        $ValorIndividual = 0.0;
        $valorSaidaIndividual = 0.0;
        $valorSaidaTotal = 0.0;
        $ValorTotal = 0.0;
        $ValorIndividual = 0.0;
        while ($row = $result->fetch_assoc()) {
            if ($idTempPai == "") {
                $idTempPai = $row["IdOrdenacaoPai"];
            }

            if ($idTempPai != $row["IdOrdenacaoPai"]) {
                if ($Permissao == "999") {
                    if ($Nivel == "0") {
                        $sqlFilho = "SELECT
                        PD.QuantidadeInicial,PD.Valor,HP.Quantidade,PD.Fazenda_Id as IdPai,FZ.Nome as NomePai,
                        HP.Talhao_Id as IdOrdenacaoFilho,TL.Nome as NomeFilhos,HP.Talhao_Id as idTempFilhoDados
                        FROM HistoricoDeProdutos HP
                        JOIN Produtos PD on PD.Id = HP.Produto_Id
                        JOIN Fazenda FZ on PD.Fazenda_Id = FZ.Id
                        JOIN Talhoes TL on TL.Id = HP.Talhao_Id
                        where PD.Fazenda_Id='$idTempPai'
                        ORDER BY HP.Talhao_Id ASC";
                    } else  if ($Nivel == "1") {
                        $sqlFilho = "SELECT
                        PD.QuantidadeInicial,PD.Valor,HP.Quantidade,HP.Talhao_Id as IdPai,
                        HP.Zona_Id as IdOrdenacaoFilho,ZN.Nome as Nome, TL.Nome as NomePai, ZN.Nome as NomeFilhos
                        FROM HistoricoDeProdutos HP
                         JOIN Produtos PD on PD.Id = HP.Produto_Id
                        JOIN Fazenda FZ on PD.Fazenda_Id = FZ.Id
                        JOIN Talhoes TL on TL.Id = HP.Talhao_Id
                        JOIN Zonas ZN on ZN.Id = HP.Produto_Id
                        where PD.Fazenda_Id='$IdNivelZero' and HP.Talhao_Id='$idTempPai'
                        ORDER BY HP.Zona_Id ASC";
                    } else if ($Nivel == "2") {
                        $sqlFilho = "SELECT
                        PD.QuantidadeInicial,PD.Valor,HP.Quantidade,HP.Zona_Id as IdPai,
                        PD.Categoria as IdOrdenacaoFilho,CP.Nome as Nome, TL.Nome as NomePai, CP.Nome as NomeFilhos
                        FROM HistoricoDeProdutos HP
                        JOIN Produtos PD on PD.Id = HP.Produto_Id
                        JOIN Fazenda FZ on PD.Fazenda_Id = FZ.Id
                        JOIN Talhoes TL on TL.Id = HP.Talhao_Id
                        JOIN Zonas ZN on ZN.Id = HP.Zona_Id
                        JOIN Categorias_Produtos CP on CP.Id = PD.Categoria
                        where PD.Fazenda_Id='$IdNivelZero' and HP.Talhao_Id='$IdNivelUm' and HP.Zona_Id='$idTempPai'
                        ORDER BY PD.Categoria ASC";
                    } else if ($Nivel == "3") {
                        $sqlFilho = "SELECT
                        PD.QuantidadeInicial,PD.Valor,HP.Quantidade,PD.Categoria as IdPai,
                        HP.Veiculo_Id as IdOrdenacaoFilho,CP.Nome as Nome, HP.Zona_Id as NomePai, VL.Nome as NomeFilhos
                            FROM HistoricoDeProdutos HP
                        JOIN Produtos PD on PD.Id = HP.Produto_Id
                        JOIN Fazenda FZ on PD.Fazenda_Id = FZ.Id
                        JOIN Talhoes TL on TL.Id = HP.Talhao_Id
                        JOIN Zonas ZN on ZN.Id = HP.Zona_Id
                        JOIN Categorias_Produtos CP on CP.Id = PD.Categoria
                        JOIN Veiculos VL on VL.Id = HP.Veiculo_Id
                        where PD.Fazenda_Id='$IdNivelZero' and HP.Talhao_Id='$IdNivelUm' and HP.Zona_Id='$IdNivelDois'
                        and PD.Categoria='$idTempPai'
                        ORDER BY HP.Veiculo_Id ASC";
                    } else if ($Nivel == "4") {
                        $sqlFilho = "SELECT
                        PD.QuantidadeInicial,PD.Valor,HP.Quantidade,PD.Categoria as IdPai,
                        HP.Veiculo_Id as IdOrdenacaoFilho,CP.Nome as Nome, HP.Zona_Id as NomePai, VL.Nome as NomeFilhos
                            FROM HistoricoDeProdutos HP
                        JOIN Produtos PD on PD.Id = HP.Produto_Id
                        JOIN Fazenda FZ on PD.Fazenda_Id = FZ.Id
                        JOIN Talhoes TL on TL.Id = HP.Talhao_Id
                        JOIN Zonas ZN on ZN.Id = HP.Zona_Id
                        JOIN Categorias_Produtos CP on CP.Id = PD.Categoria
                        JOIN Veiculos VL on VL.Id = HP.Veiculo_Id
                        where PD.Fazenda_Id='$IdNivelZero' and HP.Talhao_Id='$IdNivelUm' and HP.Zona_Id='$IdNivelDois'
                        and PD.Categoria='$IdNivelTres' and HP.Veiculo_Id='$idTempPai'
                        ORDER BY HP.Veiculo_Id ASC";
                    }
                }


                $resultFilho = $link->query($sqlFilho);
                if ($resultFilho->num_rows > 0) {
                    $idTempFilhoDados = "";
                    $DadosFilho = array();
                    while ($rowFilho = $resultFilho->fetch_assoc()) {
                        if ($idTempFilhoDados == "") {
                            $idTempFilhoDados = $rowFilho["IdOrdenacaoFilho"];
                        }
                        if ($idTempFilhoDados != $rowFilho["IdOrdenacaoFilho"]) {
                            $DadosFilhoResposta = array(
                                "Nome" => $NomeFilho,
                                "Valor" => $valorSaidaIndividual
                            );
                            array_push($DadosFilho, $DadosFilhoResposta);
                            $valorSaidaIndividual = 0.0;
                            $idTempFilhoDados = $rowFilho["IdOrdenacaoFilho"];
                        }

                        $NomeFilho = $rowFilho["NomeFilhos"];

                        $valorSaidaIndividual = (($rowFilho["Valor"] / $rowFilho["QuantidadeInicial"]) * $rowFilho["Quantidade"]) + $valorSaidaIndividual;
                        $valorSaidaTotal = (($rowFilho["Valor"] / $rowFilho["QuantidadeInicial"]) * $rowFilho["Quantidade"]) + $valorSaidaTotal;
                    }
                    //=======FILHO=DADOS===========
                    $DadosFilhoResposta = array(
                        "Nome" => $NomeFilho,
                        "Valor" => $valorSaidaIndividual
                    );
                    array_push($DadosFilho, $DadosFilhoResposta);
                    $valorSaidaIndividual = 0.0;
                    $idTempFilhoDados =  "";
                    //=======FILHO============
                    $Resposta = array(
                        "IdPai" => $idTempPai,
                        "NomePai" => $NomePai,
                        "Dados" => $DadosFilho
                    );
                    array_push($Respostas["Filho"], $Resposta);
                    $DadosFilho = array();
                    $idTempFilho = $rowFilho["IdOrdenacaoFilho"];
                    $valorSaidaTotal = 0.0;
                    $valorSaidaIndividual = 0.0;
                    $idTempFilhoDados = "";
                }
                $Resposta = array(
                    "Nome" => $NomePai,
                    "Valor" => $ValorIndividual,
                );
                array_push($Respostas["Pai"], $Resposta);
                $idTempPai = $row["IdOrdenacaoPai"];
                $ValorIndividual = 0.0;
            }
            $ValorIndividual = (($row["Valor"] / $row["QuantidadeInicial"]) * $row["Quantidade"]) + $ValorIndividual;
            $ValorTotal = (($row["Valor"] / $row["QuantidadeInicial"]) * $row["Quantidade"]) + $ValorTotal;
            $NomePai = $row["NomePai"];
            $Id = $row["Id"];
        }

        if ($Permissao == "999") {
            if ($Nivel == "0") {
                $sqlFilho = "SELECT
                PD.QuantidadeInicial,PD.Valor,HP.Quantidade,PD.Fazenda_Id as IdPai,FZ.Nome as NomePai,
                HP.Talhao_Id as IdOrdenacaoFilho,TL.Nome as NomeFilhos,HP.Talhao_Id as idTempFilhoDados
                FROM HistoricoDeProdutos HP
                JOIN Produtos PD on PD.Id = HP.Produto_Id
                JOIN Fazenda FZ on PD.Fazenda_Id = FZ.Id
                JOIN Talhoes TL on TL.Id = HP.Talhao_Id
                where PD.Fazenda_Id='$idTempPai'
                ORDER BY HP.Talhao_Id ASC";
            } else  if ($Nivel == "1") {
                $sqlFilho = "SELECT
                        PD.QuantidadeInicial,PD.Valor,HP.Quantidade,PD.Fazenda_Id as IdPai,
                        HP.Zona_Id as IdOrdenacaoFilho,ZN.Nome as Nome, TL.Nome as NomePai, ZN.Nome as NomeFilhos
                        FROM HistoricoDeProdutos HP
                         JOIN Produtos PD on PD.Id = HP.Produto_Id
                        JOIN Fazenda FZ on PD.Fazenda_Id = FZ.Id
                        JOIN Talhoes TL on TL.Id = HP.Talhao_Id
                        JOIN Zonas ZN on ZN.Id = HP.Zona_Id
                        where PD.Fazenda_Id='$IdNivelZero' and HP.Talhao_Id='$idTempPai'
                        ORDER BY HP.Zona_Id ASC";
            } else if ($Nivel == "2") {
                $sqlFilho = "SELECT
                    PD.QuantidadeInicial,PD.Valor,HP.Quantidade,HP.Zona_Id as IdPai,
                    PD.Categoria as IdOrdenacaoFilho,CP.Nome as Nome, TL.Nome as NomePai, CP.Nome as NomeFilhos
                    FROM HistoricoDeProdutos HP
                    JOIN Produtos PD on PD.Id = HP.Produto_Id
                    JOIN Fazenda FZ on PD.Fazenda_Id = FZ.Id
                    JOIN Talhoes TL on TL.Id = HP.Talhao_Id
                    JOIN Zonas ZN on ZN.Id = HP.Zona_Id
                    JOIN Categorias_Produtos CP on CP.Id = PD.Categoria
                    where PD.Fazenda_Id='$IdNivelZero' and HP.Talhao_Id='$IdNivelUm' and HP.Zona_Id='$idTempPai'
                    ORDER BY PD.Categoria ASC";
            } else if ($Nivel == "3") {
                $sqlFilho = "SELECT
                        PD.QuantidadeInicial,PD.Valor,HP.Quantidade,PD.Categoria as IdPai,
                        HP.Veiculo_Id as IdOrdenacaoFilho,CP.Nome as Nome, HP.Zona_Id as NomePai, VL.Nome as NomeFilhos
                            FROM HistoricoDeProdutos HP
                        JOIN Produtos PD on PD.Id = HP.Produto_Id
                        JOIN Fazenda FZ on PD.Fazenda_Id = FZ.Id
                        JOIN Talhoes TL on TL.Id = HP.Talhao_Id
                        JOIN Zonas ZN on ZN.Id = HP.Zona_Id
                        JOIN Categorias_Produtos CP on CP.Id = PD.Categoria
                        JOIN Veiculos VL on VL.Id = HP.Veiculo_Id
                        where PD.Fazenda_Id='$IdNivelZero' and HP.Talhao_Id='$IdNivelUm' and HP.Zona_Id='$IdNivelDois'
                        and PD.Categoria='$idTempPai'
                        ORDER BY HP.Veiculo_Id ASC";
            } else if ($Nivel == "4") {
                $sqlFilho = "SELECT
                PD.QuantidadeInicial,PD.Valor,HP.Quantidade,PD.Categoria as IdPai,
                HP.Veiculo_Id as IdOrdenacaoFilho,CP.Nome as Nome, HP.Zona_Id as NomePai, VL.Nome as NomeFilhos
                    FROM HistoricoDeProdutos HP
                JOIN Produtos PD on PD.Id = HP.Produto_Id
                JOIN Fazenda FZ on PD.Fazenda_Id = FZ.Id
                JOIN Talhoes TL on TL.Id = HP.Talhao_Id
                JOIN Zonas ZN on ZN.Id = HP.Zona_Id
                JOIN Categorias_Produtos CP on CP.Id = PD.Categoria
                JOIN Veiculos VL on VL.Id = HP.Veiculo_Id
                where PD.Fazenda_Id='$IdNivelZero' and HP.Talhao_Id='$IdNivelUm' and HP.Zona_Id='$IdNivelDois'
                and PD.Categoria='$IdNivelTres' and HP.Veiculo_Id='$idTempPai'
                ORDER BY HP.Veiculo_Id ASC";
            }
        }

        $resultFilho = $link->query($sqlFilho);
        if ($resultFilho->num_rows > 0) {
            $idTempFilhoDados = "";
            $DadosFilho = array();
            while ($rowFilho = $resultFilho->fetch_assoc()) {
                if ($idTempFilhoDados == "") {
                    $idTempFilhoDados = $rowFilho["IdOrdenacaoFilho"];
                }
                if ($idTempFilhoDados != $rowFilho["IdOrdenacaoFilho"]) {
                    $DadosFilhoResposta = array(
                        "Nome" => $NomeFilho,
                        "Valor" => $valorSaidaIndividual
                    );
                    array_push($DadosFilho, $DadosFilhoResposta);
                    $valorSaidaIndividual = 0.0;
                    $idTempFilhoDados = $rowFilho["IdOrdenacaoFilho"];
                }
                $NomeFilho = $rowFilho["NomeFilhos"];

                $valorSaidaIndividual = (($rowFilho["Valor"] / $rowFilho["QuantidadeInicial"]) * $rowFilho["Quantidade"]) + $valorSaidaIndividual;
                $valorSaidaTotal = (($rowFilho["Valor"] / $rowFilho["QuantidadeInicial"]) * $rowFilho["Quantidade"]) + $valorSaidaTotal;
            }
            //=======FILHO=DADOS===========
            $DadosFilhoResposta = array(
                "Nome" => $NomeFilho,
                "Valor" => $valorSaidaIndividual
            );
            array_push($DadosFilho, $DadosFilhoResposta);
            $valorSaidaIndividual = 0.0;
            //=======FILHO============
            $Resposta = array(
                "IdPai" => $idTempPai,
                "NomePai" => $NomePai,
                "Dados" => $DadosFilho
            );
            array_push($Respostas["Filho"], $Resposta);
            $DadosFilho = array();
            $idTempFilho = $rowFilho["IdOrdenacaoFilho"];
            $valorSaidaTotal = 0.0;
            $valorSaidaIndividual = 0.0;
            $idTempFilhoDados = "";
        }
        $Resposta = array(
            "Nome" => $NomePai,
            "Valor" => $ValorIndividual,
        );
        array_push($Respostas["Pai"], $Resposta);
    }

    $retorno = array("Status" => "Ok", "Resposta" => $Respostas);
    echo json_encode($retorno);
}
function RetornaGraficos($link, $Usuario, $Senha)
{
    $sql = "select Id from Usuarios where Id='$Usuario' and Senha='$Senha' and Permissao in ('0','999')";
    $result = $link->query($sql);
    if ($result->num_rows > 0) {
        $sql = "UPDATE Talhoes SET Corte=Corte+1 WHERE Status='0'";
        if ($link->query($sql) === TRUE) {
            $data = date("Y-m-d");
            $sql = "INSERT INTO HistoricoCiclo (Usuario, Data)VALUES ('$Usuario', '$data')";
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
function RetornatalhoesTalhoes($Usuario, $Id, $link)
{
    $sql = "select * from Talhoes where Status<>'3'";
    $result = $link->query($sql);
    $respostas = array();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $resposta = array(
                "Id" => $row['Id'],
                "Nome" => $row['Nome'],
                "Corte" => $row['Corte'],
                "Hectare" => $row['Hectare'],
                "Safra" => $row['Safra'],
                "Localizaocao" => json_decode(str_replace('\"', '', $row['Localizaocao'])),
                "Fazenda" => $row['Fazenda']
            );
            array_push($respostas, $resposta);
        }
        resgistraLog($link, "talhões", "RetornatalhoesTalhoes", "Ok",  "Retorna talhões", $Usuario);
        $retorno = array("Status" => "Ok", "Resposta" => $respostas);
    } else {
        resgistraLog($link, "talhões", "RetornatalhoesTalhoes", "Erro",  "Nenhuma talhões", $Usuario);
        $retorno = array("Status" => "Erro", "Resposta" => "Nenhuma categoria");
    }
    echo json_encode($retorno);
}
function CadastraAtualizaDeletaTalhao($Usuario, $Acao, $Localizaocao, $Fazenda, $Safra, $Hectare, $Id, $Nome, $Status, $link)
{
    if ($Acao == "1") {
        $sql = "INSERT INTO Talhoes
        (Nome,Corte,Hectare,Localizaocao,Safra,Fazenda,Status)
        VALUES
        ('$Nome','1','$Hectare','$Localizaocao','$Safra','$Fazenda','$Status')";
        $StatusAcao = "Cadastrado";
    } else if ($Acao == "2") {
        $sql = "UPDATE Talhoes SET
        Nome='$Nome',Hectare='$Hectare',Localizaocao='$Localizaocao',Safra='$Safra',Fazenda='$Fazenda',Status='$Status'
        WHERE Id='$Id'";
        $StatusAcao = "Atualizados";
    } else {
        $sql = "UPDATE Talhoes SET Status='3' WHERE Id='$Id'";
        $StatusAcao = "Deletado";
    }
    if ($link->query($sql) === TRUE) {
        resgistraLog($link, "talhoes", "CadastraAtualizaDeleta", "Ok",  "Talhão : " . $StatusAcao, $Usuario);
        $retorno = array("Status" => "Ok", "Resposta" => "Talhão : " . $StatusAcao);
    } else {
        resgistraLog($link, "talhoes", "CadastraAtualizaDeleta", "Erro",  "Nenhuma categoria", $Usuario);
        $retorno = array("Status" => "Erro", "Resposta" => "Nenhuma categoria");
    }
    echo json_encode($retorno);
}
