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
                Log::channel("sms")->info($data);
                $response = $this->client->request('POST', $url, [ 'body' => $data ]);
                Log::channel("sms")->info("SMS enviado [ " . $this->getLogActionByType( $type ) . " ] - " . $response->getBody()->getContents());
                Log::channel("sms")->info("SMS enviado [ " . $this->getLogActionByType( $type ) . " ] - " . $item->phone);
            } catch (Exception $e) {
                Log::channel("sms")->error("Erro ao enviar SMS [ " . $this->getLogActionByType( $type ) . " ] - " . $item->phone . " - message: " . $e->getMessage());
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
            return "Ola, sua conta foi criada no BR Vita Premios! Você ira concorrer a muitos premios com seus numeros da sorte ao comprar na loja. Acesse: brvitapremios.com.br";
        }
        if ($type == self::GENERATED_NUMBERS_TYPE) {
            return "Ola, " . $arg . " numeros da sorte foram inseridos na sua conta no BR Vita Premios! Agora e so torcer muito e acompanhar todos os sorteios. Acesse: brvitapremios.com.br";
        }
        if ($type == self::DRAWN_NUMBER_TYPE) {
            return "Parabens, seu numero da sorte foi contemplado no BR Vita Premios! Entre no  site e confira seu numero sorteado e seu premio!. Acesse: brvitapremios.com.br";
        }
        if ($type == self::DRAWN_DEFAULTER_TYPE) {
            return "Que pena!, seu numero da sorte foi contemplado, mas você esta inadimplente, conforme o regulamento nao podera receber o premio. Acesse: brvitapremios.com.br";
        }
        return "";
    }

    private function getLogActionByType( $type ): string
    {
        if ($type == self::NEW_ACCOUNT_TYPE) {
            return "Nova Conta";
        }
        if ($type == self::GENERATED_NUMBERS_TYPE) {
            return "Número Gerado";
        }
        if ($type == self::DRAWN_NUMBER_TYPE) {
            return "Número Sorteado";
        }
        if ($type == self::DRAWN_DEFAULTER_TYPE) {
            return "Número Sorteado Inadimplente";
        }
        return "";
    }

}
