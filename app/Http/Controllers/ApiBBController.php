<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;

class ApiBBController extends Controller
{
    public function token()
    {
        try {
            $guzzle = new Client([
                'headers' => [
                    'gw_dev_app_key' => config('apiCobranca.gw_dev_app_key'),
                    'Authorization' => config('apiCobranca.authorization'),
                    'Content-Type' => 'application/x-www-form-urlencoded'
                ],
                'verify' => false
            ]);

            $response = $guzzle->request('POST', 'https://oauth.sandbox.bb.com.br/oauth/token?gw_dev_app_key=' . config('apiCobranca.gw_dev_app_key'), [
                'form_params' => [
                    'grant_type' => 'client_credentials',
                    'client_id' => config('apiCobranca.client_id'),
                    'client_secret' => config('apiCobranca.client_secret'),
                    'scope' => 'cobrancas.boletos-info cobrancas.boletos-requisicao'
                ]
            ]);

            $body = $response->getBody();
            $contents = $body->getContents();

            $token = json_decode($contents);
            return $token->access_token;
        } catch (GuzzleException $e) {
            echo $e->getMessage();
        }
    }

    public function registrar()
    {
        $body = [
            'numeroConvenio' => '',
            'numeroCarteira' => '',
            'numeroVariacaoCarteira' => '',
            'codigoModalidade' => '',
            'dataEmissao' => '',
            'dataVencimento' => '',
            'valorOriginal' => '',
            'valorAbatimento' => '',
            'quantidadeDiasProtesto',
            'indicadorNumeroDiasLimiteRecebimento' => '',
            'numeroDiasLimiteRecebimento' => '',
            'codigoAceite'  => 'A',
            'codigoTipoTitulo' => 'DS',
            'descricaoipoTitulo' => '',
            'indicadorPermissaoRecebimentoParcial' => 'N',
            'numeroTituloBeneficiario' => '',
            'textoCampoUtiliacaoBeneficiario' => 'TESTE',
            'codigoTipoContaCaucao' => '',
            'numeroTituloCliente' => '',
            'textoMensagemBloquetoOcorrencia' => 'TESTE',
            'pagador' => [
                'tipoRegistro' => '',
                'numeroRegistro' => '',
                'nome' => 'TESTE',
                'endereco' => '',
                'cep' => '',
                'cidade' => '',
                'bairro' => '',
                'uf' => '',
                'telefone' => ''
            ],
            'email' => ''
        ];

        $body = json_encode($body);

        try {
            $guzzle = new Client([
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->token(),
                    'Content-Type' => 'application/json'
                ],
                'verify' => false
            ]);

            $response = $guzzle->request('POST', 'https://api.hm.bb.com.br/cobrancas/v1/boletos?gw_dev_app_key=' . config('apiCobranca.gw_dev_app_key'), [
                'body' => $body
            ]);

            $body = $response->getBody();

            $contents = $body->getContents();

            $boleto = json_decode($contents);

            dd($boleto);
        } catch (ClientException $e) {
            echo $e->getMessage();
        }
    }

    public function listar()
    {
        try {
            $guzzle = new Client([
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->token(),
                    'Content-Type' => 'application/json'
                ],
                'verify' => false
            ]);

            $response = $guzzle->request(
                'POST',
                'https://api.hm.bb.com.br/cobrancas/v1/boletos?gw_dev_app_key=' . config('apiCobranca.gw_dev_app_key') .
                    '&agenciaBenefciario=' . '452' .
                    '&contaBeneficiario=' . '123873' .
                    '&indicadorSituacao=' . 'B' .
                    '&indice=' . '300' .
                    '&codigoEstadoTituloCobranca=' . '7' .
                    '&dataInicioMovimento=' . '01.01.2021' .
                    '&dataFimMovimento=' . '01.01.2021'
            );

            $body = $response->getBody();

            $contents = $body->getContents();

            $boleto = json_decode($contents);

            dd($boleto);
        } catch (GuzzleException $e) {
            echo $e->getMessage();
        }
    }

    public function consultar()
    {
        $id = '';
        try {
            $guzzle = new Client([
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->token(),
                    'Content-Type' => 'application/json'
                ],
                'verify' => false
            ]);

            $response = $guzzle->request(
                'POST',
                'https://api.hm.bb.com.br/cobrancas/v1/boletos/' .
                    $id .
                    '?gw_dev_app_key=' . config('apiCobranca.gw_dev_app_key') .
                    '&numeroConvenio=' . '123873'
            );

            $body = $response->getBody();

            $contents = $body->getContents();

            $boleto = json_decode($contents);

            dd($boleto);
        } catch (GuzzleException $e) {
            echo $e->getMessage();
        }
    }

    public function baixar()
    {
        dd('baixar');
    }

    public function atualizar()
    {
        $id = '';

        $dados = [
            'numeroConvenio' => '',
            'indicadorNovaDataVencimento' => '',
            'alteracaoData' => [
                'novaDataVencimento' => '13.02.2021'
            ]
        ];

        $dados = json_encode($dados);

        try {
            $guzzle = new Client([
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->token(),
                    'Content-Type' => 'application/json'
                ],
                'verify' => false
            ]);

            $response = $guzzle->request(
                'PATCH',
                'https://api.hm.bb.com.br/cobrancas/v1/boletos/' . $id . '?gw_dev_app_key=' . config('apiCobranca.gw_dev_app_key'), [
                    'body' => $dados
                ]
            );

            $body = $response->getBody();

            $contents = $body->getContents();

            $boleto = json_decode($contents);

            dd($boleto);
        } catch (GuzzleException $e) {
            echo $e->getMessage();
        }
    }
}
