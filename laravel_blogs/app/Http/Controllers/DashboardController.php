<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Comment;
use App\Models\Category;
use App\Models\Subscriber;

class DashboardController extends Controller
{
    public function index()
    {
        $postsCount = Post::count();

        $commentsCount = Comment::count();

        $categoriesCount = Category::count();

        $followersCount = Subscriber::count();


        return view('components.container', compact(
            'followersCount',
            'commentsCount',
            'postsCount',
            'categoriesCount',
        ));
    }
}
