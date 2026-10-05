import Link from "next/link";
import type { Product } from "@/lib/types";
import { AddToCart } from "@/components/add-to-cart";

export function ProductGrid({ products }: { products: Product[] }) {
  if (!products.length) return <div className="empty"><h2>Aún no hay productos</h2><p>Cuando agregues productos activos en el backend, aparecerán aquí.</p></div>;
  return <div className="product-grid">{products.map((product) => <article className="product-card" key={product.id}>
    <Link href={`/productos/${product.id}`} className="product-image">
      {product.image_url ? <img src={product.image_url} alt={product.name} loading="lazy" /> : <div className="image-placeholder"><span>EC</span></div>}
    </Link>
    <div className="product-info"><span className="eyebrow">{product.sku}</span><Link href={`/productos/${product.id}`}><h3>{product.name}</h3></Link><p className="price">${Number(product.price).toFixed(2)}</p><AddToCart product={product} /></div>
  </article>)}</div>;
}
