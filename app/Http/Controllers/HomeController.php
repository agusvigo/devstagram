<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    private $posts = 'no posts';

    public function __invoke()
    {
        // Obtener a quienes seguimos si estamos autenticados
        if (auth()->user())
        {
            $ids = auth()->user()->followings->pluck('id')->toArray();
            $this->posts = Post::WhereIn('user_id', $ids)->latest()->paginate(20);
        }

        // Obtener todos los posts
        $postsAll = Post::latest()->paginate(20);

        return view('home', [
            'posts' => $this->posts,
            'postsAll' => $postsAll,
        ]);
    }
}
