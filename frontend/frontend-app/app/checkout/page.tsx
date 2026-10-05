import { cookies } from "next/headers";
import { redirect } from "next/navigation";
import { CheckoutForm } from "@/components/checkout-form";

export default async function CheckoutPage() {
  if (!(await cookies()).get("store_session")?.value) redirect("/login?next=/checkout");
  if (!process.env.NEXT_PUBLIC_STRIPE_PUBLISHABLE_KEY) return <div className="container error-box"><h1>Falta configurar Stripe</h1><p>Agrega la clave publicable de prueba a `.env.local` para habilitar el formulario de pago.</p></div>;
  return <section className="container standard-page"><span className="eyebrow">ÚLTIMO PASO</span><h1>Finaliza tu compra</h1><CheckoutForm /></section>;
}
