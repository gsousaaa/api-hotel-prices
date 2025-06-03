<?php

namespace App\Services;

use App\Enums\UserRole;
use App\Exceptions\BadRequestException as BadRequest;
use App\Repositories\CompanyRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;


class AuthService
{
    public function __construct(
        protected UserRepository $userRepository,
        protected CompanyRepository $companyRepository
    ) {
    }

    public function register(array $data)
    {
        $existsCompany = $this->companyRepository->findByCnpj($data['cnpj']);

        if ($existsCompany) {
            throw new BadRequest(`Empresa já cadastrada!`);
        }

        $createdCompany = $this->companyRepository->create([
            'name' => $data['company_name'],
            'cnpj' => $data['cnpj'],
            'uf' => $data['uf']
        ]);

        $existsUser = $this->userRepository->findByEmail($data['email']);

        if ($existsUser) {
            throw new BadRequest(`Usuário já cadastrado!`);
        }

        $createdUser = $this->userRepository->create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => UserRole::MANAGER->value,
            'company_id' => $createdCompany['id']
        ]);

        return JWTAuth::fromUser($createdUser);
    }

    public function login(array $credentials)
    {
        $user = $this->userRepository->findByEmail($credentials['email']);

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            throw new BadRequest('Email e/ou senha incorretos!');
        }

        return JWTAuth::fromUser($user);
    }
}