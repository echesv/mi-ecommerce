"use client";
import Link from "next/link";
import { useCart } from "@/components/cart-provider";
export function CartCount() { const { count } = useCart(); return <span className="cart-badge">{count}</span>; }
export function Footer() { return <footer className="site-footer"><div className="container footer-inner"><span>SVCommerce<span className="brand-dot">.</span></span><span>Compra fácil, vive mejor.</span><Link href="/">Volver a la tienda ↑</Link></div></footer>; }
