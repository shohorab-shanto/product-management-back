<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $products = DB::table('products')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->join('product_attributes', 'products.id', '=', 'product_attributes.product_id')
            ->join('attributes', 'product_attributes.attribute_id', '=', 'attributes.id')
            ->select('products.*', 'categories.name as category_name', 'attributes.name as attribute_name', 'product_attributes.value')
            ->get();

        $productData = [];
        foreach ($products as $product) {
            $productId = $product->id;
            if (!isset($productData[$productId])) {
                $productData[$productId] = [
                    'id' => $product->id,
                    'name' => $product->name,
                    'qty' => $product->qty,
                    'status' => $product->status,
                    'description' => $product->description,
                    'category' => [
                        'id' => $product->category_id,
                        'name' => $product->category_name,
                    ],
                    'attributes' => [],
                ];
            }

            $productData[$productId]['attributes'][] = [
                'id' => $product->attribute_id,
                'name' => $product->attribute_name,
                'value' => $product->value,
            ];
        }

        return $productsResult = array_values($productData);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => "required|unique:attributes,name|string|max:155",
            'description' => 'nullable|string'
        ]);

        DB::beginTransaction();
        try {
            $attributes = json_decode($request->attribute);
            // return $attributes;
            $data = $request->only([
                'category_id',
                'name',
                'qty',
                'description',
            ]);
            $product = Product::create($data);
            //store attributes
            foreach ($attributes as $key => $attribute) {
                //Create the part attributes
                $product->productAttribute()->updateOrCreate(collect($attribute)->toArray() + [
                    'product_id' => $product->id,
                    'attribute_id' => $attribute->attribute_id,
                    'value' => $attribute->value,
                ]);
            }
            DB::commit();
            return message('Items created successfully', 200, $product);
        } catch (\Throwable $th) {
            DB::rollback();
            return message($th->getMessage(), 400);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = DB::table('products')
            ->where('id', $id)
            ->first();

        if ($product) {
            $attributes = DB::table('product_attributes')
                ->where('product_id', $id)
                ->get();

            foreach ($attributes as $attribute) {
                DB::table('product_attributes')
                    ->where('id', $attribute->id)
                    ->delete();
            }

            DB::table('products')
                ->where('id', $id)
                ->delete();

            return message('category archived successfully');
        }
    }
}

