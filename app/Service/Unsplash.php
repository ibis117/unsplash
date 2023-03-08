<?php

namespace App\Service;

use Illuminate\Support\Facades\Http;

class Unsplash
{

    private $client;

    const base_url = 'https://api.unsplash.com';

    const colors = [
        'black_and_white',
        'black',
        'white',
        'yellow',
        'orange',
        'red',
        'purple',
        'magenta',
        'teal',
        'blue',
    ];
    public function __construct()
    {
        $this->client = Http::withHeaders([
            'Authorization' => 'Client-ID '.config('app.unsplash_client_id')
        ])
        ->baseUrl(self::base_url);
    }


    public function search($url, $params): object|array
    {
        return $this->client->get($url, [
            'per_page' => 12,
            ...$params
        ])
            ->object();
    }

    public function download($url): string
    {
        return $this->client->get($url)
            ->body();
    }

}
