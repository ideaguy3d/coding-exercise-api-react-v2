<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

use App\Http\Resources\PeopleCollection;
use App\Http\Resources\PersonResource;
use App\Models\Person;

class PeopleController extends Controller
{
    /**GET "a list of records"
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        return new PeopleCollection(Person::all());
    }
    
    /** CREATE
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $person = Person::create($request->validate([
            'first_name' => 'required|max:255',
            'last_name' => 'required|max:255',
            'email_address' => 'required|email',
            'status' => Rule::in(['active', 'archived']),
        ]));
        
        return (new PersonResource($person))
            ->response()
            ->setStatusCode(201);
    }
    
    /**GET "a specific record"
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        return new PersonResource(Person::findOrFail($id));
    }
    
    /**UPDATE
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        $person = Person::findOrFail($id);
        $person->update($request->all());
        
        return response()->json(null, 204);
    }
    
    /**DELETE
     * Rem  ove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        $person = Person::findOrFail($id);
        $person->delete();
        
        return response()->json(null, 204);
    }
}
