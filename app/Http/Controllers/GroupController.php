<?php

namespace App\Http\Controllers;

use App\Group;
use App\Http\Resources\GroupResource;
use App\Http\Resources\GroupsCollection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDO;

class GroupController extends Controller
{
    private $pdo;
    
    public function __construct() {
        $this->pdo = new PDO('mysql:dbname=laravel;host=localhost', 'root', '', [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
        
    }
    
    public function __destruct() {
        unset($pdo);
    }
    
    /**GET "a list of records"
     * Display a listing of the resource.
     *
     * return \Illuminate\Http\Response
     */
    public function index() {
        /*$res = DB::table('groups')
                 ->join('people', 'groups.id', '=', 'people.group_member_id')
                 ->select('groups.group_name', 'people.first_name', 'people.last_name')
                 ->get();*/
        $q = <<<sql
select g.id as group_id, g.group_name, p.full_name
from people p right join `groups` g on p.group_member_id = g.id;
sql;
        
        $r = $this->pdo->prepare($q);
        $r->execute();
        $r = $r->fetchAll();
        
        $groupBy = [];
        $group = 'group_name';
        $full = 'full_name';
        foreach($r as $i => $val) {
            $key = $val[$group];
            
            if(isset($groupBy[$key])) {
                $groupBy[$key]['members'] []= $val[$full];
            }
            else {
                $groupBy[$key][$group] = $key;
                //$groupBy[$key]['members'] = $val[$full] . ', ';
                $groupBy[$key]['members'] = [$val[$full]];
            }
        }
        
        //dd($groupBy);
        
        return new GroupsCollection(array_values($groupBy));
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
        //log('__> $request->all() = ' . var_export($request->all(), true));
        // using same technique as PeopleController.php
        $request->validate(['group_name' => 'required|max:255']);
        $group = Group::create($request->all());
        return (new GroupResource($group))->response()->setStatusCode(201);
    }
    
    /**GET "a specific record"
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        return new GroupResource(Group::findOrFail($id));
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
        $group = Group::findOrFail($id);
        $group->update($request->all());
        return response()->json(null, 204);
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        $group = Group::findOrFail($id);
        $group->delete();
        return response()->json(null, 204);
    }
}
