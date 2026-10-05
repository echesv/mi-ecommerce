export type Product = {
  id: number;
  name: string;
  description: string;
  price: string | number;
  quantity: number;
  sku: string;
  image_url: string | null;
  status: "active" | "inactive";
};

export type OrderItem = {
  id: number;
  product_id: number;
  quantity: number;
  price: string | number;
  product?: Product;
};

export type Order = {
  id: number;
  total_amount: string | number;
  status: "pending" | "completed" | "cancelled";
  notes?: string | null;
  items?: OrderItem[];
  created_at: string;
};

export type ApiEnvelope<T> = { success?: boolean; message?: string; data: T };
