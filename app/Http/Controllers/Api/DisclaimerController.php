<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Disclaimer;
use Illuminate\Http\Request;

class DisclaimerController extends Controller
{
    public function FetchDisclaimer()
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


        $disclaimer = Disclaimer::select('disclaimer')->first();
        $text = strip_tags(htmlspecialchars_decode($disclaimer->disclaimer));
        $text = html_entity_decode(htmlspecialchars_decode($text));
        $specialChars = ["\r", "\n", "&nbsp;", "&ldquo;", "&rdquo;"];
        $text = str_replace($specialChars, '', $text);
        return response()->json([
            'data' => [$text],
            'message' => 'Disclaimer Fetched Successfully',
            'status' => true
        ], 200);
    }
}
