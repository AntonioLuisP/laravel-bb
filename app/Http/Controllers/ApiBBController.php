<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class ApiBBController extends Controller
{
    public function token(Request $request)
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
            dd($token->access_token);
        } catch (GuzzleException $e) {
            echo $e->getMessage();
        }
    }

    public function registrar()
    {
        dd('registrar');
    }

    public function listar()
    {
        dd('listar');
    }

    public function consultar()
    {
        dd('consultar');
    }

    public function baixar()
    {
        dd('baixar');
    }

    public function atualizar()
    {
        dd('atualizar');
    }
}
