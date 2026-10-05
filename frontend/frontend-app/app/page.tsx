import { Suspense } from "react";
import { getProducts } from "@/lib/api";
import { ProductGrid } from "@/components/product-grid";

async function Catalog() { const products = await getProducts(); return <ProductGrid products={products} />; }
function CatalogSkeleton() { return <div className="product-grid">{Array.from({ length: 6 }, (_, i) => <div className="skeleton-card" key={i}><div className="skeleton-image" /><div className="skeleton-line" /><div className="skeleton-line short" /></div>)}</div>; }

export default function HomePage() {
  return <><section className="hero"><div className="container hero-content"><span className="eyebrow hero-eyebrow">SELECCIÓN DE TEMPORADA</span><h1>Encuentra eso<br /><em>que te hace bien.</em></h1><p>Productos elegidos para acompañar tu día a día.</p><a className="button button-light" href="#catalogo">Explorar productos <span>↓</span></a><div className="hero-index">01 <span /> 03</div></div><div className="hero-orb orb-one" /><div className="hero-orb orb-two" /></section><section className="container catalog-section" id="catalogo"><div className="section-heading"><div><span className="eyebrow">NUESTRA COLECCIÓN</span><h2>Productos destacados</h2></div><p>Calidad que se nota.<br />Detalles que importan.</p></div><Suspense fallback={<CatalogSkeleton />}><Catalog /></Suspense></section></>;
}
