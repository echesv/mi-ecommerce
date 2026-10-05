# Frontend E-commerce (Next.js)

Next.js App Router frontend para la API Laravel del repositorio `echesv/e-commerce`.

## Configuración local

1. Copia esta carpeta como `frontend/frontend-app` junto a `backend/e-commerce`.
2. Desde esta carpeta, ejecuta `npm install`.
3. Copia `.env.example` como `.env.local` y configura `LARAVEL_API_URL` y `NEXT_PUBLIC_STRIPE_PUBLISHABLE_KEY`.
4. Inicia Laravel (`php artisan serve`) y Next (`npm run dev`).
5. Abre `http://localhost:3000`.

El token Sanctum se guarda en una cookie `httpOnly`; las Server Actions hacen las mutaciones y nunca exponen el token al navegador. El carrito se mantiene en `localStorage`.

## Contrato usado

- `GET /api/products`, `GET /api/products/{id}`
- `POST /api/register`, `POST /api/login`, `POST /api/logout`
- `POST /api/orders` con `{ items: [{ product_id, quantity }] }`
- `GET /api/orders`
- `POST /api/payments` con `{ order_id, stripe_token, amount }`

## Requisitos previos en el backend

`app/Http/Requests/StoreOrderRequest.php` actualmente devuelve `false` en `authorize()` y no define reglas. Laravel rechazará todas las solicitudes de creación de órdenes hasta que se permita al usuario autenticado y se validen `items.*.product_id` y `items.*.quantity`.

El endpoint de pago confirma el PaymentIntent en el servidor usando el identificador de PaymentMethod creado por Stripe.js. Para tarjetas que requieren autenticación 3D Secure, el backend debería crear/devolver un PaymentIntent `client_secret` y el frontend completar `stripe.confirmCardPayment`; el contrato actual no lo expone.
