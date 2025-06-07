<?php
namespace App\Clients;

use App\Exceptions\BadRequestException;
use Exception;
use Illuminate\Support\Facades\Http;

class ApiInvertexto
{
    public function findHolidays(int $year, string|null $state)
    {
        $url = "https://api.invertexto.com/v1/holidays/$year";
        $response = Http::get($url, [
            'token' => env('API_INVERTEXTO_TOKEN'),
            'state' => $state
        ])->throw();

        return $response->json();
    }
}