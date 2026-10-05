# Mi E-commerce

Tienda en línea con API REST en Laravel 12 y frontend en Next.js. Permite consultar productos, registrarse, iniciar sesión, gestionar el carrito, crear órdenes y procesar pagos con Stripe.

## Estructura

- `backend/e-commerce`: API, base de datos, autenticación y pagos.
- `frontend/frontend-app`: interfaz de la tienda.

## Requisitos

PHP, Composer, Node.js, npm y una base de datos configurada para Laravel.

## Iniciar el backend

`cd backend/e-commerce
composer install
cp .env.example .env
php artisan key:generate
`

## Configura la base de datos y las claves de Stripe en .env. Después, ejecuta:
`php artisan migrate --seed
php artisan serve`

La API estará disponible en http://127.0.0.1:8000. Si Swagger está configurado, consulta http://127.0.0.1:8000/api/documentation.

## Iniciar el frontend
En otra terminal:
`cd frontend/frontend-app
npm install
cp .env.example .env.local`

Configura LARAVEL_API_URL y NEXT_PUBLIC_STRIPE_PUBLISHABLE_KEY en .env.local. Luego inicia Next.js:
`npm run dev`
Abre http://localhost:3000.
No subas .env ni .env.local a GitHub. Usa los archivos .env.example como plantilla.
