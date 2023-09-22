<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;


class ProductController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->get('search');
        $perPage = 3;

        if(!empty($keyword)){
            $products = Product::Where('name', 'LIKE', "%$keyword%")
                        ->orWhere('category', 'LIKE', "%$keyword%")
                        ->latest()->paginate($perPage);
        }else{
            $products = Product::latest()->paginate($perPage);
        }

        return view('products.index' ,['products' => $products])->with('1',(request()->input('page',1) -1) * $perPage);
    }

    public function create(){
         
        return view('products.create');
    }

    public function store(Request $request){

        $request->validate([
            'name'=> 'required',
            'description'=> 'required',
            'category'=> 'required',
            'qty'=> 'required',
            'price'=> 'required',
            'image'=> 'required|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
        ]);
        // $data = $request->except('_token');

        $product = new Product;

        $file_name = time().'.'.request()->image->getClientOriginalExtension();
        request()->image->move(public_path('images'),$file_name);

        $product->name = $request->name;
        $product->description = $request->description;
        $product->category = $request->category;
        $product->qty = $request->qty;
        $product->price = $request->price;
        $product->image = $file_name;
        $product->save();

        return redirect()->route('products.index')->with('success','Product has been added');

    }
    public function edit($id){

        $product = Product::findOrFail($id);
        return view('products.edit',['product'=>$product]);

    }
    public function update(Request $request, Product $product){
        $request->validate([
            'name'=> 'required',
            'description'=> 'required',
            'category'=> 'required',
            'qty'=> 'required',
            'price'=> 'required',
        ]);
        
        $file_name = $request->hidden_product_image;

        if($request->image != ''){
            $file_name = time().'.'.request()->image->getClientOriginalExtension();
            request()->image->move(public_path('images'),$file_name);
        }

        $product = Product::find($request->hidden_id);

        $product->name = $request->name;
        $product->description = $request->description;
        $product->category = $request->category;
        $product->qty = $request->qty;
        $product->price = $request->price;
        $product->image = $file_name;
        $product->save();

        return redirect()->route('products.index')->with('success','Product has been updated');

    }
    public function destroy ($id){

        $product = Product::findOrFail($id);
        $image_path = public_path()."/images/";
        $image = $image_path. $product->image;
        if(file_exists($image)){
            @unlink($image);
        }
        $product->delete();
        return redirect()->route('products.index')->with('success','Product has been deleted');

    }
}
