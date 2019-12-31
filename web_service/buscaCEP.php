<?php

require_once('ViaCep/BuscaViaCEP_inc.php');

use Jarouche\ViaCEP\HelperViaCep;

if (isset($_GET['cep']) && $_GET['cep']) {
    $class_cep = HelperViaCep::getBuscaViaCEP('XML', $_GET['cep']);

    $dom = new DOMDocument('1.0', 'UTF-8');
    $dom->preserveWhiteSpace = false;
    //$dom->formatOutput = true;

    $xmlcep = $dom->createElement('xmlcep');

    $xmlcep->appendChild($dom->createElement('cep', $class_cep['result']['cep']));
    $xmlcep->appendChild($dom->createElement('logradouro', $class_cep['result']['logradouro']));
    $xmlcep->appendChild($dom->createElement('bairro', $class_cep['result']['bairro']));
    $xmlcep->appendChild($dom->createElement('localidade', $class_cep['result']['localidade']));
    $xmlcep->appendChild($dom->createElement('uf', $class_cep['result']['uf']));

    $dom->appendChild($xmlcep);

    //$dom->save("cep.xml");

    header("Content-Type: text/xml");
    print $dom->saveXML();
}
/*
  else {
  $erro = 13;
  header("Location: /miniaturas/erro.php?erro=$erro");
  exit;
  echo 'Parâmetros inválidos: CEP não informado. <br>Verifique se o JavaScript está habilitado, e caso o erro persista, entre em contato com o administrador.';
  } */
?>