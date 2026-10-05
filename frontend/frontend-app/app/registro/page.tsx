"use client";
import Link from "next/link";
import { useActionState } from "react";
import { registerAction } from "@/app/actions";

export default function RegisterPage() {
  const [state, action, pending] = useActionState(registerAction, {});
  return <section className="auth-wrap"><form className="auth-card" action={action}><span className="eyebrow">CREA TU CUENTA</span><h1>Únete a la tienda</h1><p>Regístrate para guardar tus compras y seguir tus pedidos.</p><label htmlFor="name">Nombre completo</label><input id="name" name="name" autoComplete="name" required /><label htmlFor="email">Correo electrónico</label><input id="email" name="email" type="email" autoComplete="email" required /><label htmlFor="password">Contraseña</label><input id="password" name="password" type="password" autoComplete="new-password" required minLength={8} /><label htmlFor="password_confirmation">Confirma tu contraseña</label><input id="password_confirmation" name="password_confirmation" type="password" autoComplete="new-password" required minLength={8} />{state.error && <p className="form-error" role="alert">{state.error}</p>}<button className="button full-button" disabled={pending}>{pending ? "Creando cuenta…" : "Crear cuenta"}</button><p className="auth-switch">¿Ya tienes cuenta? <Link href="/login">Inicia sesión</Link></p></form></section>;
}
