<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Event;
use App\Models\Label;
use App\Models\WahanaPhoto;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class LandingPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_landing_routes_are_available(): void
    {
        $this->get('/')->assertInertia(fn (Assert $page) => $page->component('Home'));
        $this->get('/tentang-kami')->assertInertia(fn (Assert $page) => $page->component('TentangKami'));
        $this->get('/wahana')->assertInertia(fn (Assert $page) => $page->component('Wahana'));
        $this->get('/galeri-event')->assertInertia(fn (Assert $page) => $page->component('GaleriEvent'));
    }

    public function test_wahana_page_exposes_labels_and_photos_for_client_side_and_filtering(): void
    {
        $category = Category::create(['name' => 'Wahana', 'slug' => 'wahana']);
        $air = Label::create(['category_id' => $category->id, 'name' => 'Air', 'slug' => 'air']);
        $anak = Label::create(['category_id' => $category->id, 'name' => 'Anak-anak', 'slug' => 'anak-anak']);
        $photo = WahanaPhoto::create(['title' => 'Contoh data pengujian']);
        $photo->labels()->attach([$air->id, $anak->id]);

        $this->get('/wahana')->assertInertia(fn (Assert $page) => $page
            ->component('Wahana')
            ->has('categories.0.labels', 2)
            ->has('photos', 1)
            ->where('photos.0.labels.0.slug', 'air'));
    }

    public function test_events_are_exposed_with_their_photos_for_sorting_in_the_gallery(): void
    {
        Event::create(['title' => 'Contoh data pengujian', 'event_date' => now()->toDateString()]);

        $this->get('/galeri-event')->assertInertia(fn (Assert $page) => $page
            ->component('GaleriEvent')
            ->has('events', 1));
    }
}
