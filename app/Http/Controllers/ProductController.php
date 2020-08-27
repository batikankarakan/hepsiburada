<?php

namespace App\Http\Controllers;

use App\AddProduct;
use App\Product;
use DOMDocument;
use http\Exception\RuntimeException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function index()
    {
        $products = Auth::user()->products()->paginate(10);

        return view('add_product.list', [
            'products' => $products,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function create()
    {
        return view('add_product.add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'link' => 'required|min:20|max:65536|url'
        ]);
        $pos = strpos(strval($request), 'trendyol');
        if ($pos !== false) {
            try {
                $productDetails = $this->fetchTrendyolProductFromLink($request->get('link'));
            } catch (\Throwable $exception) {
                throw ValidationException::withMessages(['link' => 'Product details cannot be fetched.']);
            }

        } else {
            try {
                $productDetails = $this->fetchHepsiBuradaProductFromLink($request->get('link'));
            } catch (\Throwable $exception) {
                throw ValidationException::withMessages(['link' => 'Product details cannot be fetched.']);
            }
        }

        $values = [
            'user_id' => Auth::id(),
            'name' => $productDetails['productName'],
            'image' => $productDetails['productImage'],
            'link' => $request->get('link'),
            'price' => $productDetails['productPrice']
        ];

        $product = new Product($values);
        $product->save();

        return redirect()->route('products.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Product $product
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $users_product = Product::where('user_id', '=', Auth::id())->where('id', '=', $id)->delete();

        return redirect()->route('products.index', [
        ]);
    }

    private function fetchHepsiBuradaProductFromLink(string $link)
    {
        $response = Http::get($link);
        $content = $response->getBody();

        $doc = new DOMDocument();
        libxml_use_internal_errors(true);
        $doc->loadHTML($content);
        $finder = new \DOMXPath($doc);

        $productNameNode = $finder->query("//*[contains(@class, 'product-name')]")->item(0);
        if (!$productNameNode) {
            throw new \RuntimeException("Product name cannot be found.");
        }

        $productName = $productNameNode->nodeValue;
        if (!$productName) {
            throw new \RuntimeException('Product name is empty.');
        }

        $productPriceNode = $finder->query("//*[contains(@id, 'offering-price')]")->item(0);
        if (!$productPriceNode) {
            throw new \RuntimeException('Product price cannot be found.');
        }

        $productPrice = (float)$productPriceNode->getAttribute('content');
        if (!$productPrice) {
            throw new \RuntimeException('Product price node does not have a content attribute.');
        }

        $productImageNode = $finder->query("//*[contains(@class, 'product-image')]")->item(2);
        if (!$productImageNode) {
            throw new \RuntimeException('Product image link cannot be found.');
        }

        $productImage = $productImageNode->getAttribute('src');
        if (!$productImage) {
            throw new \RuntimeException('Product image link does not have a src attribute.');
        }

        return [
            'productName' => $productName,
            'productPrice' => $productPrice,
            'productImage' => $productImage,
        ];
    }

    private function fetchTrendyolProductFromLink(string $link)
    {
        $response = Http::get($link);
        $content = $response->getBody();

        $doc = new DOMDocument();
        libxml_use_internal_errors(true);
        $doc->loadHTML($content);
        $finder = new \DOMXPath($doc);

        $productNameNode = $finder->query("//*[contains(@class, 'pr-in-br')]")->item(0);
        if (!$productNameNode) {
            throw new \RuntimeException("Product name cannot be found.");
        }

        $productName = $productNameNode->nodeValue;
        if (!$productName) {
            throw new \RuntimeException('Product name is empty.');
        }

        $productPriceNode = $finder->query("//*[contains(@class, 'prc-slg')]")->item(0);
        if (!$productPriceNode) {
            throw new \RuntimeException('Product price cannot be found.');
        }

        $productPrice = (float)$productPriceNode->nodeValue;
        if (!$productPrice) {
            throw new \RuntimeException('Product price node does not have a content attribute.');
        }


        $productImageNode = $finder->query("//*[contains(@class, 'ph-gl-img')]")->item(0);
        if (!$productImageNode) {
            throw new \RuntimeException('Product image link cannot be found.');
        }

        $productImage = $productImageNode->getAttribute('src');
        if (!$productImage) {
            throw new \RuntimeException('Product image link does not have a src attribute.');
        }


        return [
            'productName' => $productName,
            'productPrice' => $productPrice,
            'productImage' => $productImage,
        ];
    }
}
