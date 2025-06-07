# API de Previsoes de Preco

## Rodar o projeto

### Configure as variaveis de ambiente
Exemplo: 
```

DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=test_db
DB_USERNAME=root
DB_PASSWORD=root

API_INVERTEXTO_TOKEN=19921|1D5F0UMckUaLgtTrDPr9pV7MnlnU7uao
```
Execute o comando no seu terminal

```
docker-composer up --build
```

## Endpoints

## 🔐 Rotas de Autenticação

### `POST /auth/login`

Login do usuário.

**Body:**
```json
{
  "email": "teste@gmail.com",
  "password": "teste"
}
```

---

### `POST /auth/register`

Cadastro de empresa e usuário responsável.

**Body:**
```json
{
  "company_name": "Hotel Bela Vista",
  "cnpj": "12.345.678/0001-90",
  "uf": "SP",
  "name": "João Silva",
  "email": "joao@belavista.com",
  "password": "senha123"
}
```

---

## 📦 Rotas da API

### `GET /api/rooms`

Retorna os quartos da empresa logada com seus respectivos preços por data.

**Exemplo de resposta:**
```json
[
  {
    "id": 1,
    "name": "Quarto 101",
    "type": "Standard",
    "created_at": "2025-06-05T00:58:54.000000Z",
    "updated_at": null,
    "company_id": 1,
    "prices": [
      {
        "id": 10,
        "room_id": 1,
        "price": "242.00",
        "effective_date": "2025-10-03",
        "created_at": "2025-06-05T01:01:19.000000Z",
        "updated_at": "2025-06-05T01:01:19.000000Z"
      }
    ]
  }
]
```

---


### `POST /api/rooms/{roomId}/price`

Calcula a previsão de preço para uma data desejada, levando em consideração a taxa de ocupação e se o dia é feriado (via API externa da **Invertexto**) ou fim de semana.

#### Payload

```json
{
  "effectiveDate": "string (opcional, padrão: dia atual + 1)",
  "occupancyRate": number
}
```

#### Exemplo de Requisição

```json
{
"effectiveDate": "2025-06-07",
  "occupancyRate": 85
}
```

#### Exemplo de Resposta

```json
{
  "price": 383.33,
  "isDayOff": true,
  "effectiveDate": "2025-06-08",
  "created_at": "2025-06-07T00:42:36.000000Z"
}
```

#### Observações

- A verificação de feriados é feita usando a [API da Invertexto](https://api.invertexto.com/v1/holidays).

---


