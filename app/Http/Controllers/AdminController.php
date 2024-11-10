<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Intervention\Image\Laravel\Facades\Image;
use App\Http\Requests\AddBrandStoreRequest;



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
        $brand->name =$validatedRequest['name'];
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
}
