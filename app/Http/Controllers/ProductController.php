<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Http\Requests\CreateProductRequest;

class ProductController extends Controller
{
    public function showall()
    {
        $products = Product::all();

        return view('products.showall', ['products' => $products]);
    }

    public function createForm()
    {
        $categories = Category::all();

        if (count($categories) == 0 || is_null($categories)) return redirect()->back()->withErrors('Para crear un artículo primero debe hacer creado al menos una categoría.');

        return view('products.createForm', ['categories' => $categories]);
    }

    public function create(CreateProductRequest $request)
    {
        Product::create($request->validated());

        return redirect()->back()->withSuccess('El producto se ha creado correctamente.');
    }

    public function editForm(Product $product)
    {
        $categories = Category::all();

        return view('products.editForm', ['categories' => $categories, 'product' => $product]);
    }

    public function edit(CreateProductRequest $request, Product $product)
    {
        $update = Product::find($product->id);

        $update->update($request->validated());

        return redirect()->back()->withSuccess('El producto se ha actulizado correctamente.');
    }

    public function delete(Product $product)
    {
        $delete = Product::find($product->id);

        $delete->delete();

        return redirect()->back()->withSuccess('El producto se ha eliminado correctamente');
    }
}
