<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Product::with('category')->get());
    }

    public function search(Request $request)
    {
        $request->validate([
            'sort' => 'nullable|string|in:az,za,termurah,mahal',
        ]);

        $query = $request->input('query');
        $sort = $request->input('sort');

        $builder = Product::query();

        if ($query) {
            $builder->where(function ($qb) use ($query) {
                $qb->where('name', 'LIKE', "%$query%")
                    ->orWhere('description', 'LIKE', "%$query%")
                    ->orWhereHas('category', function ($q) use ($query) {
                        $q->where('name', 'LIKE', "%$query%");
                    });
            });
        }

        // Sorting: az (name asc), za (name desc), termurah (price asc), mahal (price desc)
        switch ($sort) {
            case 'az':
                $builder->orderBy('name', 'asc');
                break;
            case 'za':
                $builder->orderBy('name', 'desc');
                break;
            case 'termurah':
                $builder->orderBy('base_price', 'asc');
                break;
            case 'mahal':
                $builder->orderBy('base_price', 'desc');
                break;
        }

        $products = $builder->with('category')->paginate(10);

        return response()->json($products);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return response()->json(
            Product::with(['category', 'productVariants.variantValues.variantOption'])
                ->findOrFail($id)
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    /**
     * Get variants for a product (with values and option names)
     */
    public function getVariants(string $id)
    {
        $variants = ProductVariant::with(['variantValues.variantOption'])
            ->where('product_id', $id)
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $variants,
        ]);
    }
}
