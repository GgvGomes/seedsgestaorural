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
                if ($funcao == "CadastraAtualizaDeletaProdutos") {
                    $Acao = $_POST["Acao"];
                    $Id = $_POST["Id"];
                    $Nome = $_POST["Nome"];
                    // $NewCategoria = $_POST["NewCategoria"];
                    $Quantidade = $_POST["Quantidade"];
                    $QuantidadeMinima = $_POST["QuantidadeMinima"];
                    $Valor = $_POST["Valor"];
                    $CategoriaSelect = $_POST["CategoriaSelect"];
                    $TipoUnidade = $_POST["TipoUnidade"];
                    CadastraAtualizaDeletaProdutos($Usuario, $QuantidadeMin, $Quantidade, $Valor, $TipoUnidade, $CategoriaSelect, $Nome, $Id, $Acao, $link);
                } else if ($funcao == "SaidaDeEstoque") {
                    $Id = $_POST["Id"];
                    $Talhao = $_POST["Talhao"];
                    $Quantidade = $_POST["Quantidade"];
                    $Zona = $_POST["ZonaSelect"];
                    $Veiculo = $_POST["VeiculoSelect"];
                    $NewZona = $_POST["NewZona"];
                    $NewVeiculos = $_POST["NewVeiculos"];
                    SaidaDeEstoque($Usuario, $Quantidade, $Id, $Talhao, $Zona, $NewZona, $NewVeiculos, $Veiculo, $link);
                } else if ($funcao == "RetornaProdutosSaida") {
                    $Id = $_POST["Id"];
                    RetornaProdutosSaida($Usuario, $Id, $link);
                } else if ($funcao == "CadastraAtualizaDeletaNota") {
                    CadastraAtualizaDeletaNota($Usuario, $link);
                } else if ($funcao == "RetornaProdutos") {
                    $Id = $_POST["Id"];
                    RetornaProdutos($Usuario, $Id, $link);
                } else if ($funcao == "RetornaCategorias") {
                    RetornaCategorias($Usuario,  $link);
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
function CadastraAtualizaDeletaNota($Usuario, $link)
{
    $Id = $_REQUEST["Id"];
    $Acao = $_REQUEST["Acao"];
    $Nota = $_REQUEST["Nota"];
    $DataCompra = $_REQUEST["DataCompra"];
    $DataVencimento = $_REQUEST["DataVencimento"];
    $Parcelas = $_REQUEST["Parcelas"];
    $Fornecedor = $_REQUEST["Fornecedor"];
    $CNPJFornecedor = $_REQUEST["CNPJFornecedor"];
    $Fazenda = $_REQUEST["Fazenda"];
    $SafraEntrada = $_REQUEST["SafraEntrada"];
    if ($Acao == "1") {
        $DataCadastro = date("Y-m-d H:i:s");
        $sql = "INSERT INTO CabecalhoProduto
        (Nota,DataCompra,DataVencimento,Parcelas,Fornecedor,CNPJFornecedor,Fazenda,
        SafraEntrada,Status)
         VALUES
         ('$Nota','$DataCompra','$DataVencimento','$Parcelas','$Fornecedor',
         '$CNPJFornecedor','$Fazenda','$SafraEntrada','0')";
        $StatusAcao = "Cadastrado";

        $sql="SELECT Id from CabecalhoProduto where DataCompra='$DataCompra' and DataVencimento='$DataVencimento'
        and CNPJFornecedor='$CNPJFornecedor' and Nota='Nota'";
        $result = $link->query($sql);
        $row = $result->fetch_assoc();
        $Id = $row['Id'];
    } else if ($Acao == "2") {
        $sql = "UPDATE CabecalhoProduto SET
        Nota='$Nota',DataCompra='$DataCompra',DataVencimento='$DataVencimento',
        Parcelas='$Parcelas',Fornecedor='$Fornecedor',CNPJFornecedor='$CNPJFornecedor',
        Fazenda='$Fazenda',SafraEntrada='$SafraEntrada' 
        WHERE Id='$Id'";
        $StatusAcao = "Atualizados";
    } else {
        $sql = "UPDATE CabecalhoProduto SET Status='3' WHERE Id='$Id'";
        $StatusAcao = "Deletado";
    }
    if ($link->query($sql) === TRUE) {
        resgistraLog($link, "produtos", "CadastraAtualizaDeletaNota", "Ok",  "Produto " . $StatusAcao, $Usuario);
        $retorno = array("Status" => "Ok", "Resposta" =>  "Produto " . $StatusAcao, "Id" => $Id);
    } else {
        resgistraLog($link, "produtos", "CadastraAtualizaDeletaNota", "Erro", "Erro ao cadastrar : " . $sql, $Usuario);
        $retorno = array("Status" => "Erro", "Resposta" => "Erro ao cadastrar");
    }

    echo json_encode($retorno);
}
function CadastraAtualizaDeletaProdutos($Usuario, $Quantidade, $Valor, $TipoUnidade, $CategoriaSelect, $Nome, $Id, $Acao, $link)
{
    // if ($NewCategoria != null && $NewCategoria != "") {
    //     $sql = "INSERT INTO Categorias_Produtos (Nome) VALUES ('$NewCategoria')";
    //     $link->query($sql);
    //     $CategoriaSelect = $link->insert_id;
    // }
    $Valor = str_replace(".", "", $Valor);
    $Valor = str_replace(",", ".", $Valor);
    if ($Acao == "1") {
        $DataCadastro = date("Y-m-d H:i:s");
        $sql = "INSERT INTO Produtos
        (Nome,TipoUnidade,DataCadatro,Valor,Categoria,Quantidade,QuantidadeInicial,Status)
         VALUES
         ('$Nome','$TipoUnidade','$DataCadastro','$Valor','$CategoriaSelect','$Quantidade','$Quantidade','0')";
        $StatusAcao = "Cadastrado";
    } else if ($Acao == "2") {
        $sql = "UPDATE Produtos SET
        Nome='$Nome',Categoria='$CategoriaSelect',Valor='$Valor',TipoUnidade='$TipoUnidade',Quantidade='$Quantidade'
        WHERE Id='$Id'";
        $StatusAcao = "Atualizados";
    } else {
        $sql = "UPDATE Produtos SET Status='3' WHERE Id='$Id'";
        $StatusAcao = "Deletado";
    }
    if ($link->query($sql) === TRUE) {
        resgistraLog($link, "produtos", "CadastraAtualizaDeletaLicenca", "Ok",  "Produto " . $StatusAcao, $Usuario);
        $retorno = array("Status" => "Ok", "Resposta" =>  "Produto " . $StatusAcao);
    } else {
        resgistraLog($link, "produtos", "CadastraAtualizaDeletaLicenca", "Erro", "Erro ao cadastrar : " . $sql, $Usuario);
        $retorno = array("Status" => "Erro", "Resposta" => "Erro ao cadastrar");
    }

    echo json_encode($retorno);
}
function SaidaDeEstoque($Usuario, $Quantidade, $Id, $Talhao, $Zona, $NewZona, $NewVeiculos, $Veiculo, $link)
{
    if ($NewZona != null && $NewZona != "") {
        $sql = "INSERT INTO Zonas (Nome,Talhao_Id) VALUES ('$NewZona','$Talhao')";
        $link->query($sql);
        $Zona = $link->insert_id;
    }
    if ($NewVeiculos != null && $NewVeiculos != "") {
        $sql = "select Responsavel_Id from Usuarios where Id='$Usuario'";
        $result = $link->query($sql);
        $row = $result->fetch_assoc();
        $reponsavel = $row["Responsavel_Id"];
        $sql = "INSERT INTO Veiculos (Nome,Responsavel_Id) VALUES ('$NewVeiculos','$reponsavel')";
        $link->query($sql);
        $Veiculo = $link->insert_id;
    }

    $sql = "select Quantidade from Produtos where Id='$Id'";
    $result = $link->query($sql);
    $row = $result->fetch_assoc();
    $quantidadeAtual = $row["Quantidade"];
    if ($Quantidade <= $quantidadeAtual) {
        $Quantidade = str_replace(",", ".", $Quantidade);
        $quantidadeAtual = $quantidadeAtual - $Quantidade;
        if ($quantidadeAtual == 0) {
            $sql = "UPDATE Produtos SET Quantidade='$quantidadeAtual',Status='3' WHERE Id='$Id'";
        } else {
            $sql = "UPDATE Produtos SET Quantidade='$quantidadeAtual' WHERE Id='$Id'";
        }
        $StatusAcao = "Atualizados";
        $DataCadastro = date("Y-m-d H:i:s");
        $sqlTalhao = "select Corte from Talhoes where Id='$Talhao'";
        $resultTalhao = $link->query($sqlTalhao);
        $rowTalhao = $resultTalhao->fetch_assoc();
        $Corte = $rowTalhao["Corte"];
        $sqlInsert = "INSERT INTO HistoricoDeProdutos (Corte,Produto_Id, DataCadatro, Talhao_Id, Quantidade, Usuario, Zona_Id, Veiculo_Id)
         VALUES ('$Corte','$Id','$DataCadastro','$Talhao','$Quantidade','$Usuario','$Zona', '$Veiculo')";
        if ($link->query($sql) === TRUE && $link->query($sqlInsert) === TRUE) {
            resgistraLog($link, "produtos", "SaidaDeEstoque", "Ok",  "Produto " . $StatusAcao, $Usuario);
            $retorno = array("Status" => "Ok", "Resposta" =>  "Produto " . $StatusAcao);
        } else {
            resgistraLog($link, "produtos", "SaidaDeEstoque", "Erro", "Erro ao cadastrar : " . $sql, $Usuario);
            $retorno = array("Status" => "Erro", "Resposta" => "Erro ao cadastrar");
        }
    } else {
        resgistraLog($link, "produtos", "SaidaDeEstoque", "Erro", "Saída maior que estoque", $Usuario);
        $retorno = array("Status" => "Erro", "Resposta" => "Saída maior que estoque");
    }

    echo json_encode($retorno);
}
function RetornaCategorias($Usuario,  $link)
{
    $sql = "SELECT * from Categorias_Produtos";
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
        resgistraLog($link, "produtos", "RetornaCategorias", "Ok",  "Retorna licenca", $Usuario);
        $retorno = array("Status" => "Ok", "Resposta" => $respostas);
    } else {
        resgistraLog($link, "produtos", "RetornaCategorias", "Erro",  "Nenhuma categoria", $Usuario);
        $retorno = array("Status" => "Erro", "Resposta" => "Nenhuma categoria");
    }
    echo json_encode($retorno);
}
function RetornaProdutosSaida($Usuario, $Id, $link)
{

    if ($Id == null || $Id == "") {
        $sql = "select
     PD.Id as Id, PD.Nome as Nome, CP.Nome as Categoria,PD.Categoria as IdCategoria,PD.Quantidade as Quantidade,PD.TipoUnidade as TipoUnidade, PD.Valor as Valor
     from Produtos PD JOIN Categorias_Produtos CP on PD.Categoria = CP.ID where PD.Status<>'3' and PD.Quantidade>0";
    } else {
        $sql = "select
     PD.Id as Id, PD.Nome as Nome, CP.Nome as Categoria,PD.Categoria as IdCategoria,PD.Quantidade as Quantidade,PD.TipoUnidade as TipoUnidade, PD.Valor as Valor
     from Produtos PD JOIN Categorias_Produtos CP on PD.Categoria = CP.ID where PD.Id='$Id' and PD.Status<>'3' and PD.Quantidade>0";
    }
    $result = $link->query($sql);
    $respostas = array();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $resposta = array(
                "Id" => $row['Id'],
                "Nome" => $row['Nome'],
                "TipoUnidade" => $row['TipoUnidade'],
                "IdCategoria" => $row['IdCategoria'],
                "Categoria" => $row['Categoria'],
                "Valor" => $row['Valor'],
                "Quantidade" => str_replace(".", ",", $row['Quantidade'])
            );
            array_push($respostas, $resposta);
        }
        resgistraLog($link, "produtos", "RetornaProdutos", "Ok",  "Retorna produtos", $Usuario);
        $retorno = array("Status" => "Ok", "Resposta" => $respostas);
    } else {
        resgistraLog($link, "produtos", "RetornaProdutos", "Erro",  "Nenhuma produto", $Usuario);
        $retorno = array("Status" => "Erro", "Resposta" => "Nenhuma produto");
    }
    echo json_encode($retorno);
}
function RetornaProdutos($Usuario, $Id, $link)
{
    if ($Id == null || $Id == "") {
        $sql = "select Responsavel_Id from Usuarios where Id='$Usuario'";
        $result = $link->query($sql);
        $row = $result->fetch_assoc();
        $reponsavel = $row["Responsavel_Id"];
        $sql = "select
     PD.Id as Id, PD.Nome as Nome, CP.Nome as Categoria,PD.Categoria as IdCategoria,PD.Quantidade as Quantidade,PD.TipoUnidade as TipoUnidade, PD.Valor as Valor
     from Produtos PD JOIN Categorias_Produtos CP on PD.Categoria = CP.ID where PD.Status<>'3' and PD.Responsavel_Id='$reponsavel'";
    } else {
        $sql = "select Responsavel_Id from Usuarios where Id='$Usuario'";
        $result = $link->query($sql);
        $row = $result->fetch_assoc();
        $reponsavel = $row["Responsavel_Id"];
        $sql = "select
     PD.Id as Id, PD.Nome as Nome, CP.Nome as Categoria,PD.Categoria as IdCategoria,PD.Quantidade as Quantidade,PD.TipoUnidade as TipoUnidade, PD.Valor as Valor
     from Produtos PD JOIN Categorias_Produtos CP on PD.Categoria = CP.ID where PD.Id='$Id' and PD.Status<>'3' and PD.Responsavel_Id='$reponsavel'";
    }
    $result = $link->query($sql);
    $respostas = array();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $resposta = array(
                "Id" => $row['Id'],
                "Nome" => $row['Nome'],
                "TipoUnidade" => $row['TipoUnidade'],
                "IdCategoria" => $row['IdCategoria'],
                "Categoria" => $row['Categoria'],
                "Valor" => $row['Valor'],
                "Quantidade" => $row['Quantidade']
            );
            array_push($respostas, $resposta);
        }
        resgistraLog($link, "produtos", "RetornaProdutos", "Ok",  "Retorna produtos", $Usuario);
        $retorno = array("Status" => "Ok", "Resposta" => $respostas);
    } else {
        resgistraLog($link, "produtos", "RetornaProdutos", "Erro",  "Nenhuma produto", $Usuario);
        $retorno = array("Status" => "Erro", "Resposta" => "Nenhuma produto");
    }
    echo json_encode($retorno);
}
