import Link from "next/link";
import { cookies } from "next/headers";
import { redirect } from "next/navigation";
import { getOrders } from "@/lib/api";
import { Suspense } from "react";

async function OrderList() {
  const orders = await getOrders();
  if (!orders.length) return <div className="empty"><h2>Aún no tienes compras</h2><p>Cuando completes tu primera compra, aparecerá aquí.</p><Link className="button" href="/">Explorar tienda</Link></div>;
  return <div className="order-list">{orders.map((order) => <article className="order-card" key={order.id}><div className="order-top"><div><span className="eyebrow">ORDEN #{order.id}</span><p>{new Date(order.created_at).toLocaleDateString("es", { year: "numeric", month: "long", day: "numeric" })}</p></div><span className={`status status-${order.status}`}>{order.status === "completed" ? "Completada" : order.status === "pending" ? "Pendiente" : "Cancelada"}</span></div><div className="order-items">{order.items?.map((item) => <p key={item.id}>{item.product?.name ?? `Producto ${item.product_id}`} <span>× {item.quantity}</span></p>)}</div><div className="order-total"><span>Total</span><strong>${Number(order.total_amount).toFixed(2)}</strong></div></article>)}</div>;
}

export default async function OrdersPage() {
  if (!(await cookies()).get("store_session")?.value) redirect("/login?next=/ordenes");
  return <section className="container standard-page"><span className="eyebrow">TU CUENTA</span><h1>Historial de compras</h1><Suspense fallback={<div className="page-loading"><span className="spinner" /></div>}><OrderList /></Suspense></section>;
}
