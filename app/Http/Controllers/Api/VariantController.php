<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Variant;
use App\Models\Product;

class VariantController extends Controller
{
    public function index()
    {
        // Incluye relación con el producto
        return Variant::with('product')->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'idProduct' => 'required|exists:products,idProduct',
            'color' => 'nullable|string|max:100',
            'capacidad' => 'nullable|string|max:100',
            'stock' => 'required|integer|min:0',
        ]);

        $variant = Variant::create($validated);

        return response()->json($variant, 201);
    }

    public function show($id)
    {
        $variant = Variant::with('product')->findOrFail($id);
        return response()->json($variant);
    }

    public function update(Request $request, $id)
    {
        $variant = Variant::findOrFail($id);

        $validated = $request->validate([
            'idProduct' => 'sometimes|exists:products,idProduct',
            'color' => 'nullable|string|max:100',
            'capacidad' => 'nullable|string|max:100',
            'stock' => 'sometimes|required|integer|min:0',
        ]);

        $variant->update($validated);

        return response()->json($variant);
    }

    public function destroy($id)
    {
        $variant = Variant::findOrFail($id);
        $variant->delete();

        return response()->json(['message' => 'Variante eliminada correctamente.']);
    }
}
