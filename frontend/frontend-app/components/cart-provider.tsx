"use client";

import { createContext, useContext, useEffect, useMemo, useState } from "react";
import type { Product } from "@/lib/types";

export type CartLine = { product: Product; quantity: number };
type CartContextValue = { lines: CartLine[]; count: number; subtotal: number; add: (product: Product) => void; setQuantity: (id: number, quantity: number) => void; remove: (id: number) => void; clear: () => void };
const CartContext = createContext<CartContextValue | null>(null);

export function CartProvider({ children }: { children: React.ReactNode }) {
  const [lines, setLines] = useState<CartLine[]>([]);
  useEffect(() => {
    try { const value = localStorage.getItem("ecommerce-cart"); if (value) setLines(JSON.parse(value)); } catch { localStorage.removeItem("ecommerce-cart"); }
  }, []);
  useEffect(() => { localStorage.setItem("ecommerce-cart", JSON.stringify(lines)); }, [lines]);
  const value = useMemo<CartContextValue>(() => ({
    lines,
    count: lines.reduce((sum, line) => sum + line.quantity, 0),
    subtotal: lines.reduce((sum, line) => sum + Number(line.product.price) * line.quantity, 0),
    add: (product) => setLines((old) => {
      const found = old.find((line) => line.product.id === product.id);
      if (found) return old.map((line) => line.product.id === product.id ? { ...line, quantity: Math.min(line.quantity + 1, product.quantity) } : line);
      return [...old, { product, quantity: 1 }];
    }),
    setQuantity: (id, quantity) => setLines((old) => quantity <= 0 ? old.filter((line) => line.product.id !== id) : old.map((line) => line.product.id === id ? { ...line, quantity: Math.min(quantity, line.product.quantity) } : line)),
    remove: (id) => setLines((old) => old.filter((line) => line.product.id !== id)),
    clear: () => setLines([]),
  }), [lines]);
  return <CartContext.Provider value={value}>{children}</CartContext.Provider>;
}

export function useCart() {
  const value = useContext(CartContext);
  if (!value) throw new Error("useCart debe usarse dentro de CartProvider");
  return value;
}
