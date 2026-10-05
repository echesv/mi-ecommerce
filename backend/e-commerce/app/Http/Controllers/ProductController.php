<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Listar todos los productos (PÚBLICO)
     */
    public function index(Request $request)
    {
        try {
            $query = Product::query();

            // Filtrar por estado
            if ($request->has('status')) {
                $query->where('status', $request->status);
            }

            // Búsqueda por nombre
            if ($request->has('search')) {
                $query->where('name', 'like', '%' . $request->search . '%');
            }

            // Paginación
            $products = $query->paginate(15);

            return response()->json([
                'success' => true,
                'message' => 'Productos obtenidos exitosamente',
                'data' => $products,
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener productos',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Ver detalle de un producto (PÚBLICO)
     */
    public function show($id)
    {
        try {
            $product = Product::findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $product,
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Producto no encontrado',
                'error' => $e->getMessage(),
            ], 404);
        }
    }

    /**
     * Crear producto (PROTEGIDO)
     */
    public function store(StoreProductRequest $request)
    {
        try {
            $product = Product::create($request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Producto creado exitosamente',
                'data' => $product,
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear el producto',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Actualizar producto (PROTEGIDO)
     */
    public function update(UpdateProductRequest $request, $id)
    {
        try {
            $product = Product::findOrFail($id);
            $product->update($request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Producto actualizado exitosamente',
                'data' => $product,
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar el producto',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Eliminar producto (PROTEGIDO)
     */
    public function destroy($id)
    {
        try {
            $product = Product::findOrFail($id);
            $product->delete();

            return response()->json([
                'success' => true,
                'message' => 'Producto eliminado exitosamente',
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar el producto',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}