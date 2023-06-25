<?php

namespace App\Http\Controllers;

use App\Models\AttributeValue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AttributeValueController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $attributeValue = DB::table('attribute_values as c1')
            ->leftJoin('attributes as c2', 'c1.attribute_id', '=', 'c2.id')
            ->select('c1.*', 'c2.name as attribute_name')
            ->get();

        return $attributeValue;
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
            'value' => "required|unique:attribute_values,value|string|max:155",
            'description' => 'nullable|string'
        ]);


        try {
            $data = $request->all();
            $attribute = AttributeValue::create($data);

            return message('Items created successfully', 200, $attribute);
        } catch (\Throwable $th) {
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

    public function attributeValue($id)
    {
        $attributeValue =  DB::table('attribute_values')
            ->where('attribute_id', $id)
            ->get();

        return $attributeValue;
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
        DB::table('attribute_values')
            ->where('id', $id)
            ->delete();

        return message('category archived successfully');
    }
}
