import Link from "next/link";
import { cookies } from "next/headers";
import { logoutAction } from "@/app/actions";
import { CartCount } from "@/components/header-client";

export async function Header() {
  const signedIn = Boolean((await cookies()).get("store_session")?.value);
  return <header className="site-header"><div className="header-inner"><Link href="/" className="brand"><span className="brand-mark">JE</span> SVCommerce<span className="brand-dot">.</span></Link><nav><Link href="/">Tienda</Link><Link href="/ordenes">Mis compras</Link><Link href="/carrito" className="cart-link">Carrito <CartCount /></Link>{signedIn ? <form action={logoutAction}><button className="nav-button">Salir</button></form> : <Link href="/login">Ingresar</Link>}</nav></div></header>;
}
