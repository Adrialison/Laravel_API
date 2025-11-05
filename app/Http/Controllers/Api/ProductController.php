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

        // Convertir ruta de imagen a URL pública
        $products->each(function ($product) {
            $product->images->transform(function ($image) {
                $image->imagen = Storage::url($image->imagen);
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
                $path = $file->store('products', 'public'); // Se guardan en storage/app/public/products
                ProductImage::create([
                    'idProduct' => $product->idProduct,
                    'imagen' => $path
                ]);
            }
        }

        // Cargar imágenes con URLs públicas
        $product->load('images');
        $product->images->transform(function ($image) {
            $image->imagen = Storage::url($image->imagen);
            return $image;
        });

        return response()->json($product, 201);
    }

    // Mostrar un producto específico con todas sus imágenes
    public function show($id)
    {
        $product = Product::with(['category', 'brand', 'variants', 'images'])->findOrFail($id);

        // Convertir ruta a URL pública
        $product->images->transform(function ($image) {
            $image->imagen = Storage::url($image->imagen);
            return $image;
        });

        return response()->json($product);
    }

    // Actualizar un producto y agregar nuevas imágenes
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

        // Guardar nuevas imágenes si se envían
        if ($request->hasFile('imagenes')) {
            foreach ($request->file('imagenes') as $file) {
                $path = $file->store('products', 'public'); // Se guardan en storage/app/public/products
                ProductImage::create([
                    'idProduct' => $product->idProduct,
                    'imagen' => $path
                ]);
            }
        }

        // Cargar imágenes con URLs públicas
        $product->load('images');
        $product->images->transform(function ($image) {
            $image->imagen = Storage::url($image->imagen);
            return $image;
        });

        return response()->json($product);
    }

    // Eliminar un producto y todas sus imágenes
    public function destroy($id)
    {
        $product = Product::with('images')->findOrFail($id);

        // Eliminar imágenes físicas y registros
        foreach ($product->images as $image) {
            Storage::disk('public')->delete($image->imagen);
            $image->delete();
        }

        $product->delete();

        return response()->json(['message' => 'Producto eliminado correctamente.']);
    }
}
