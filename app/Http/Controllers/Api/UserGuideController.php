<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\UserGuide;
use Illuminate\Http\Request;

class UserGuideController extends Controller
{
    public function FetchUserGuide()
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


        $UserGuide = UserGuide::select('user_guide')->first();
        $text = strip_tags(htmlspecialchars_decode($UserGuide->user_guide));
        $text = html_entity_decode(htmlspecialchars_decode($text));
        $specialChars = ["\r", "\n", "&nbsp;", "&ldquo;", "&rdquo;"];
        $text = str_replace($specialChars, '', $text);
        return response()->json([
            'data' => [$text],
            'message' => 'User Guide Fetched Successfully',
            'status' => true
        ], 200);
    }
}
