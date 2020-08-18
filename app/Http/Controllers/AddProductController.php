<?php

namespace App\Http\Controllers;

use App\AddProduct;
use DOMDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Sunra\PhpSimple\HtmlDomParser;


class AddProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function index()
    {
        return view('add_product.add');
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
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $values = [
            'user_id' => Auth::id(),
            'Name' => $request->get('Name'),
            'ImageLink' => $request->get('ImageLink'),
            'Price' => $request->get('Price')
        ];

        $product = new AddProduct($values);
        $product->save();


        return redirect()->route('listProducts', [
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\AddProduct $addProduct
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function show(AddProduct $addProduct)
    {

        $content = Storage::get('link1.html');
        libxml_clear_errors();

        $doc = new DOMDocument();
        libxml_use_internal_errors(true);
        $doc->loadHTML($content);
        $doms = $doc->getElementsByTagName('title');

        foreach ($doms as $dom) {
            dd($dom->nodeValue);
            die();
        }

        $products = DB::table('add_products')->paginate(5);
        return view('add_product.list', [
            'products' => $products,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\AddProduct $addProduct
     * @return \Illuminate\Http\Response
     */
    public function edit(AddProduct $addProduct)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\AddProduct $addProduct
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AddProduct $addProduct)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @param \App\AddProduct $addProduct
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        AddProduct::find($id)->delete();
        return redirect()->route('listProducts', [
        ]);
    }
}
