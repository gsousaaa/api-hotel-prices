<?php

namespace App\Services;

use App\Enums\UserRole;
use App\Repositories\UserRepository;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Tymon\JWTAuth\Facades\JWTAuth;
use function PHPUnit\Framework\throwException;

class AuthService 
{
    public function __construct(
        protected UserRepository $userRepository,
        protected CompanyRepository $companyRepository
    ) {}

    public function register(array $data) 
    {
        $existsCompany = $this->userRepository->findByCnpj($data['cnpj']);

        if($existsCompany) {
            throw new Exception(`Empresa já cadastrada!`);
        }

        $createdCompany = $this->companyRepository->create([
            'name' => $data['company_name'],
            'cnpj' => $data['cnpj']
        ]);

        $existsUser = $this->userRepository->findByEmail($data['email']);

        if($existsUser) {
            throw new Exception(`Usuário já cadastrado!`);
        }

        $createdUser = $this->userRepository.create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password_hash' => Hash::make($data['password']),
            'role' => UserRole::MANAGER->value,
            'company_id' => $createdCompany->id
        ]);

         return JWTAuth::fromUser($createdUser);
    }

    public function login(array $credentials) {

    }
}