<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Event;
use App\Models\News;
use App\Models\Partner;
use App\Models\Promotion;
use App\Models\WahanaPhoto;
use Inertia\Inertia;
use Inertia\Response;

class PublicPageController extends Controller
{
    public function home(): Response
    {
        return Inertia::render('Home', [
            'news' => News::query()->latest('published_at')->take(4)->get(),
            'promotions' => Promotion::query()->latest('start_date')->take(3)->get(),
            'partners' => Partner::query()->orderBy('name')->get(),
            'featuredRides' => WahanaPhoto::query()
                ->with('labels:id,name,slug')
                ->where('is_featured', true)
                ->take(3)
                ->get(),
        ]);
    }

    public function about(): Response
    {
        return Inertia::render('TentangKami');
    }

    public function rides(): Response
    {
        return Inertia::render('Wahana', [
            'categories' => Category::query()->with('labels:id,category_id,name,slug')->orderBy('name')->get(),
            'photos' => WahanaPhoto::query()->with('labels:id,name,slug')->latest()->get(),
        ]);
    }

    public function events(): Response
    {
        return Inertia::render('GaleriEvent', [
            'events' => Event::query()->with('photos:id,event_id,photo_path,alt_text')->latest('event_date')->get(),
        ]);
    }
}
