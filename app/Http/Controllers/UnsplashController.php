<?php

namespace App\Http\Controllers;

use App\Service\Unsplash;
use Illuminate\Http\Request;

class UnsplashController extends Controller
{


    public function index(Request $request, Unsplash $unsplash)
    {
        $title = $request->get('title', 'furniture');
        $color = $request->get('color', null);
        $page = $request->get('page', 1);

        $data = $unsplash->search('search/photos', [
            'query' => $title,
            'color' => $color,
            'page' => $page
        ]);
        $colors = Unsplash::colors;
        return inertia('Unsplash', compact('data', 'title', 'color', 'colors', 'page'));
    }


    public function download(Request $request, Unsplash $unsplash)
    {
        $url = $request->get('url', null);
        $body = $unsplash->download($url);
        return response($body)
            ->header('Cache-Control', 'no-cache private')
            ->header('Content-Description', 'File Transfer')
            ->header('Content-Type', 'application/octet-stream')
            ->header('Content-Disposition', 'attachment; filename=logo.png')
            ->header('Content-Transfer-Encoding', 'binary');
    }

}
