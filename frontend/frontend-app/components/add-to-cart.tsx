"use client";
import { useState } from "react";
import { useCart } from "@/components/cart-provider";
import type { Product } from "@/lib/types";

export function AddToCart({ product }: { product: Product }) {
  const { add } = useCart();
  const [added, setAdded] = useState(false);
  return <button className="button" disabled={product.quantity < 1} onClick={() => { add(product); setAdded(true); window.setTimeout(() => setAdded(false), 1400); }}>
    {product.quantity < 1 ? "Agotado" : added ? "Agregado ✓" : "Agregar al carrito"}
  </button>;
}
