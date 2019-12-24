<?php

namespace App\Http\Controllers;

use App\Center;
use Illuminate\Http\Request;
use Toastr;

class CenterController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['centers'] = Center::all();

        return view('centers.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('centers.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->all());

        $rules = [
            'name' => 'required',

        ];

        $messages = [
            'name.required' => 'The name is required',
        ];

        $this->validate($request, $rules, $messages);

        $center = new Center;
        $center->name = $request->name;
        $center->code = $request->code;
        $center->save();

        Toastr::success('Center added', 'Success', ["positionClass" => "toast-bottom-right"]);

        return redirect()->route('centers.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Center  $center
     * @return \Illuminate\Http\Response
     */
    public function show(Center $center)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Center  $center
     * @return \Illuminate\Http\Response
     */
    public function edit(Center $center)
    {
        $data['center'] = Center::find($center->id);

        return view('centers.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Center  $center
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Center $center)
    {
        // dd($request->all());

        $rules = [
            'name' => 'required',

        ];

        $messages = [
            'name.required' => 'The name is required',
        ];

        $this->validate($request, $rules, $messages);

        $center = Center::find($center->id);
        $center->name = $request->name;
        $center->code = $request->code;
        $center->save();

        return redirect()->route('centers.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Center  $center
     * @return \Illuminate\Http\Response
     */
    public function destroy(Center $center)
    {
        dd($center);

        $center = Center::find($center->id);
        
        if($center) {
            $center->delete();
        }

        return redirect()->route('centers.index');
    }
}
