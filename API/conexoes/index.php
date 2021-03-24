<?php
if (!isset($_SESSION)) {
    session_start();
}
header('Access-Control-Allow-Origin: *');
date_default_timezone_set('America/Sao_Paulo');
header('Content-Type: text/html; charset=utf-8');
$link = mysqli_connect("p:162.214.109.135", "seedgest_user", ",IM*tW%F_I1d", "seedgest_banco", "3306");
$link->query("SET NAMES utf8");
function connect(){
    $link = mysqli_connect("p:162.214.109.135", "seedgest_user", ",IM*tW%F_I1d", "seedgest_banco", "3306");
$link->query("SET NAMES utf8");
return $link;
}
$chaveApi = md5("&&)#$#A6&&)#$#technology&&)Seed#$#");
//================A6===============

$urlApiA6 = "https://a6technology.com.br/";
$chaveApiA6 = md5("&&)#$#A6%(&*$#technology*(*$");

//========CONFIGURAÇÔES EMAIL==================
$Host = "mail.seedgestaorural.com";
$Username = "contato@seedgestaorural.com";
$Password = "Seeds@#$%2021";

$Logo = "https://seedgestaorural.com/admin/images/logoFundo.jpeg";
$Port = "587";
$tamplate = "";
