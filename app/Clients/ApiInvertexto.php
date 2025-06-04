<?php
namespace App\Clients;

use Exception;
use Illuminate\Support\Facades\Http;

class ApiInvertexto
{
    public function findHolidays(int $year, string|null $state)
    {
        try{
            $url = "https://api.invertexto.com/v1/holidays/$year";
            $response = Http::get($url, [
                'token' => env('API_INVERTEXTO_TOKEN'),
                'state' => $state
            ]);

            return $response->json();
        } catch(\Exception $err) {
            throw new Exception('Erro ao buscar feriados');
        }
    }
}