<?php

 namespace App\Repositories;

 use App\Models\Company;

 class CompanyRepository {
    public function create(array $data) {
        return Company::create($data);
    }

    public function findByCnpj(string $cnpj) {
        return Company::where('cnpj', $cnpj)->first();
    }
 }