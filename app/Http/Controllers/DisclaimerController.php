<?php

namespace App\Http\Controllers;

use App\Models\Disclaimer;
use Illuminate\Http\Request;

class DisclaimerController extends Controller
{
    public function index()
    {
        return view('admin.disclaimer.index', [
            'disclaimer' => Disclaimer::first()
        ]);
    }
    public function storeAndUpdate(Request $request)
    {

        $request->validate([
            'disclaimer' => 'required',
        ]);

        $disclaimer  = Disclaimer::first();
        $collection = $request->except(['_token']);
        $disclaimer->update($collection);

        $disclaimer->save();

        return redirect()->back()->with([
            'disclaimer' => Disclaimer::first()
        ]);
    }
}
