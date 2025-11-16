<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    // Listar todos los productos con relaciones y sus imágenes
    public function index()
    {
        $products = Product::with(['category', 'brand', 'variants', 'images'])->get();

        $products->each(function ($product) {

            // Imagen principal
            $product->imagen = $product->imagen
                ? '/' . ltrim(Storage::url($product->imagen), '/')
                : null;

            // Imágenes múltiples
            $product->images->transform(function ($image) {
                $image->imagen = '/' . ltrim(Storage::url($image->imagen), '/');
                return $image;
            });
        });

        return response()->json($products);
    }

    // Crear un producto con múltiples imágenes
    public function store(Request $request)
    {
        $validated = $request->validate([
            'idCategory' => 'required|exists:categories,idCategory',
            'idBrand' => 'required|exists:brands,idBrand',
            'nombre' => 'required|string|max:255',
            'precio' => 'required|numeric',
            'descripcion' => 'nullable|string',
            'modelo' => 'nullable|string',
            'imagenes.*' => 'nullable|file|mimes:jpg,jpeg,png|max:2048'
        ]);

        $product = Product::create($validated);

        // Guardar imágenes
        if ($request->hasFile('imagenes')) {
            foreach ($request->file('imagenes') as $file) {
                $path = $file->store('products', 'public');
                ProductImage::create([
                    'idProduct' => $product->idProduct,
                    'imagen' => $path
                ]);
            }
        }

        // Convertir URLs
        $product->load('images');
        $product->images->transform(function ($image) {
            $image->imagen = '/' . ltrim(Storage::url($image->imagen), '/');
            return $image;
        });

        $product->imagen = $product->imagen
            ? '/' . ltrim(Storage::url($product->imagen), '/')
            : null;

        return response()->json($product, 201);
    }

    // Mostrar un producto específico
    public function show($id)
    {
        $product = Product::with(['category', 'brand', 'variants', 'images'])->findOrFail($id);

        // Convertir rutas
        $product->imagen = $product->imagen
            ? '/' . ltrim(Storage::url($product->imagen), '/')
            : null;

        $product->images->transform(function ($image) {
            $image->imagen = '/' . ltrim(Storage::url($image->imagen), '/');
            return $image;
        });

        return response()->json($product);
    }

    // Actualizar producto
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $validated = $request->validate([
            'idCategory' => 'sometimes|exists:categories,idCategory',
            'idBrand' => 'sometimes|exists:brands,idBrand',
            'nombre' => 'sometimes|string|max:255',
            'precio' => 'sometimes|numeric',
            'descripcion' => 'nullable|string',
            'modelo' => 'nullable|string',
            'imagenes.*' => 'nullable|file|mimes:jpg,jpeg,png|max:2048'
        ]);

        $product->update($validated);

        if ($request->hasFile('imagenes')) {
            foreach ($request->file('imagenes') as $file) {
                $path = $file->store('products', 'public');
                ProductImage::create([
                    'idProduct' => $product->idProduct,
                    'imagen' => $path
                ]);
            }
        }

        // Convertir rutas
        $product->load('images');

        $product->imagen = $product->imagen
            ? '/' . ltrim(Storage::url($product->imagen), '/')
            : null;

        $product->images->transform(function ($image) {
            $image->imagen = '/' . ltrim(Storage::url($image->imagen), '/');
            return $image;
        });

        return response()->json($product);
    }

    // Eliminar producto
    public function destroy($id)
    {
        $product = Product::with('images')->findOrFail($id);

        foreach ($product->images as $image) {
            Storage::disk('public')->delete($image->imagen);
            $image->delete();
        }

        $product->delete();

        return response()->json(['message' => 'Producto eliminado correctamente.']);
    }
}
