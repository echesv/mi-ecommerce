import { notFound } from "next/navigation";
import Link from "next/link";
import { getProduct } from "@/lib/api";
import { AddToCart } from "@/components/add-to-cart";

export default async function ProductPage({ params }: { params: Promise<{ id: string }> }) {
  const { id } = await params; const product = await getProduct(id); if (!product || product.status !== "active") notFound();
  return <section className="container detail-page"><Link href="/" className="back-link">← Volver a la tienda</Link><div className="detail-grid"><div className="detail-image">{product.image_url ? <img src={product.image_url} alt={product.name} /> : <div className="image-placeholder"><span>EC</span></div>}</div><div className="detail-info"><span className="eyebrow">{product.sku}</span><h1>{product.name}</h1><p className="detail-price">${Number(product.price).toFixed(2)} <small>USD</small></p><p className="detail-description">{product.description}</p><p className="stock">{product.quantity > 0 ? `${product.quantity} disponibles` : "Agotado"}</p><AddToCart product={product} /><div className="detail-perks"><span>✓ Pago seguro</span><span>✓ Compra protegida</span></div></div></div></section>;
}
