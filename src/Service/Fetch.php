<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class Fetch
{

    public function __construct(private HttpClientInterface $client){}

    public function embbed($word){
        $embeded = $this->client->request(
            'POST',
            'http://localhost:11434/api/embeddings',
            [
                "headers"=>[
                    'Content-Type'=>'application/json'
                ],
                "json"=>[
                    "model" => "mistral",
                    "prompt"=>$word,

                ]
            ]
        );

        $data = $embeded->toArray();
        return $data;
    }

}