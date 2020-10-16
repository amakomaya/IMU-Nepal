<?php

namespace App\Http\Controllers;

use App\Models\NoticeBoard;
use Illuminate\Http\Request;

class NoticeBoardController extends Controller
{
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
        $rows = NoticeBoard::latest()->get();
        return view('notice-board.index',compact('rows'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('notice-board.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $customMessages = [
            'required' => 'The :attribute field is required.',
            'min' => 'The :attribute must have at least 3 character'
        ];

        $request->validate([
            'type' => 'required',
            'title' => 'required|min:3',
            'description' => 'required',
        ], $customMessages);
        NoticeBoard::create(['title' => $request->title,'description' => $request->description, 'type' => $request->type]);
        $request->session()->flash('message', 'Notice created successfully');
        return redirect()->route('notice-board.index');
    }

    public function show($id)
    {
        $row = NoticeBoard::where('id', $id)->first();
        return view('notice-board.show',compact('row'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $row = NoticeBoard::where('id', $id)->first();
        return view('notice-board.edit',compact('row'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $customMessages = [
            'required' => 'The :attribute field is required.',
            'min' => 'The :attribute must have at least 3 character'
        ];
        $request->validate([
            'type' => 'required',
            'title' => 'required|min:3',
            'description' => 'required',
        ], $customMessages);
        NoticeBoard::where('id', $id)->update(['title' => $request->title,'description' => $request->description, 'type' => $request->type]);
        $request->session()->flash('message', 'Notice updated successfully');
        return redirect()->route('notice-board.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        NoticeBoard::findOrFail($id)->delete();
        $request->session()->flash('message', 'Notice deleted');
        return redirect()->route('notice-board.index');
    }
}
