<?php

namespace App\Services;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class ZenviaClient
{
    const NEW_ACCOUNT_TYPE          = 1;
    const GENERATED_NUMBERS_TYPE    = 2;
    const DRAWN_NUMBER_TYPE         = 3;
    const DRAWN_DEFAULTER_TYPE      = 4;

    //token: lqphCkQFDtLQe2VsD7xzLFnkikkIleChFRvp

    private Client $client;

    public function __construct()
    {
        $this->client   = new Client([
            'base_uri'          => 'https://api.zenvia.com/v2/',
            'headers'       => [
                'Accept'        		=> 'application/json',
                //'Authorization' 		=> 'Basic ' . base64_encode("atom.rest:#Tom!brvit@2021"),
                'X-API-TONEK'           => 'lqphCkQFDtLQe2VsD7xzLFnkikkIleChFRvp',
                'Content-Type'  		=> 'application/json',
                'Cache-Control'         => 'no-cache'
                //'verify' => false
            ],
        ]);
    }

    /**
     * @throws Exception
     */
    public function sendSMS($type, $items ): string
    {
        $url = 'channels/sms/messages';
        try {
            $response = $this->client->request( 'POST', $url, [
                'json'  => $this->formatSmsData( $type, $items )
            ] );
            return $response->getBody()->getContents();
        } catch ( GuzzleException $e ) {
            throw new Exception($e);
        }
    }

    private function formatSmsData( $type, $items ): bool|string
    {
        $messages = [];
        foreach ( $items as $item ) {
            $message[] = [
                "from"      => "BR Vita Premios",
                "to"        => $item->phone,
                "content"       => $this->getTextByType( $type, $item->arg )
            ];
            $messages[] = $message;
        }
        return json_encode([
            "sendSmsMultiRequest"   => [
                "sendSmsRequestList"    => $messages
            ]
        ]);
    }

    private function getTextByType( $type, $arg ): string
    {
        if ($type == self::NEW_ACCOUNT_TYPE) {
            return "Olá, sua conta foi criada no BR Vita Prêmios!
                    Você irá concorrer a muitos prêmios com seus números da sorte ao comprar na loja.
                    Acesse: brvitapremios.com.br";
        }
        if ($type == self::GENERATED_NUMBERS_TYPE) {
            return "Olá, " . $arg . " números da sorte foram inseridos na sua conta no BR Vita Prêmios!
                    Agora é só torcer muito e acompanhar todos os sorteios.
                    Acesse: brvitapremios.com.br";
        }
        if ($type == self::DRAWN_NUMBER_TYPE) {
            return "Parabéns, seu número da sorte foi contemplado no BR Vita Prêmios!
                    Entre no  site e confira seu número sorteado e seu prêmio!.
                    Acesse: brvitapremios.com.br";
        }
        if ($type == self::DRAWN_DEFAULTER_TYPE) {
            return "Que pena!, seu número da sorte foi contemplado, mas você está inadimplente,
                    conforme o regulamento não poderá receber o prêmio.
                    Acesse: brvitapremios.com.br";
        }
        return "";
    }

}
