<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function index() {
        $products = Product::all();
        return view('products.index', compact('products'));
    }

    public function create() {
        return view('products.create');
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'quantity' => 'required|integer',
            'description' => 'required|max:511',
        ]);

        $product = Product::create($validated);

        return redirect()
                ->route('products.show', [$product]) // vai ['product' => $product]
                ->with('message', "Product created successfully");
    }

    public function show(Product $product) {
        return view('products.show', compact('product'));
    }

    public function destroy(Product $product) {
        $product->delete();
        return redirect()
                ->route('products.index')
                ->with('message', "Product deleted successfully");
    }

    public function edit(Product $product) {
        return view('products.edit', compact('product'));
    }

    public function update(Request $request, Product $product) {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'quantity' => 'required|integer',
            'description' => 'required|max:511',
        ]);

        $product->update($validated);
        return redirect()
                ->route('products.show', [$product])
                ->with('message', "Product updated successfully");
    }

    public function decreaseQuantity(Request $request, Product $product) {
        $request->validate([
            'amount' => 'required|integer|min:1',
        ]);

        if ($product->decreaseQuantity($request->amount)) {
            return redirect()
                    ->route('products.show', [$product])
                    ->with('message', "Product quantity decreased successfully");
        } else {
            return redirect()
                    ->route('products.show', [$product])
                    ->withErrors(['amount' => 'Not enough stock to decrease quantity.']);
        }
    }
}
