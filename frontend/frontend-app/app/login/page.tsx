"use client";
import Link from "next/link";
import { useActionState } from "react";
import { loginAction } from "@/app/actions";

export default function LoginPage() {
  const [state, action, pending] = useActionState(loginAction, {});
  return <section className="auth-wrap"><form className="auth-card" action={action}><span className="eyebrow">GRACIAS POR ESTAR DE VUELTA! :D</span><h1>Inicia sesión</h1><p>Accede para ver tus compras y continuar con tu pedido.</p><label htmlFor="email">Correo electrónico</label><input id="email" name="email" type="email" autoComplete="email" required /><label htmlFor="password">Contraseña</label><input id="password" name="password" type="password" autoComplete="current-password" required minLength={8} />{state.error && <p className="form-error" role="alert">{state.error}</p>}<button className="button full-button" disabled={pending}>{pending ? "Ingresando…" : "Ingresar"}</button><p className="auth-switch">¿Todavía no tienes cuenta? <Link href="/registro">Regístrate</Link></p></form></section>;
}
