<?php

namespace Tests\Feature\Admin;

use App\Models\EventPromo;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class EventPromoCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_event_promo_crud(): void
    {
        $this->get('/admin/event-promo')->assertRedirect(route('login'));
        $this->get('/admin/event-promo/create')->assertRedirect(route('login'));
        $this->post('/admin/event-promo', [])->assertRedirect(route('login'));
        $this->get('/admin/event-promo/1/edit')->assertRedirect(route('login'));
        $this->patch('/admin/event-promo/1', [])->assertRedirect(route('login'));
        $this->delete('/admin/event-promo/1')->assertRedirect(route('login'));
    }

    public function test_user_role_cannot_access_event_promo_crud(): void
    {
        $user = $this->userWithRole('user');
        $item = $this->createEventPromo($user, 'event-promo/forbidden.jpg');

        $this->actingAs($user)->get('/admin/event-promo')->assertForbidden();
        $this->actingAs($user)->get('/admin/event-promo/create')->assertForbidden();
        $this->actingAs($user)->post('/admin/event-promo', [])->assertForbidden();
        $this->actingAs($user)->get(route('admin.event-promo.edit', $item))->assertForbidden();
        $this->actingAs($user)->patch(route('admin.event-promo.update', $item), [])->assertForbidden();
        $this->actingAs($user)->delete(route('admin.event-promo.destroy', $item))->assertForbidden();
    }

    public function test_admin_and_super_admin_can_open_event_promo_index(): void
    {
        $this->actingAs($this->userWithRole('admin'))
            ->get('/admin/event-promo')
            ->assertInertia(fn (Assert $page) => $page
                ->component('Admin/EventPromo/Index')
                ->has('items'));

        $this->actingAs($this->userWithRole('super_admin'))
            ->get('/admin/event-promo')
            ->assertInertia(fn (Assert $page) => $page->component('Admin/EventPromo/Index'));
    }

    public function test_admin_can_create_event_promo_with_poster_and_server_side_audit(): void
    {
        Storage::fake('public');
        $admin = $this->userWithRole('admin');

        $this->actingAs($admin)->post('/admin/event-promo', [
            'judul' => 'Promo Pengujian',
            'deskripsi_singkat' => 'Deskripsi singkat promo pengujian.',
            'poster' => $this->fakeImage('poster.png'),
            'link_wa' => null,
            'created_by' => 999999,
            'updated_by' => 999999,
        ])->assertRedirect(route('admin.event-promo.index'));

        $item = EventPromo::query()->sole();
        $this->assertSame($admin->id, $item->created_by);
        $this->assertNull($item->updated_by);
        $this->assertNull($item->link_wa);
        $this->assertStringStartsWith('event-promo/', $item->poster);
        Storage::disk('public')->assertExists($item->poster);
    }

    public function test_create_validation_rejects_invalid_fields_and_poster(): void
    {
        Storage::fake('public');

        $this->actingAs($this->userWithRole('admin'))
            ->post('/admin/event-promo', [
                'judul' => str_repeat('a', 151),
                'deskripsi_singkat' => str_repeat('b', 256),
                'poster' => UploadedFile::fake()->create('document.pdf', 20, 'application/pdf'),
                'link_wa' => str_repeat('c', 256),
            ])
            ->assertSessionHasErrors(['judul', 'deskripsi_singkat', 'poster', 'link_wa']);

        $this->assertDatabaseCount('event_promo', 0);
    }

    public function test_admin_can_update_without_replacing_poster_and_sets_updated_by(): void
    {
        Storage::fake('public');
        $creator = $this->userWithRole('super_admin');
        $admin = $this->userWithRole('admin');
        $item = $this->createEventPromo($creator, 'event-promo/original.jpg');
        Storage::disk('public')->put($item->poster, 'original');

        $this->actingAs($admin)->patch(route('admin.event-promo.update', $item), [
            'judul' => 'Judul Diperbarui',
            'deskripsi_singkat' => 'Deskripsi singkat diperbarui.',
            'link_wa' => 'https://wa.me/628123456789',
        ])->assertRedirect(route('admin.event-promo.index'));

        $item->refresh();
        $this->assertSame('Judul Diperbarui', $item->judul);
        $this->assertSame($creator->id, $item->created_by);
        $this->assertSame($admin->id, $item->updated_by);
        $this->assertSame('event-promo/original.jpg', $item->poster);
        Storage::disk('public')->assertExists($item->poster);
    }

    public function test_replacing_poster_removes_only_the_unused_old_file(): void
    {
        Storage::fake('public');
        $admin = $this->userWithRole('admin');
        $item = $this->createEventPromo($admin, 'event-promo/old.jpg');
        Storage::disk('public')->put($item->poster, 'old');

        $this->actingAs($admin)->post(route('admin.event-promo.update', $item), [
            '_method' => 'patch',
            'judul' => $item->judul,
            'deskripsi_singkat' => $item->deskripsi_singkat,
            'poster' => $this->fakeImage('replacement.png'),
            'link_wa' => '',
        ])->assertRedirect(route('admin.event-promo.index'));

        $newPath = $item->fresh()->poster;
        $this->assertNotSame('event-promo/old.jpg', $newPath);
        Storage::disk('public')->assertExists($newPath);
        Storage::disk('public')->assertMissing('event-promo/old.jpg');
    }

    public function test_delete_removes_record_and_unused_poster(): void
    {
        Storage::fake('public');
        $admin = $this->userWithRole('admin');
        $item = $this->createEventPromo($admin, 'event-promo/delete-me.jpg');
        Storage::disk('public')->put($item->poster, 'poster');

        $this->actingAs($admin)
            ->delete(route('admin.event-promo.destroy', $item))
            ->assertRedirect(route('admin.event-promo.index'));

        $this->assertDatabaseMissing('event_promo', ['id' => $item->id]);
        Storage::disk('public')->assertMissing('event-promo/delete-me.jpg');
    }

    public function test_delete_preserves_poster_still_used_by_another_record(): void
    {
        Storage::fake('public');
        $admin = $this->userWithRole('admin');
        $first = $this->createEventPromo($admin, 'event-promo/shared.jpg');
        $this->createEventPromo($admin, 'event-promo/shared.jpg', 'Promo Kedua');
        Storage::disk('public')->put('event-promo/shared.jpg', 'shared');

        $this->actingAs($admin)->delete(route('admin.event-promo.destroy', $first));

        Storage::disk('public')->assertExists('event-promo/shared.jpg');
    }

    public function test_home_exposes_cms_promotions_with_one_public_poster_url(): void
    {
        $creator = $this->userWithRole('admin');
        $this->createEventPromo(
            $creator,
            'event-promo/promo.jpg',
            'Promo dari CMS',
            'https://wa.me/628123456789',
        );

        $this->get('/')->assertInertia(fn (Assert $page) => $page
            ->component('Home')
            ->has('promotions', 1)
            ->where('promotions.0.title', 'Promo dari CMS')
            ->where('promotions.0.description', 'Deskripsi singkat promo pengujian.')
            ->where('promotions.0.poster_url', url('/storage/event-promo/promo.jpg'))
            ->where('promotions.0.link_wa', 'https://wa.me/628123456789')
            ->missing('promotions.0.poster')
            ->missing('promotions.0.image'));
    }

    public function test_home_returns_empty_promotions_for_the_frontend_fallback(): void
    {
        $this->get('/')->assertInertia(fn (Assert $page) => $page
            ->component('Home')
            ->has('promotions', 0));
    }

    private function userWithRole(string $roleName): User
    {
        $role = Role::firstOrCreate(['nama_role' => $roleName]);

        return User::factory()->create(['role_id' => $role->id]);
    }

    private function createEventPromo(
        User $creator,
        string $poster,
        string $title = 'Promo Pengujian',
        ?string $linkWa = null,
    ): EventPromo {
        return EventPromo::create([
            'created_by' => $creator->id,
            'updated_by' => null,
            'judul' => $title,
            'deskripsi_singkat' => 'Deskripsi singkat promo pengujian.',
            'poster' => $poster,
            'link_wa' => $linkWa,
        ]);
    }

    private function fakeImage(string $name): UploadedFile
    {
        $onePixelPng = base64_decode(
            'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNk+A8AAQUBAScY42YAAAAASUVORK5CYII=',
            true,
        );

        return UploadedFile::fake()->createWithContent($name, $onePixelPng);
    }
}
