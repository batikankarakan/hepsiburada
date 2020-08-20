<?php

namespace App\Http\Controllers;

use App\AddProduct;
use DOMDocument;
use http\Exception\RuntimeException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use mysql_xdevapi\Exception;
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
        $productDetails = $this->fetchProductFromLink($request->get('link'));

        $values = [
            'user_id' => Auth::id(),
            'Name' => $productDetails['productName'],
            'ImageLink' => $productDetails['productImage'],
            'Price' => $productDetails['productPrice']
        ];

        $product = new AddProduct($values);
        $product->save();


        return redirect()->route('listProducts');
    }

    /**
     * Display the specified resource.
     *
     * @param \App\AddProduct $addProduct
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function show(AddProduct $addProduct)
    {
        $products = DB::table('add_products')->paginate(5);
        return view('add_product.list', [
            'products' => $products,
        ]);
    }

    private function fetchProductFromLink(string $link)
    {
        $content = file_get_contents($link);

        $doc = new DOMDocument();
        libxml_use_internal_errors(true);
        $doc->loadHTML($content);
        $finder = new \DOMXPath($doc);

        $productNameNode = $finder->query("//*[contains(@class, 'product-name')]")->item(0);
        if (!$productNameNode) {
            throw new RuntimeException("Product name cannot be found.");
        }

        $productName = $productNameNode->nodeValue;
        if (!$productName) {
            throw new RuntimeException('Product name is empty.');
        }

        $productPriceNode = $finder->query("//*[contains(@id, 'offering-price')]")->item(0);
        if (!$productPriceNode) {
            throw new RuntimeException('Product price cannot be found.');
        }

        $productPrice = (float)$productPriceNode->getAttribute('content');
        if (!$productPrice) {
            throw new RuntimeException('Product price node does not have a content attribute.');
        }

        $productImageNode = $finder->query("//*[contains(@class, 'product-image')]")->item(2);
        if (!$productImageNode) {
            throw new RuntimeException('Product image link cannot be found.');
        }

        $productImage = $productImageNode->getAttribute('src');
        if (!$productImage) {
            throw new RuntimeException('Product image link does not have a src attribute.');
        }

        return [
            'productName' => $productName,
            'productPrice' => $productPrice,
            'productImage' => $productImage,
        ];
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
