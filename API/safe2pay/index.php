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
            $RetonaToken = json_decode($RetonaToken);

            if ($StatusToken->Status != "Erro") {
                if ($funcao == "GeraBoleto") {
                    $Id = $_POST["Id"];
                    $data = $_POST["data"];
                    GeraBoleto($Usuario, $Nome, $Id, $link,$data);
                } else if ($funcao == "RetornaResponsavel") {
                    $Id = $_POST["Id"];
                    RetornaResponsavel($Usuario, $Id, $link);
                } else {
                    $retorno = array("Status" => "erro", "Resposta" => "Nenhuma função encontrada");
                    echo json_encode($retorno);
                }
            } else {
                $retorno = array("Status" => "erro", "Resposta" => "Token Inválido");
                echo json_encode($retorno);
            }
        }
    }
} else {
    $retorno = array("Status" => "erro", "Resposta" => "Chave de acesso");
    echo json_encode($retorno);
}
function GeraBoleto($Usuario, $Nome, $Id, $link,$data)
{

    $sql = "select * from Reponsavel where Id='$Id'";

    $result = $link->query($sql);

    $row = $result->fetch_assoc();

    $idLincenca = $row["Lincenca"];

    $sqlLicenca = "select Valor,Nome from Licencas where Id='$idLincenca'";

    $resultlLicenca = $link->query($sqlLicenca);

    $rowlLicenca = $resultlLicenca->fetch_assoc();


    $preco = str_replace(",", ".", $rowlLicenca["Valor"]);

    $NomeLicenca = $rowlLicenca["Nome"];

    $token = "A580B0876AD149CDB55EDA0AF7B47A82";

    $vendedor = "Maninho Viagens";

    $uid = uniqid();

    $nome = $row["Nome"];

    $cnpjcpf = str_replace("-", "", $row["CPF"]);
    $cnpjcpf = str_replace(".", "", $cnpjcpf);

    $telefone = $row["Celular"];

    $telefone = str_replace("(", "", $row["Celular"]);
    $telefone = str_replace(")", "", $telefone);
    $telefone = str_replace(" ", "", $telefone);

    $email = $row["Email"];

    $cep = str_replace("-", "", $row["CEP"]);

    $logradouro = $row["Endereco"];

    $numero = $row["Numero"];

    $complemento = $row["Complemento"];

    $bairro = $row["Bairro"];

    $cidade = $row["Cidade"];

    $uf = $row["UF"];

    $pais = "Brasil";

    $descricao = "Passagem";

    $quantidade = "1";

    $data = $data;

    $isntrucao = "Pagar Antes do Vencimento";

    $mensagem = "Pagar Antes do Vencimento";

    $multa = "0";

    $juros = "0";

    if ($multa == null) {

        $multa = 0;
    }

    if ($juros == null) {

        $juros = 0;
    }



    $obj = array(

        "IsSandbox" => false,

        "Application" => "Seeds",

        "Vendor" => "Seeds",

        "CallbackUrl" => "https://seedgestaorural.com/API/safe2pay/RetornoSafeBoleto.php",

        "PaymentMethod" => "1",

        "Reference" => "",

        "Customer" => array(

            "Name" => "$nome",

            "Identity" => "$cnpjcpf",

            "Phone" => "$telefone",

            "Email" => "$email",

            "Address" => array(

                "ZipCode" => "$cep",

                "Street" => "$logradouro",

                "Number" => "$numero",

                "Complement" => "$complemento",

                "District" => "$bairro",

                "CityName" => "$cidade",

                "StateInitials" => "$uf",

                "CountryName" => "$pais"

            )

        ),

        "Products" => [

            array(

                "Code" => "001",

                "Description" => "$NomeLicenca",

                "UnitPrice" => $preco,

                "Quantity" => $quantidade

            )

        ],

        "PaymentObject" => array(

            "DueDate" => "$data",

            "Instruction" => "$isntrucao",

            "Message" => [

                "$mensagem"

            ],

            "PenaltyRate" => $multa,

            "InterestRate" => $juros,

            "CancelAfterDue" => false,

            "IsEnablePartialPayment" => false

        )

    );

    // echo var_dump($obj);

    //     return;

    $curl = curl_init();

    curl_setopt_array($curl, array(

        CURLOPT_URL => "https://payment.safe2pay.com.br/v2/Payment",

        CURLOPT_RETURNTRANSFER => true,

        CURLOPT_ENCODING => "",

        CURLOPT_MAXREDIRS => 10,

        CURLOPT_TIMEOUT => 30,

        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,

        CURLOPT_CUSTOMREQUEST => "POST",

        CURLOPT_POSTFIELDS => json_encode(unserialize(serialize($obj))),

        CURLOPT_HTTPHEADER => array(

            "content-type: application/json",

            "x-api-key:" . $token

        ),

    ));

    $response = curl_exec($curl);

    $err = curl_error($curl);

    curl_close($curl);

    $tete = json_decode($response);

    if ($tete->HasError) {

        // echo "Erro: " . $tete->Error;

        $retorno = array("Status" => "Erro", "Resposta" => $tete->Error);

        return $retorno;
    } else {

        $tete = json_decode($response);

        $url = $tete->ResponseDetail->BankSlipUrl;

        $IdTransaction = $tete->ResponseDetail->IdTransaction;
        $data = date('d/m/Y');
        $sql = "INSERT INTO HistoricoPagamentos (Resposavel_Id,IdTransacional,UrlBoleto,DataEmissao,Status,Licenca_Id)
         VALUES ('$Id','$IdTransaction','$url','$data','0','$idLincenca')";
        $link->query($sql);
        $retorno = array("Status" => "Ok", "Resposta" => 'Boleto Gerado', 'url_boleto' => $url, 'IdTransaction' => $IdTransaction);

        echo $retorno;
    }
}
