<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Film;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class FilmCrudTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test guest cannot access admin dashboard.
     */
    public function test_guest_cannot_access_admin_dashboard(): void
    {
        $response = $this->get('/admin');

        $response->assertRedirect('/login');
    }

    /**
     * Test admin authentication.
     */
    public function test_admin_can_authenticate_and_access_dashboard(): void
    {
        $admin = User::create([
            'name' => 'Admin Test',
            'username' => 'admin',
            'password' => bcrypt('admin123'),
        ]);

        $response = $this->post('/login', [
            'username' => 'admin',
            'password' => 'admin123',
        ]);

        $response->assertRedirect('/admin');
        $this->assertAuthenticatedAs($admin);
    }

    /**
     * Test admin can create a film.
     */
    public function test_admin_can_create_film(): void
    {
        $admin = User::create([
            'name' => 'Admin Test',
            'username' => 'admin',
            'password' => bcrypt('admin123'),
        ]);

        Storage::fake('public');

        $response = $this->actingAs($admin)->post('/admin/store', [
            'judul' => 'Inception',
            'genre' => 'Sci-Fi',
            'sutradara' => 'Christopher Nolan',
            'tahun_rilis' => 2010,
            'gambar' => UploadedFile::fake()->image('poster.jpg'),
        ]);

        $response->assertRedirect('/admin');
        $this->assertDatabaseHas('films', [
            'judul' => 'Inception',
            'genre' => 'Sci-Fi',
        ]);

        // Assert file exists in fake storage
        $film = Film::first();
        $this->assertTrue(Storage::disk('public')->exists($film->gambar));
    }

    /**
     * Test admin can export PDF.
     */
    public function test_admin_can_export_pdf(): void
    {
        $admin = User::create([
            'name' => 'Admin Test',
            'username' => 'admin',
            'password' => bcrypt('admin123'),
        ]);

        Film::create([
            'judul' => 'Inception',
            'genre' => 'Sci-Fi',
            'sutradara' => 'Christopher Nolan',
            'tahun_rilis' => 2010,
            'gambar' => 'films/test.jpg',
        ]);

        $response = $this->actingAs($admin)->get('/admin/export-pdf');

        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/pdf');
    }

    /**
     * Test admin can export PDF with pagination, search, and sorting query parameters.
     */
    public function test_admin_can_export_pdf_with_pagination_and_search(): void
    {
        $admin = User::create([
            'name' => 'Admin Test',
            'username' => 'admin',
            'password' => bcrypt('admin123'),
        ]);

        Film::create([
            'judul' => 'Inception',
            'genre' => 'Sci-Fi',
            'sutradara' => 'Christopher Nolan',
            'tahun_rilis' => 2010,
            'gambar' => 'films/test.jpg',
        ]);

        Film::create([
            'judul' => 'Interstellar',
            'genre' => 'Sci-Fi',
            'sutradara' => 'Christopher Nolan',
            'tahun_rilis' => 2014,
            'gambar' => 'films/test2.jpg',
        ]);

        Film::create([
            'judul' => 'The Dark Knight',
            'genre' => 'Action',
            'sutradara' => 'Christopher Nolan',
            'tahun_rilis' => 2008,
            'gambar' => 'films/test3.jpg',
        ]);

        $response = $this->actingAs($admin)->get('/admin/export-pdf?limit=2&page=1&search=Sci-Fi&order_col=2&order_dir=asc');

        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/pdf');
    }
}
