<?php

namespace App\Http\Controllers;

use App\Models\Banners;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
                $imagePaths[] =  $file2;
            }
        }

        foreach ($imagePaths as $path) {
            Banners::create(['image_path' => $path]);
        }

        return redirect()->route('banners.index')->with('success', 'Banners uploaded successfully.');
    }
    public function destroy($id)
    {
        DB::table("banners")->where('id', $id)->delete();
        return redirect()->route('banners.index')
            ->with('success', 'Banner deleted successfully');
    }
    public function edit($id)
    {
        $Banners = Banners::find($id)->first();
        // dd(  $Banners->);
        return view('admin.banners.edit')->with([
            'Banners' => $Banners
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'banner_id' => 'required',
            'banner_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        $image = $request->file('banner_image');
        $file2 = \Carbon\Carbon::now()->subMonth()->format('F') . '_' . basename($image->getClientOriginalName(), '.' . $image->getClientOriginalExtension()) . time() . '.' . $image->extension();
        $image->move(public_path('/admin/assets/Banners/'), $file2);



        Banners::findOrFail($request->banner_id)->update([
            'image_path' =>   $file2
        ]);

        return redirect()->route('banners.index')
            ->with('success', 'Image updated successfully');
    }
}
