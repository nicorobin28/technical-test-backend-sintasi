# SINTASI Fullstack Developer Test — Backend

Laravel REST API + MySQL. Implemented from the supplied test brief.

## Scope

- Login
- Patient registration
- Visit creation
- Auto medical record number: `RM0001`, `RM0002`, ...
- Visit count calculated from visit records
- Patient lookup by medical record number before creating a visit
- NIK and email encrypted at rest with Laravel `Crypt`
- Username/email/password/NIK encrypted in the HTTP payload with RSA before reaching the API
- Laravel Sanctum bearer token authentication
- Seeder login user
- Postman collection included

## Postman

Import `postman/Testing Restful-API - Technical Tes postman_collection.json`.

## Setup

```bash
composer install
cp .env.example .env
php artisan key:generate
```

Create MySQL database `sintasidb`, then set DB credentials in `.env`.

Generate an RSA key pair. The private key stays on the backend; the public key is copied to the frontend `.env`.

Example using OpenSSL:

```bash
openssl genrsa -out storage/app/private/rsa_private.pem 2048
openssl rsa -in storage/app/private/rsa_private.pem -pubout -out storage/app/private/rsa_public.pem
```

Then put the PEM contents into `RSA_PRIVATE_KEY` and `RSA_PUBLIC_KEY` in `.env`. Preserve `\\n` when using a single-line environment value.

Run:

```bash
php artisan migrate --seed
php artisan serve --host=127.0.0.1 --port=8000
```

Seeded login:

- username: `admin`
- password: `Admin123!`

Change the seeded password for any real deployment.

## API

| Method | Endpoint                              |
| ------ | ------------------------------------- |
| POST   | `/api/login`                          |
| POST   | `/api/patients`                       |
| GET    | `/api/patients`                       |
| GET    | `/api/patients/{medicalRecordNumber}` |
| POST   | `/api/visits`                         |
| POST   | `/api/logout`                         |
| GET    | `/api/me`                             |
| GET    | `/api/health`                         |
