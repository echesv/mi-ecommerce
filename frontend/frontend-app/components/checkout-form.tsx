"use client";

import { FormEvent, useState, useTransition } from "react";
import { CardElement, Elements, useElements, useStripe } from "@stripe/react-stripe-js";
import { loadStripe } from "@stripe/stripe-js";
import Link from "next/link";
import { useCart } from "@/components/cart-provider";
import { createOrderAction, payOrderAction } from "@/app/actions";

const stripePromise = loadStripe(process.env.NEXT_PUBLIC_STRIPE_PUBLISHABLE_KEY!);

function CardCheckout() {
  const stripe = useStripe(); const elements = useElements(); const { lines, subtotal, clear } = useCart();
  const [error, setError] = useState(""); const [busy, startTransition] = useTransition(); const [done, setDone] = useState(false);
  async function submit(event: FormEvent<HTMLFormElement>) {
    event.preventDefault(); setError("");
    if (!stripe || !elements) return;
    if (!lines.length) { setError("Tu carrito está vacío."); return; }
    startTransition(async () => {
      const created = await createOrderAction(lines.map(({ product, quantity }) => ({ product_id: product.id, quantity })));
      if (created.error || !created.orderId) { setError(created.error || "No se pudo crear la orden."); return; }
      const card = elements.getElement(CardElement);
      if (!card) { setError("No se pudo cargar el formulario de tarjeta."); return; }
      const method = await stripe.createPaymentMethod({ type: "card", card });
      if (method.error || !method.paymentMethod) { setError(method.error?.message || "No se pudo validar la tarjeta."); return; }
      const paid = await payOrderAction(created.orderId, method.paymentMethod.id);
      if (paid.error) { setError(paid.error); return; }
      clear(); setDone(true);
    });
  }
  if (done) return <div className="success-panel"><span className="success-mark">✓</span><h2>¡Compra confirmada!</h2><p>El pago se procesó correctamente. Puedes consultar tu compra en el historial.</p><Link className="button" href="/ordenes">Ver mis compras</Link></div>;
  if (!lines.length) return <div className="empty"><h2>No hay productos en tu carrito</h2><Link className="button" href="/">Volver a la tienda</Link></div>;
  return <div className="checkout-layout"><form className="payment-card" onSubmit={submit}><h2>Datos de pago</h2><label>Tarjeta de crédito o débito</label><div className="stripe-field"><CardElement options={{ hidePostalCode: true, style: { base: { color: "#18352e", fontFamily: "Inter, sans-serif", fontSize: "16px", "::placeholder": { color: "#8c9993" } } } }} /></div>{error && <p className="form-error" role="alert">{error}</p>}<button className="button full-button" disabled={!stripe || busy}>{busy ? "Procesando…" : `Pagar $${subtotal.toFixed(2)}`}</button><p className="secure-note">Tu pago se procesa de forma segura con Stripe.</p></form><aside className="summary"><h2>Tu pedido</h2>{lines.map(({ product, quantity }) => <div key={product.id}><span>{product.name} × {quantity}</span><strong>${(Number(product.price) * quantity).toFixed(2)}</strong></div>)}<hr /><div className="summary-total"><span>Total</span><strong>${subtotal.toFixed(2)}</strong></div></aside></div>;
}

export function CheckoutForm() { return <Elements stripe={stripePromise}><CardCheckout /></Elements>; }
