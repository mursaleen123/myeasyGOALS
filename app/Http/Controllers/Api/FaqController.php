<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Faqs;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    public function FetchFaqs(Request $request)
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


        $faqs = Faqs::where('is_active','true')->orderBy('id', 'DESC')->get();
        $data = [
            'faqs' => $faqs,
        ];

        return response()->json([
            'data' => [$data],
            'message' => 'Faq\'s Fetched Successfully',
            'status' => true
        ], 200);
    }
}
