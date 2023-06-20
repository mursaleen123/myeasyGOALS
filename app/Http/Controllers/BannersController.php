<?php

namespace App\Http\Controllers;

use App\Models\Banners;
use Illuminate\Http\Request;

class BannersController extends Controller
{
    public function index()
    {
        return view('admin.banners.index')->with([
            'banners' => Banners::all(),
        ]);
    }

    public function create()
    {
        return view('admin.banners.create');
    }
    public function store(Request $request)
    {
        $request->validate([
            'banner_image' => 'required|array',
            'banner_image.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048', // Adjust the allowed extensions and size limit as needed
        ]);
        if ($request->hasFile('banner_image')) {
            foreach ($request->file('banner_image') as $image) {
                $file2 = \Carbon\Carbon::now()->subMonth()->format('F') . '_' . basename($image->getClientOriginalName(), '.' . $image->getClientOriginalExtension()) . time() . '.' . $image->extension();
                $path = $image->move(public_path('/admin/assets/Banners/'), $file2);
                $imagePaths[] =  $file2 ;
            }
        }

        foreach ($imagePaths as $path) {
            Banners::create(['image_path' => $path]);
        }

        return redirect()->route('banners.index')->with('success', 'Banners uploaded successfully.');
    }
}
