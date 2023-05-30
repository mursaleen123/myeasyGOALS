<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\UserGuide;
use Illuminate\Http\Request;

class UserGuideController extends Controller
{
    public function index()
    {
        return view('admin.userguide.index', [
            'guide' => UserGuide::first()
        ]);
    }
    public function storeAndUpdate(Request $request)
    {

        $request->validate([
            'user_guide' => 'required',
        ]);

        $user_guide  = UserGuide::first();
        $collection = $request->except(['_token']);
        $user_guide->update($collection);

        $user_guide->save();

        return redirect()->back()->with([
            'guide' => UserGuide::first()
        ]);
    }
}
