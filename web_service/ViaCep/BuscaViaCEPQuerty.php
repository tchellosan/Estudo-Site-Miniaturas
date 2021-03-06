<?php
namespace Jarouche\ViaCEP;
/**
 * Classe BuscaViaCEPQuerty para buscar os dados no formato QUERTY
 * @author Rodrigo Jarouche <rjarouche@gmail.com>
 * @package ViaCEP
 * @version 0.1
 */

class BuscaViaCEPQuerty extends BuscaViaCEP
{
    const CEP_METHOD = '/querty/';

    /**
     * Método retornaCEP
     * Método para o retorno dos dados do CEP pesquisado
     * @param string $cep
     * @return Array Array com os dados do CEP pesquisado, caso não exista irá retornar um array ('erro' => true);
     */

    public function retornaCEP($cep)
    {
        $this->fazRequisicaoFacade($cep);
        $results = '';
        parse_str($this->results_string, $results);
        return $results;
    }
}
