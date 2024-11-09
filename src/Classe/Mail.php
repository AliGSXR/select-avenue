<?php

namespace App\Classe;

use Mailjet\Client;
use Mailjet\Resources;

class Mail{
    public function send($to_mail, $to_name, $subject, $body){
        $mj = new Client($_ENV['MJ_APIKEY_PUBLIC'], $_ENV['MJ_APIKEY_PRIVATE'],true,['version' => 'v3.1']);

        $body = [
            'Messages' => [
                [
                    'From' => [
                        'Email' => "ali.belaidouni@gmail.com",
                        'Name' => "Select Avenue"
                    ],
                    'To' => [
                        [
                            'Email' => $to_mail,
                            'Name' => $to_name
                        ]
                    ],
                    'TemplateID'=> 6457167,
                    'TemplateLanguage' => true,
                    'Subject' => $subject,
                    'Variables'=>[
                        'content' => $body
                    ]

                ]
            ]
        ];
        $response = $mj->post(Resources::$Email, ['body' => $body]);

        $response->success() && var_dump($response->getData());
    }
}