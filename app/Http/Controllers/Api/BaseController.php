<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;

class BaseController extends Controller
{

    protected $Service;

    public function __construct($Service)
    {
        $this->Service = $Service;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (!empty($request->sort) && !empty($request->order)) {
            $sort = $request->sort;
            $order = $request->order;
        } else if (empty($request->sort) && empty($request->order)) {
            $sort = 'id';
            $order = 'desc';
        } else {
            $sort = 'id';
            $order = 'desc';
        }
        $data = $this->Service->getModel()->orderBy($sort, $order);
        if (count($this->searchAble)) {
            foreach ($this->searchAble as $attribute) {
                if (isset($request[$attribute])) {
                    $data = $data->where($attribute, $request[$attribute]);
                }
            }
        }
        if ($request->page) {
            $data = $data->paginate();
        } else {
            $data = $data->get();
        }
        return $this->sendResponse($data, $this->labelplural . ' retrieved successfully');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $this->Service->store($request);
        return $this->sendResponse($data, $this->labelsingle . ' created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = $this->Service->find($id);
        return $this->sendResponse($data, $this->labelsingle . ' retrieved successfully');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = $this->Service->find($id);
        return $this->sendResponse($data, $this->labelsingle . ' retrieved successfully');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = $this->Service->update($request, $id);
        return $this->sendResponse($data, $this->labelsingle . ' updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->Service->destroy($id);
        return $this->sendResponse([], $this->labelsingle . ' deleted successfully');
    }

    public function sendResponse($result, $message)
    {
        return Response::json([
            'data' => $result,
            'message' => $message,
            'status' => true,
        ]);
    }

    public function sendError($error, $code = 404)
    {
        return Response::json($error, $code);
    }

    public function sendInfo($message, $status = true)
    {
        return Response::json([
            'status' => $status,
            'message' => $message
        ], 200);
    }
}
