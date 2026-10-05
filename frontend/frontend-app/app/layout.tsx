import type { Metadata } from "next";
import { CartProvider } from "@/components/cart-provider";
import { Header } from "@/components/header";
import { Footer } from "@/components/header-client";
import "./globals.css";

export const metadata: Metadata = { title: "SVCommerce — TU Tienda en línea", description: "Descubre productos seleccionados y compra de forma segura." };

export default function RootLayout({ children }: Readonly<{ children: React.ReactNode }>) {
  return <html lang="es"><body><CartProvider><Header /><main>{children}</main><Footer /></CartProvider></body></html>;
}
