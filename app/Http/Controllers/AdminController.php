<?php

namespace App\Http\Controllers;

use App\Http\Requests\EditBrandStoreRequest;
use App\Models\Brand;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Intervention\Image\Laravel\Facades\Image;
use App\Http\Requests\AddBrandStoreRequest;
use App\Models\Category;
use App\Models\Product;

class AdminController extends Controller
{
    public function index()
    {
        return view('admin.index');
    }

    public function brands()
    {
        $brands = Brand::orderBy('id', 'DESC')->paginate(10);
        return view('admin.brands', compact('brands'));
    }
    public function add_brand()
    {
        return view('admin.brand-add');
    }
    public function add_brand_store(AddBrandStoreRequest $request)
    {


        $validatedRequest = $request->validated();

        // dump($validatedRequest);
        // dd("bu8rda");



        $brand = new Brand();
        $brand->name = $validatedRequest['name'];
        $brand->slug = Str::slug($validatedRequest['name']);

        // if ($validatedRequest->hasFile('image')) {
        //     $image = $validatedRequest->file('image');
        //     $file_extension = $image->extension();
        //     $file_name = Carbon::now()->timestamp . '.' . $file_extension;
        //     // $this->GenerateBrandThumbailsImage($image, $file_name);
        //     $brand->image = $file_name;
        // }

        $brand->save();
        return redirect()->route('admin.brands')->with('status', 'Record has been added successfully!');
    }

    // public function GenerateBrandThumbailsImage($image, $imageName)
    // {
    //     $destinationPath = public_path('uploads/brands');
    //     $img = Image::read($image->path());
    //     $img->cover(124, 124, "top");
    //     $img->resize(124, 124, function($constraint){
    //         $constraint->aspectRatio();
    //     })->save($destinationPath.'/'.$imageName);
    // }


public function edit_brand($id)
{
    $brand = Brand::find($id);
    return view('admin.brand-edit',compact('brand'));
}

public function update_brand(request $request)
{
    $request->validate([
        'name' => 'required',
        'slug' => 'required|unique:brands,slug,'.$request->id,
    ]);


    $brand = Brand::find($request->id);
    $brand->name =  $request['name'];
    $brand->slug = Str::slug($request['name']);

    $brand->save();
    return redirect()->route('admin.brands')->with('status','Record has been updated successfully !');
}

public function delete_brand($id)
{
    $brand = Brand::find($id);

    $brand->delete();
    return redirect()->route('admin.brands')->with('status','Record has been deleted successfully !');
}


public function categories()
 {
        $categories = Category::orderBy('id','DESC')->paginate(10);
        return view("admin.categories",compact('categories'));
 }

 public function add_category()
{
    return view("admin.category-add");
}


public function add_category_store(Request $request)
{
    $request->validate([
        'name' => 'required',
        'slug' => 'required|unique:categories,slug',
    ]);

    $category = new Category();
    $category->name = $request->name;
    $category->slug = Str::slug($request->name);
    $category->save();
    return redirect()->route('admin.categories')->with('status','Record has been added successfully !');
}

public function edit_category($id)
{
    $category = Category::find($id);
    return view('admin.category-edit',compact('category'));
}


public function update_category(Request $request)
{
    $request->validate([
        'name' => 'required',
        'slug' => 'required|unique:categories,slug,'.$request->id,

    ]);

    $category = Category::find($request->id);
    $category->name = $request->name;
    $category->slug = $request->slug;
    $category->save();
    return redirect()->route('admin.categories')->with('status','Record has been updated successfully !');
}

public function delete_category($id)
{
    $category = Category::find($id);

    $category->delete();
    return redirect()->route('admin.categories')->with('status','Record has been deleted successfully !');
}

public function products()
{
    $products = Product::orderBy('created_at', 'DESC')->paginate(10);
    return view('admin.products',compact('products'));
}


public function add_product()
{
    $categories = Category::Select('id','name')->orderBy('name')->get();
    $brands = Brand::Select('id','name')->orderBy('name')->get();
    return view("admin.product-add",compact('categories','brands'));
}

public function product_store(Request $request)
{
    $request->validate([
        'name'=>'required',
        'slug'=>'required|unique:products,slug',
        'category_id'=>'required',
        'brand_id'=>'required',            
        'short_description'=>'required',
        'description'=>'required',
        'regular_price'=>'required',
        'sale_price'=>'required',
        'SKU'=>'required',
        'stock_status'=>'required',
        'featured'=>'required',
        'quantity'=>'required',
                
    ]);

    $product = new Product();
    $product->name = $request->name;
    $product->slug = Str::slug($request->name);
    $product->short_description = $request->short_description;
    $product->description = $request->description;
    $product->regular_price = $request->regular_price;
    $product->sale_price = $request->sale_price;
    $product->SKU = $request->SKU;
    $product->stock_status = $request->stock_status;
    $product->featured = $request->featured;
    $product->quantity = $request->quantity;
   
    $product->category_id = $request->category_id;
    $product->brand_id = $request->brand_id;
    $product->save();
    return redirect()->route('admin.products')->with('status','Record has been added successfully !');
}

}
