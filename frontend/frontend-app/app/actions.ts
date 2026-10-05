"use server";

import { cookies } from "next/headers";
import { revalidatePath } from "next/cache";
import { redirect } from "next/navigation";
import { apiRequest, getSessionToken } from "@/lib/api";
import type { ApiEnvelope, Order } from "@/lib/types";

type AuthResponse = ApiEnvelope<{ token: string; user: { name: string; email: string } }>;
type ActionResult = { error?: string };

async function authenticate(endpoint: "/login" | "/register", formData: FormData): Promise<ActionResult> {
  const payload: Record<string, string> = {};
  for (const key of ["name", "email", "password", "password_confirmation", "phone", "address", "city", "postal_code"]) {
    const value = formData.get(key);
    if (typeof value === "string" && value) payload[key] = value;
  }
  try {
    const result = await apiRequest<AuthResponse>(endpoint, { method: "POST", body: JSON.stringify(payload) });
    (await cookies()).set("store_session", result.data.token, {
      httpOnly: true,
      secure: process.env.NODE_ENV === "production",
      sameSite: "lax",
      path: "/",
      maxAge: 60 * 60 * 24 * 7,
    });
    revalidatePath("/");
  } catch (error) {
    return { error: error instanceof Error ? error.message : "No se pudo iniciar sesión." };
  }
  redirect("/");
}

export async function loginAction(_previous: ActionResult, formData: FormData) {
  return authenticate("/login", formData);
}

export async function registerAction(_previous: ActionResult, formData: FormData) {
  return authenticate("/register", formData);
}

export async function logoutAction() {
  const token = await getSessionToken();
  if (token) {
    try { await apiRequest("/logout", { method: "POST" }, token); } catch { /* expirada: borrar cookie igualmente */ }
  }
  (await cookies()).delete("store_session");
  revalidatePath("/");
  redirect("/");
}

export async function createOrderAction(items: { product_id: number; quantity: number }[]): Promise<{ error?: string; orderId?: number }> {
  const token = await getSessionToken();
  if (!token) return { error: "Inicia sesión para completar tu compra." };
  try {
    const result = await apiRequest<ApiEnvelope<Order>>("/orders", {
      method: "POST",
      body: JSON.stringify({ items }),
    }, token);
    revalidatePath("/ordenes");
    return { orderId: result.data.id };
  } catch (error) {
    return { error: error instanceof Error ? error.message : "No se pudo crear la orden." };
  }
}

export async function payOrderAction(orderId: number, stripePaymentMethodId: string): Promise<ActionResult> {
  const token = await getSessionToken();
  if (!token) return { error: "Tu sesión expiró. Inicia sesión de nuevo." };
  try {
    const orderResponse = await apiRequest<ApiEnvelope<Order>>(`/orders/${orderId}`, {}, token);
    const amount = Number(orderResponse.data.total_amount);
    await apiRequest("/payments", {
      method: "POST",
      body: JSON.stringify({ order_id: orderId, amount, stripe_token: stripePaymentMethodId }),
    }, token);
    revalidatePath("/ordenes");
    revalidatePath("/checkout");
    return {};
  } catch (error) {
    return { error: error instanceof Error ? error.message : "El pago no pudo procesarse." };
  }
}
