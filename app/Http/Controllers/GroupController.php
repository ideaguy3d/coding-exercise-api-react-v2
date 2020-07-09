<?php

namespace App\Http\Controllers;

use App\Group;
use App\Http\Resources\GroupResource;
use App\Http\Resources\GroupsCollection;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    /**GET "a list of records"
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        return new GroupsCollection(Group::all());
    }
    
    /**CREATE
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        //dd($request->all());
        //Log::info('__> $request->all() = ' . var_export($request->all(), true));
        // using same technique as PeopleController.php
        $request->validate(['group_name' => 'required|max:255']);
        $group = \App\Group::create($request->all());
        return (new GroupResource($group))->response()->setStatusCode(201);
    }
    
    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        //
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        //
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        //
    }
}
