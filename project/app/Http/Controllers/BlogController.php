<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index()
    {
        $blogs = Blog::all();
        return view('blog.index', compact('blogs'));
    }

    public function create() 
    {
        return view('blog.create');
    }

    public function store(Request $Request) 
    {
        $request->validate([
            'title' => 'required',
            'slug' => 'required',
            'content' => 'required'
        ]);
        $blog = Blog::create($request->all());
        return redirect->route('blog.index')->with('success', 'Blog Created' )
    }

    public function show($id) 
    {
        $blog = Blog::findOrFail($id);
        return view('blog.show', compact('blog'));
    }

    public function edit($id) 
    {
        $blog = Blog::findOrFail($id);
        return view('blog.edit', compact('blog'));
    }

    public function update(Request $Request, $id) 
    {
        $request->validate([
            'title' => 'required',
            'slug' => 'required',
            'content' => 'required'
        ]);

        $blog = Blog::findOrFail($id);
        $blog->update($request->all());

        return redirect->route('blog.index')->with('success', 'Blog Updated' )
    }

    public function delete($id)
    {
        $blog = Blog::findOrFail($id)->delete();
        return redirect->route('blog.index')->with('success', 'Blog Deleted' )
    }

}
