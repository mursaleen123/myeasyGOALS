<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Faqs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FaqsController extends Controller
{
    public function index()
    {
        $faqs = Faqs::orderBy('id', 'DESC')->get();
        $count = 1;
        return view('admin.faq.index', compact('faqs', 'count'));
    }
    public function getFaqForm()
    {
        return view('admin.faq.create');
    }
    public function storeFaq(Request $request)
    {

        // Validate the request data
        $request->validate([
            'question' => 'required',
            'answer' => 'required',
        ]);

        $collection = $request->except(['_token']);

        // Create a new Faq instance with the validated data
        Faqs::create($collection);

        // Redirect back with success message
        return redirect()->route('get.faq.index')->with('success', 'Faq created successfully');
    }
    public function destroy($id)
    {
        DB::table("faqs")->where('id', $id)->delete();
        return redirect()->route('get.faq.index')
            ->with('success', 'Faq deleted successfully');
    }
    public function edit($id)
    {
        $faq = Faqs::find($id);

        return view('admin.faq.edit', compact('faq'));
    }
    public function update(Request $request)
    {
        $validatedData = $request->validate([
            'question' => 'required',
            'answer' => 'required',
        ]);

        $collection = $request->except(['_token']);
        $collection['is_active'] = $request->has('is_active') ? 'true' : 'false';
        Faqs::findOrFail($request->id)->update($collection);

        return redirect()->route('get.faq.index')
            ->with('success', 'Faq updated successfully');
    }
}
