<?php
include '../includes/conexoes/index.php';
include './index.php';

$token = $User_Token;
$funcao = $_POST["funcao"];
if ($funcao == "RetornaLicenca") {
    $url = $urlApi . "licencas/index.php";
    $Id = $_POST["Id"];
    $User_Id = $User_Id;
    $User_Token = $User_Token;
    $obj = array(
        "Usuario" => $User_Id,
        "Token" => $User_Token,
        "funcao" => $funcao,
        "verificacao" => $chaveApi,
        "Dispositivo" => "0",
        "Id" => $Id
    );
    ConnectApi($url, $token, $obj);
} else if ($funcao == "DesconectUser") {
    $url = $urlApi . "usuarios/index.php";
    $obj = array(
        "Usuario" => $User_Id,
        "funcao" => $funcao,
        "Token" => $User_Token,
        "verificacao" => $chaveApi,
        "Dispositivo" => "0"
    );
    ConnectApi($url, $token, $obj);
    DesconectUser();
} else if ($funcao == "CadastraAtualizaDeletaZona") {
    $url = $urlApi . "zona/index.php";
    $Id = $_POST["Id"];
    $Nome = $_POST["Nome"];
    $Fazenda = $_POST["Fazenda"];
    $Cor = $_POST["Cor"];
    $Acao = $_POST["Acao"];
    $User_Id = $User_Id;
    $User_Token = $User_Token;
    $obj = array(
        "Usuario" => $User_Id,
        "funcao" => $funcao,
        "Token" => $User_Token,
        "verificacao" => $chaveApi,
        "Cor" => $Cor,
        "Nome" => $Nome,
        "Fazenda" => $Fazenda,
        "Acao" => $Acao,
        "Id" => $Id,
        "Dispositivo" => "0"
    );
    ConnectApi($url, $token, $obj);
} else if ($funcao == "CadastraAtualizaDeletaTalhao") {
    $url = $urlApi . "talhoes/index.php";
    $Id = $_POST["Id"];

    $Zona = $_POST["Zona"];
    $Localizaocao = $_POST["Localizaocao"];
    $Acao = $_POST["Acao"];
    $NewVariedade = $_POST["NewVariedade"];
    $Hectare = $_POST["Hectare"];
    $Variedade = $_POST["Variedade"];
    $User_Id = $User_Id;
    $User_Token = $User_Token;
    $obj = array(
        "Usuario" => $User_Id,
        "funcao" => $funcao,
        "Token" => $User_Token,
        "verificacao" => $chaveApi,
        "Localizaocao" => $Localizaocao,
        "Hectare" => $Hectare,
        "Variedade" => $Variedade,
        "NewVariedade" => $NewVariedade,
        "Zona" => $Zona,
        "Acao" => $Acao,
        "Id" => $Id,
        "Dispositivo" => "0"
    );
    ConnectApi($url, $token, $obj);
} else if ($funcao == "CadastraProducao") {
    $url = $urlApi . "producao/index.php";
    $User_Id = $User_Id;
    $User_Token = $User_Token;
    $Talhao = $_POST["Talhao"];
    $Unidade = $_POST["Unidade"];
    $Quantidade = $_POST["Quantidade"];
    $obj = array(
        "Usuario" => $User_Id,
        "funcao" => $funcao,
        "Token" => $User_Token,
        "Unidade" => $Unidade,
        "Quantidade" => $Quantidade,
        "Talhao" => $Talhao,
        "verificacao" => $chaveApi,
        "Dispositivo" => "0"
    );
    ConnectApi($url, $token, $obj);
} else if ($funcao == "RetornaProducoes") {
    $url = $urlApi . "producao/index.php";
    $User_Id = $User_Id;
    $User_Token = $User_Token;
    $obj = array(
        "Usuario" => $User_Id,
        "funcao" => $funcao,
        "Token" => $User_Token,
        "verificacao" => $chaveApi,
        "Dispositivo" => "0"
    );
    ConnectApi($url, $token, $obj);
} else if ($funcao == "CadastraAtualizaDeletaFazendas") {
    $url = $urlApi . "fazendas/index.php";
    $Id = $_POST["Id"];
    $IdResponsavel = $_POST["IdResponsavel"];
    $Nome = $_POST["Nome"];
    $Localizacao = $_POST["Localizacao"];
    $Acao = $_POST["Acao"];
    $AnoTwo = $_POST["AnoTwo"];
    $AnoOne = $_POST["AnoOne"];
    $CorteAtual = $_POST["CorteAtual"];
    $User_Id = $User_Id;
    $User_Token = $User_Token;
    $obj = array(
        "Usuario" => $User_Id,
        "Localizacao" => $Localizacao,
        "funcao" => $funcao,
        "Token" => $User_Token,
        "verificacao" => $chaveApi,
        "Nome" => $Nome,
        "AnoTwo" => $AnoTwo,
        "AnoOne" => $AnoOne,
        "CorteAtual" => $CorteAtual,
        "IdResponsavel" => $IdResponsavel,
        "Acao" => $Acao,
        "Id" => $Id,
        "Dispositivo" => "0"
    );
    ConnectApi($url, $token, $obj);
} else if ($funcao == "RetornaCiclo") {
    $url = $urlApi . "talhoes/index.php";
    $User_Id = $User_Id;
    $User_Token = $User_Token;
    $obj = array(
        "Usuario" => $User_Id,
        "funcao" => $funcao,
        "Token" => $User_Token,
        "verificacao" => $chaveApi,
        "Dispositivo" => "0"
    );
    ConnectApi($url, $token, $obj);
} else if ($funcao == "RetornaPlantacao") {
    $url = $urlApi . "talhoes/index.php";
    $Id = $_POST["Id"];
    $User_Id = $User_Id;
    $User_Token = $User_Token;
    $obj = array(
        "Usuario" => $User_Id,
        "funcao" => $funcao,
        "Token" => $User_Token,
        "verificacao" => $chaveApi,
        "Id" => $Id,
        "Dispositivo" => "0"
    );
    ConnectApi($url, $token, $obj);
} else if ($funcao == "ResetaCiclo") {

    $url = $urlApi . "talhoes/index.php";
    $Senha = $_POST["Senha"];
    $Fazenda = $_POST["Fazenda"];
    $User_Id = $User_Id;
    $User_Token = $User_Token;
    $obj = array(
        "Usuario" => $User_Id,
        "funcao" => $funcao,
        "Token" => $User_Token,
        "verificacao" => $chaveApi,
        "Fazenda" => $Fazenda,
        "Senha" => $Senha,
        "Dispositivo" => "0"
    );
    ConnectApi($url, $token, $obj);
} else if ($funcao == "RetornaProdutosEntradaSaida") {
    $url = $urlApi . "relatorios/index.php";
    $Id = $_POST["Id"];
    $User_Id = $User_Id;
    $User_Token = $User_Token;
    $obj = array(
        "Usuario" => $User_Id,
        "funcao" => $funcao,
        "Token" => $User_Token,
        "verificacao" => $chaveApi,
        "Id" => $Id,
        "Dispositivo" => "0"
    );
    ConnectApi($url, $token, $obj);
} else if ($funcao == "RetornatalhoesTalhoesDashboard") {
    $url = $urlApi . "talhoes/index.php";
    $Id = $_POST["Id"];
    $User_Id = $User_Id;
    $User_Token = $User_Token;
    $obj = array(
        "Usuario" => $User_Id,
        "funcao" => $funcao,
        "Token" => $User_Token,
        "verificacao" => $chaveApi,
        "Id" => $Id,
        "Dispositivo" => "0"
    );
    ConnectApi($url, $token, $obj);
} else if ($funcao == "RetornaZonas") {
    $url = $urlApi . "zona/index.php";
    $Id = $_POST["Id"];
    $User_Id = $User_Id;
    $User_Token = $User_Token;
    $obj = array(
        "Usuario" => $User_Id,
        "funcao" => $funcao,
        "Token" => $User_Token,
        "verificacao" => $chaveApi,
        "Id" => $Id,
        "Dispositivo" => "0"
    );
    ConnectApi($url, $token, $obj);
} else if ($funcao == "RetornatalhoesTalhoes") {
    $url = $urlApi . "talhoes/index.php";
    $Id = $_POST["Id"];
    $User_Id = $User_Id;
    $User_Token = $User_Token;
    $obj = array(
        "Usuario" => $User_Id,
        "funcao" => $funcao,
        "Token" => $User_Token,
        "verificacao" => $chaveApi,
        "Id" => $Id,
        "Dispositivo" => "0"
    );
    ConnectApi($url, $token, $obj);
} else if ($funcao == "RetornaFazendas") {
    $url = $urlApi . "fazendas/index.php";
    $Id = $_POST["Id"];
    $User_Id = $User_Id;
    $User_Token = $User_Token;
    $obj = array(
        "Usuario" => $User_Id,
        "funcao" => $funcao,
        "Token" => $User_Token,
        "verificacao" => $chaveApi,
        "Id" => $Id,
        "Dispositivo" => "0"
    );
    ConnectApi($url, $token, $obj);
} else if ($funcao == "CarregaUsuariosFazendas") {
    $url = $urlApi . "usuarios/index.php";
    $User_Id = $User_Id;
    $User_Token = $User_Token;
    $obj = array(
        "Usuario" => $User_Id,
        "funcao" => $funcao,
        "Token" => $User_Token,
        "verificacao" => $chaveApi,
        "Dispositivo" => "0"
    );
    ConnectApi($url, $token, $obj);
} else if ($funcao == "UsuarioFazenda") {
    $url = $urlApi . "usuarios/index.php";
    $Id = $_POST["Id"];
    $Acao = $_POST["Acao"];
    $UserId = $_POST["UserId"];
    $FazendaId = $_POST["FazendaId"];
    $User_Id = $User_Id;
    $User_Token = $User_Token;
    $obj = array(
        "Usuario" => $User_Id,
        "funcao" => $funcao,
        "Token" => $User_Token,
        "verificacao" => $chaveApi,
        "FazendaId" => $FazendaId,
        "Acao" => $Acao,
        "Id" => $Id,
        "UserId" => $UserId,
        "Dispositivo" => "0"
    );
    ConnectApi($url, $token, $obj);
} else if ($funcao == "CarregaUsuarios") {
    $url = $urlApi . "usuarios/index.php";
    $Id = $_POST["Id"];
    $User_Id = $User_Id;
    $User_Token = $User_Token;
    $obj = array(
        "Usuario" => $User_Id,
        "funcao" => $funcao,
        "Token" => $User_Token,
        "verificacao" => $chaveApi,
        "Id" => $Id,
        "Dispositivo" => "0"
    );
    ConnectApi($url, $token, $obj);
} else if ($funcao == "CadastraAtualizaDeletaUsuarios") {
    $url = $urlApi . "usuarios/index.php";
    $Id = $_POST["Id"];
    $Acao = $_POST["Acao"];
    $Fazenda = $_POST["Fazenda"];
    $Permissao = $_POST["Permissao"];
    $IdResponsavel = $_POST["IdResponsavel"];
    $Nome = $_POST["Nome"];
    $User_Id = $User_Id;
    $User_Token = $User_Token;
    $obj = array(
        "Usuario" => $User_Id,
        "funcao" => $funcao,
        "Token" => $User_Token,
        "verificacao" => $chaveApi,
        "Id" => $Id,
        "Acao" => $Acao,
        "Fazenda" => $Fazenda,
        "Permissao" => $Permissao,
        "IdResponsavel" => $IdResponsavel,
        "Nome" => $Nome,
        "Dispositivo" => "0"
    );
    ConnectApi($url, $token, $obj);
} else if ($funcao == "AtualizaSenha") {
    $url = $urlApi . "usuarios/index.php";
    $Senha = $_POST["Senha"];
    $SenhaValida = $_POST["SenhaValida"];
    $User_Id = $User_Id;
    $User_Token = $User_Token;
    $obj = array(
        "Usuario" => $User_Id,
        "funcao" => $funcao,
        "Token" => $User_Token,
        "verificacao" => $chaveApi,
        "Senha" => $Senha,
        "SenhaValida" => $SenhaValida,
        "Dispositivo" => "0"
    );
    ConnectApi($url, $token, $obj);
    ConnectUser($User_Token, $User_Id, '1', $Nome_Seeds, $Permissao_Seeds);
} else if ($funcao == "Logar") {
    $url = $urlApi . "usuarios/index.php";
    $UserName = $_POST["UserName"];
    $Senha = $_POST["Senha"];
    $User_Id = $User_Id;
    $User_Token = $User_Token;
    $obj = array(
        "Usuario" => $User_Id,
        "funcao" => $funcao,
        "Token" => $User_Token,
        "verificacao" => $chaveApi,
        "UserName" => $UserName,
        "Senha" => $Senha,
        "Dispositivo" => "0"
    );
    ConnectApi($url, $token, $obj);
} else if ($funcao == "RetornaResponsaveis") {
    $url = $urlApi . "licencas/index.php";
    $Id = $_POST["Id"];
    $User_Id = $User_Id;
    $User_Token = $User_Token;
    $obj = array(
        "Usuario" => $User_Id,
        "funcao" => $funcao,
        "Token" => $User_Token,
        "verificacao" => $chaveApi,
        "Dispositivo" => "0",
        "Id" => $Id
    );
    ConnectApi($url, $token, $obj);
} else if ($funcao == "RetornaResponsavel") {
    $url = $urlApi . "licencas/index.php";
    $Id = $_POST["Id"];
    $User_Id = $User_Id;
    $User_Token = $User_Token;
    $obj = array(
        "Usuario" => $User_Id,
        "funcao" => $funcao,
        "Token" => $User_Token,
        "verificacao" => $chaveApi,
        "Dispositivo" => "0",
        "Id" => $Id
    );
    ConnectApi($url, $token, $obj);
} else if ($funcao == "CadastraAtualizaDeletaLicenca") {
    $url = $urlApi . "licencas/index.php";
    $Id = $_POST["Id"];
    $Nome = $_POST["Nome"];
    $Fazendas = $_POST["Fazendas"];
    $Usuarios = $_POST["Usuarios"];
    $Acao = $_POST["Acao"];
    $User_Id = $User_Id;
    $User_Token = $User_Token;
    $obj = array(
        "Fazendas" => $Fazendas,
        "Usuarios" => $Usuarios,
        "Nome" => $Nome,
        "Acao" => $Acao,
        "Usuario" => $User_Id,
        "funcao" => $funcao,
        "Token" => $User_Token,
        "verificacao" => $chaveApi,
        "Dispositivo" => "0",
        "Id" => $Id
    );
    ConnectApi($url, $token, $obj);
} else if ($funcao == "CadastraAtualizaDeletaReponsavel") {
    $url = $urlApi . "licencas/index.php";
    $Status = $_POST["Status"];
    $Id = $_POST["Id"];
    $Username = $_POST["Username"];
    $Nome = $_POST["Nome"];
    $Cpf = $_POST["Cpf"];
    $Email = $_POST["Email"];
    $Celular = $_POST["Celular"];
    $Cep = $_POST["Cep"];
    $Endereco = $_POST["Endereco"];
    $Numero = $_POST["Numero"];
    $Complemento = $_POST["Complemento"];
    $Bairro = $_POST["Bairro"];
    $UF = $_POST["UF"];
    $Cidade = $_POST["Cidade"];
    $Licenca = $_POST["Licenca"];
    $Acao = $_POST["Acao"];
    $User_Id = $User_Id;
    $User_Token = $User_Token;
    $obj = array(
        "Status" => $Status,
        "Email" => $Email,
        "Celular" => $Celular,
        "Cep" => $Cep,
        "Endereco" => $Endereco,
        "Numero" => $Numero,
        "Complemento" => $Complemento,
        "Bairro" => $Bairro,
        "UF" => $UF,
        "Cidade" => $Cidade,
        "Licenca" => $Licenca,
        "UserName" => $Username,
        "Cpf" => $Cpf,
        "Nome" => $Nome,
        "Acao" => $Acao,
        "Usuario" => $User_Id,
        "funcao" => $funcao,
        "Token" => $User_Token,
        "verificacao" => $chaveApi,
        "Dispositivo" => "0",
        "Id" => $Id
    );
    ConnectApi($url, $token, $obj);
} else if ($funcao == "RetornaEstados") {
    $url = $urlApi . "endereco/index.php";
    $User_Id = $User_Id;
    $User_Token = $User_Token;
    $obj = array(
        "Usuario" => $User_Id,
        "Token" => $User_Token,
        "funcao" => $funcao,
        "verificacao" => $chaveApi,
        "Dispositivo" => "0",
    );
    ConnectApi($url, $token, $obj);
} else if ($funcao == "SaidaFluxoCaixa") {
    $url = $urlApi . "relatorios/index.php";
    $User_Id = $User_Id;
    $User_Token = $User_Token;
    $Nivel = $_POST["Nivel"];
    $IdNivelZero = $_POST["IdNivelZero"];
    $IdNivelUm = $_POST["IdNivelUm"];
    $IdNivelDois = $_POST["IdNivelDois"];
    $IdNivelTres = $_POST["IdNivelTres"];
    $IdNivelQuatro = $_POST["IdNivelQuatro"];
    $obj = array(
        "Usuario" => $User_Id,
        "Token" => $User_Token,
        "funcao" => $funcao,
        "Nivel" => $Nivel,
        "IdNivelQuatro" => $IdNivelQuatro,
        "IdNivelZero" => $IdNivelZero,
        "IdNivelDois" => $IdNivelDois,
        "IdNivelTres" => $IdNivelTres,
        "IdNivelUm" => $IdNivelUm,
        "verificacao" => $chaveApi,
        "Dispositivo" => "0",
    );
    ConnectApi($url, $token, $obj);
} else if ($funcao == "RetornaVeiculos") {
    $url = $urlApi . "talhoes/index.php";
    $User_Id = $User_Id;
    $User_Token = $User_Token;
    $obj = array(
        "Usuario" => $User_Id,
        "Token" => $User_Token,
        "funcao" => $funcao,
        "verificacao" => $chaveApi,
        "Dispositivo" => "0",
    );
    ConnectApi($url, $token, $obj);
} else if ($funcao == "RetornaEntrada") {
    $url = $urlApi . "relatorios/index.php";
    $User_Id = $User_Id;
    $User_Token = $User_Token;

    $obj = array(
        "Usuario" => $User_Id,
        "Token" => $User_Token,
        "funcao" => $funcao,
        "verificacao" => $chaveApi,
        "Dispositivo" => "0",
    );
    ConnectApi($url, $token, $obj);
} else if ($funcao == "RetornatalhoesZonas") {
    $url = $urlApi . "talhoes/index.php";
    $User_Id = $User_Id;
    $User_Token = $User_Token;
    $Talhao = $_POST["Talhao"];
    $obj = array(
        "Usuario" => $User_Id,
        "Token" => $User_Token,
        "funcao" => $funcao,
        "Talhao" => $Talhao,
        "verificacao" => $chaveApi,
        "Dispositivo" => "0",
    );
    ConnectApi($url, $token, $obj);
} else if ($funcao == "SaidaDeEstoque") {
    $url = $urlApi . "produtos/index.php";
    $User_Id = $User_Id;
    $User_Token = $User_Token;
    $Id = $_POST["Id"];
    $Quantidade = $_POST["Quantidade"];
    $Talhao = $_POST["Talhao"];
    $ZonaSelect = $_POST["ZonaSelect"];
    $NewZona = $_POST["NewZona"];
    $NewVeiculo = $_POST["NewVeiculo"];
    $VeiculoSelect = $_POST["VeiculoSelect"];
    $obj = array(
        "Usuario" => $User_Id,
        "Token" => $User_Token,
        "funcao" => $funcao,
        "Quantidade" => $Quantidade,
        "Id" => $Id,
        "Talhao" => $Talhao,
        "ZonaSelect" => $ZonaSelect,
        "NewVeiculo" => $NewVeiculo,
        "NewZona" => $NewZona,
        "VeiculoSelect" => $VeiculoSelect,
        "verificacao" => $chaveApi,
        "Dispositivo" => "0",
    );
    ConnectApi($url, $token, $obj);
} else if ($funcao == "CadastraAtualizaDeletaProdutos") {
    $url = $urlApi . "produtos/index.php";
    $User_Id = $User_Id;
    $User_Token = $User_Token;
    $Acao = $_POST["Acao"];
    $Id = $_POST["Id"];
    $Nome = $_POST["Nome"];
    // $NewCategoria = $_POST["NewCategoria"];
    $Quantidade = $_POST["Quantidade"];
    $Valor = $_POST["Valor"];
    $Fazenda = $_POST["Fazenda"];
    $CategoriaSelect = $_POST["CategoriaSelect"];
    $TipoUnidade = $_POST["TipoUnidade"];
    $obj = array(
        "Usuario" => $User_Id,
        "Token" => $User_Token,
        "funcao" => $funcao,
        "Acao" => $Acao,
        "Id" => $Id,
        "Nome" => $Nome,
        "Fazenda" => $Fazenda,
        // "NewCategoria" => $NewCategoria,
        "Quantidade" => $Quantidade,
        "Valor" => $Valor,
        "CategoriaSelect" => $CategoriaSelect,
        "TipoUnidade" => $TipoUnidade,
        "verificacao" => $chaveApi,
        "Dispositivo" => "0",
    );
    ConnectApi($url, $token, $obj);
} else if ($funcao == "RetornaProdutosSaida") {
    $url = $urlApi . "produtos/index.php";
    $User_Id = $User_Id;
    $User_Token = $User_Token;
    $Id = $_POST["Id"];
    $obj = array(
        "Usuario" => $User_Id,
        "Token" => $User_Token,
        "funcao" => $funcao,
        "Id" => $Id,
        "verificacao" => $chaveApi,
        "Dispositivo" => "0",
    );
    ConnectApi($url, $token, $obj);
} else if ($funcao == "RetornaProdutos") {
    $url = $urlApi . "produtos/index.php";
    $User_Id = $User_Id;
    $User_Token = $User_Token;
    $Id = $_POST["Id"];
    $obj = array(
        "Usuario" => $User_Id,
        "Token" => $User_Token,
        "funcao" => $funcao,
        "Id" => $Id,
        "verificacao" => $chaveApi,
        "Dispositivo" => "0",
    );
    ConnectApi($url, $token, $obj);
} else if ($funcao == "RetornaCategorias") {
    $url = $urlApi . "produtos/index.php";
    $User_Id = $User_Id;
    $User_Token = $User_Token;
    $obj = array(
        "Usuario" => $User_Id,
        "Token" => $User_Token,
        "funcao" => $funcao,
        "verificacao" => $chaveApi,
        "Dispositivo" => "0",
    );
    ConnectApi($url, $token, $obj);
} else if ($funcao == "RetornaMunicipios") {
    $url = $urlApi . "endereco/index.php";
    $User_Id = $User_Id;
    $User_Token = $User_Token;
    $Uf = $_POST["Uf"];
    $obj = array(
        "Uf" => $Uf,
        "Usuario" => $User_Id,
        "Token" => $User_Token,
        "funcao" => $funcao,
        "verificacao" => $chaveApi,
        "Dispositivo" => "0",
    );
    ConnectApi($url, $token, $obj);
} else if ($funcao == "CadastraAtualizaDeletaFrota") {
    $url = $urlApi . "frotas/index.php";
    $User_Id = $User_Id;
    $User_Token = $User_Token;

    $Id = $_POST["Id"];
    $Acao = $_POST["Acao"];
    $Nome = $_POST["Nome"];
    $Marca = $_POST["Marca"];
    $Modelo = $_POST["Modelo"];
    $Placa = $_POST["Placa"];
    $NumeroFrota = $_POST["NumeroFrota"];
    $KmInicial = $_POST["KmInicial"];
    $KmAtual = $_POST["KmAtual"];
    $Status = $_POST["Status"];
    $Responsavel = $_POST["Responsavel"];
    $Ano = $_POST["Ano"];
    $obj = array(
        "Id" => $Id,
        "Acao" => $Acao,
        "Nome" => $Nome,
        "Ano" => $Ano,
        "Marca" => $Marca,
        "Modelo" => $Modelo,
        "Placa" => $Placa,
        "NumeroFrota" => $NumeroFrota,
        "KmInicial" => $KmInicial,
        "KmAtual" => $KmAtual,
        "Status" => $Status,
        "Responsavel" => $Responsavel,
        "Usuario" => $User_Id,
        "Token" => $User_Token,
        "funcao" => $funcao,
        "verificacao" => $chaveApi,
        "Dispositivo" => "0",
    );
    ConnectApi($url, $token, $obj);
} else if ($funcao == "RetornaFrotas") {
    $url = $urlApi . "frotas/index.php";
    $User_Id = $User_Id;
    $User_Token = $User_Token;
    $Id = $_POST["Id"];
    $obj = array(
        "Id" => $Id,
        "Usuario" => $User_Id,
        "Token" => $User_Token,
        "funcao" => $funcao,
        "verificacao" => $chaveApi,
        "Dispositivo" => "0",
    );
    ConnectApi($url, $token, $obj);
} else if ($funcao == "PegaResponsavel") {
    $url = $urlApi . "frotas/index.php";
    $User_Id = $User_Id;
    $User_Token = $User_Token;
    $obj = array(
        "Usuario" => $User_Id,
        "Token" => $User_Token,
        "funcao" => $funcao,
        "verificacao" => $chaveApi,
        "Dispositivo" => "0",
    );
    ConnectApi($url, $token, $obj);
} else if ($funcao == "CadastraAtualizaDeletaNota") {
    $url = $urlApi . "produtos/index.php";
    $User_Id = $User_Id;
    $User_Token = $User_Token;
    $Id = $_POST["Id"];
    $Acao = $_POST["Acao"];
    $Nota = $_POST["Nota"];
    $DataCompra = $_POST["DataCompra"];
    $DataVencimento = $_POST["DataVencimento"];
    $Parcelas = $_POST["Parcelas"];
    $Fornecedor = $_POST["Fornecedor"];
    $CNPJFornecedor = $_POST["CNPJFornecedor"];
    $Fazenda = $_POST["Fazenda"];
    $SafraEntrada = $_POST["SafraEntrada"];
    $obj = array(
        "Usuario" => $User_Id,
        "Token" => $User_Token,
        "funcao" => $funcao,
        "Id" => $Id,
        "Acao" => $Acao,
        "Nota" => $Nota,
        "DataCompra" => $DataCompra,
        "DataVencimento" => $DataVencimento,
        "Parcelas" => $Parcelas,
        "Fornecedor" => $Fornecedor,
        "CNPJFornecedor" => $CNPJFornecedor,
        "Fazenda" => $Fazenda,
        "SafraEntrada" => $SafraEntrada,
        "verificacao" => $chaveApi,
        "Dispositivo" => "0",
    );
    ConnectApi($url, $token, $obj);
} else {
}
function ConnectApi($url, $token, $obj)
{
    $funcao=$obj["funcao"];

    $url = $url;
    $obj = http_build_query($obj);
    $curl  = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $obj);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $Retorno = curl_exec($curl);
    curl_close($curl);
    echo $Retorno;
    if ($funcao == "Logar") {
        $RetornoArray = json_decode($Retorno);
        //  echo var_dump($RetornoArray);
        if ($RetornoArray->Status == "Ok") {
            ConnectUser(
                $RetornoArray->Token,
                $RetornoArray->Resposta->Id,
                $RetornoArray->Resposta->PrimeiroAcesso,
                $RetornoArray->Resposta->Nome,
                $RetornoArray->Resposta->Permissao
            );
        }
    }
}
// Envio Padrão minimo
// $obj = array(
//     "Usuario" => $User_Id,
//      "funcao" => $funcao,
//     "Token" => $User_Token,
//     "verificacao" => $chaveApi,
//     "Dispositivo" => "0",
// );