<?php

namespace App\Services;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

class ZenviaClient
{
    const NEW_ACCOUNT_TYPE          = 1;
    const GENERATED_NUMBERS_TYPE    = 2;
    const DRAWN_NUMBER_TYPE         = 3;
    const DRAWN_DEFAULTER_TYPE      = 4;

    private Client $client;

    public function __construct()
    {
        $this->client   = new Client([
            'base_uri'          => 'https://api.zenvia.com/v2/channels/',
            'headers'       => [
                'Accept'        		=> 'application/json',
                'X-API-TOKEN'           => 'lqphCkQFDtLQe2VsD7xzLFnkikkIleChFRvp',
                'Content-Type'  		=> 'application/json',
                'Cache-Control'         => 'no-cache'
            ],
        ]);
    }


    public function sendSMS($type, $items ): void
    {
        $url = 'sms/messages';
        foreach ( $items as $item ) {
            sleep(2);
            try {
                Artisan::call('cache:clear');
                $data = $this->formatSmsData($type, $item);
                Log::info($data);
                $response = $this->client->request('POST', $url, [ 'body' => $data ]);
                Log::info("SMS enviado [ Nova Conta ] - " . $response->getBody()->getContents());
                Log::info("SMS enviado [ Nova Conta ] - " . $item->phone);
            } catch (Exception $e) {
                Log::error("Erro ao enviar SMS [ Nova Conta ] - " . $item->phone . " - message: " . $e->getMessage());
            }
        }
    }

    private function formatSmsData( $type, $item ): bool|string
    {
        return json_encode([
                "from"          => "BR Vita Premios",
                "to"            => $item->phone,
                "contents"      => [
                        [
                            "type"      => "text",
                            "text"      => $this->getTextByType( $type, $item->arg )
                        ]
                    ]
            ]);
    }

    private function getTextByType( $type, $arg ): string
    {
        if ($type == self::NEW_ACCOUNT_TYPE) {
            return "Olá, sua conta foi criada no BR Vita Prêmios! Você irá concorrer a muitos prêmios com seus números da sorte ao comprar na loja. Acesse: brvitapremios.com.br";
        }
        if ($type == self::GENERATED_NUMBERS_TYPE) {
            return "Olá, " . $arg . " números da sorte foram inseridos na sua conta no BR Vita Prêmios! Agora é só torcer muito e acompanhar todos os sorteios. Acesse: brvitapremios.com.br";
        }
        if ($type == self::DRAWN_NUMBER_TYPE) {
            return "Parabéns, seu número da sorte foi contemplado no BR Vita Prêmios! Entre no  site e confira seu número sorteado e seu prêmio!. Acesse: brvitapremios.com.br";
        }
        if ($type == self::DRAWN_DEFAULTER_TYPE) {
            return "Que pena!, seu número da sorte foi contemplado, mas você está inadimplente, conforme o regulamento não poderá receber o prêmio. Acesse: brvitapremios.com.br";
        }
        return "";
    }

}
