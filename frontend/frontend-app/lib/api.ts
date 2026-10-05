import "server-only";
import { cookies } from "next/headers";
import type { ApiEnvelope, Order, Product } from "@/lib/types";

const baseUrl = () => (process.env.LARAVEL_API_URL || "http://127.0.0.1:8000/api").replace(/\/$/, "");

export class ApiError extends Error {
  constructor(message: string, public status: number) { super(message); }
}

export async function apiRequest<T>(path: string, init: RequestInit = {}, token?: string): Promise<T> {
  const response = await fetch(`${baseUrl()}${path}`, {
    ...init,
    cache: "no-store",
    headers: {
      Accept: "application/json",
      "Content-Type": "application/json",
      ...(token ? { Authorization: `Bearer ${token}` } : {}),
      ...init.headers,
    },
  });
  const body = await response.json().catch(() => ({}));
  if (!response.ok) {
    const validation = body?.errors ? Object.values(body.errors).flat().join(" ") : "";
    throw new ApiError(validation || body?.message || "No se pudo completar la solicitud.", response.status);
  }
  return body as T;
}

export async function getSessionToken() {
  return (await cookies()).get("store_session")?.value;
}

export async function getProducts(): Promise<Product[]> {
  const result = await apiRequest<ApiEnvelope<{ data?: Product[] } | Product[]>>("/products?status=active");
  const payload = result.data;
  return Array.isArray(payload) ? payload : payload?.data ?? [];
}

export async function getProduct(id: string): Promise<Product | null> {
  try {
    const result = await apiRequest<ApiEnvelope<Product>>(`/products/${encodeURIComponent(id)}`);
    return result.data;
  } catch (error) {
    if (error instanceof ApiError && error.status === 404) return null;
    throw error;
  }
}

export async function getOrders(): Promise<Order[]> {
  const token = await getSessionToken();
  if (!token) return [];
  const result = await apiRequest<ApiEnvelope<{ data: Order[] } | Order[]>>("/orders", {}, token);
  return Array.isArray(result.data) ? result.data : result.data.data;
}
