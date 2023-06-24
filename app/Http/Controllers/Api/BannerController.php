<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Banners;
use App\Models\Faqs;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    public function FetchBanners(Request $request)
    {
        // dd(auth()->user());
        // $this->middleware('auth:api');
        // $user = $request->user(); // Get the authenticated user

        // if ($user) {
        //     dd(12);
        //     // User is logged in
        //     // You can perform actions for the logged-in user
        // } else {
        //     dd('not');
        //     // User is not logged in
        //     // You can handle the case where the user is not authenticated
        // }


        $Banners = Banners::all();
        foreach ($Banners as $key => $Banner) {
            $Banner->update(['image_path' => public_path('/admin/assets/Banners/').$Banner->image_path]);
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
