<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Banners;
use App\Models\Faqs;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BannerController extends Controller
{
    public function FetchBanners(Request $request)
    {

        $Banners = Banners::all();
        foreach ($Banners as $key => $Banner) {
            if (!Str::contains($Banner->image_path, '/admin')) {
                $Banner->update(['image_path' => asset('/admin/assets/Banners') .'/'. $Banner->image_path]);
            }
        }
        $data = [
            'Banners' => $Banners,
        ];

        return response()->json([
            'data' => [$data],
            'message' => 'Banners\'s Fetched Successfully',
            'status' => true
        ], 200);
    }
}
