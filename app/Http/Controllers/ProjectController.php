<?php

namespace App\Http\Controllers;

use App\Projects;


class ProjectController
{
    public function index() {
        return view('projects', ['projects' => Projects::all()]);
    }
    
    public function store() {
        Projects::create(request(['title', 'description']));
        
        return redirect('/projects');
    }
}