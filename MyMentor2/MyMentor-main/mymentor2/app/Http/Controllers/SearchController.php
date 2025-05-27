<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->input('q');
        $results = Post::where('titre', 'like', "%{$q}%")->get();
        return view('search.results', compact('results', 'q'));
    }
}
