<?php

namespace App\Http\Controllers;

use App\Models\AboutPage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AboutController extends Controller
{
    public function index()
    {
        $about = AboutPage::first();
        return view('about.index', compact('about'));
    }

    public function create()
    {
        return view('about.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:2048',
        ]);

        $data = $request->only('title', 'content');

        if ($request->hasFile('thumbnail')) {
            $filename = 'about_thumbnail_' . Str::random(10) . '.' . $request->file('thumbnail')->getClientOriginalExtension();
            $request->file('thumbnail')->move(public_path('storage/about'), $filename);
            $data['thumbnail'] = 'storage/about/' . $filename;
        }

        AboutPage::create($data);

        return redirect()->route('about.index')->with('success', 'About page created successfully.');
    }

    public function edit(AboutPage $about)
    {
        return view('about.edit', compact('about'));
    }

    public function update(Request $request, AboutPage $about)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:2048',
        ]);

        $data = $request->only('title', 'content');

        if ($request->hasFile('thumbnail')) {
            if ($about->thumbnail && file_exists(public_path($about->thumbnail))) {
                unlink(public_path($about->thumbnail));
            }

            $filename = 'about_thumbnail_' . Str::random(10) . '.' . $request->file('thumbnail')->getClientOriginalExtension();
            $request->file('thumbnail')->move(public_path('storage/about'), $filename);
            $data['thumbnail'] = 'storage/about/' . $filename;
        }

        $about->update($data);

        return redirect()->route('about.index')->with('success', 'About page updated successfully.');
    }

    public function destroy(AboutPage $about)
    {
        if ($about->thumbnail && file_exists(public_path($about->thumbnail))) {
            unlink(public_path($about->thumbnail));
        }

        $about->delete();

        return redirect()->route('about.index')->with('success', 'About page deleted successfully.');
    }
}

