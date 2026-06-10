<?php

use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use App\Models\Mahasiswa;
use App\Models\TugasAkhir;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use function Pest\Laravel\actingAs;

uses(RefreshDatabase::class);         

beforeEach(function () {
    // Setup Role and Permission
    $this->role = Role::firstOrCreate(['name' => 'operator_data', 'display_name' => 'Operator Data']);
    $this->permission = Permission::firstOrCreate(['name' => 'update_data_tugas_akhir', 'display_name' => 'Update Data TA']);
    
    if (!$this->role->permissions()->where('name', 'update_data_tugas_akhir')->exists()) {
        $this->role->permissions()->attach($this->permission->id);
    }
    
    // Create Operator User
    $this->operatorBox = User::factory()->create();
    $this->operatorBox->roles()->attach($this->role->id);

    // Create Unauthorized User
    $this->unauthorizedUser = User::factory()->create();

    // Create Student
    $this->mahasiswa = Mahasiswa::factory()->create();
});

test('operator data can access index page', function () {
    actingAs($this->operatorBox)
        ->get(route('update-data-ta.index'))
        ->assertStatus(200)
        ->assertSee('Update Data Tugas Akhir');
});

test('unauthorized user cannot access index page', function () {
    actingAs($this->unauthorizedUser)
        ->get(route('update-data-ta.index'))
        ->assertStatus(403);
});

test('index page lists mahasiswa', function () {
    actingAs($this->operatorBox)
        ->get(route('update-data-ta.index'))
        ->assertStatus(200)
        ->assertSee($this->mahasiswa->nama_lengkap)
        ->assertSee($this->mahasiswa->nim);
});

test('operator can search mahasiswa', function () {
    $otherMahasiswa = Mahasiswa::factory()->create(['nama_lengkap' => 'Not In Search']);

    actingAs($this->operatorBox)
        ->get(route('update-data-ta.index', ['search' => $this->mahasiswa->nim]))
        ->assertStatus(200)
        ->assertSee($this->mahasiswa->nama_lengkap)
        ->assertDontSee($otherMahasiswa->nama_lengkap);
});

test('operator can see edit page', function () {
    actingAs($this->operatorBox)
        ->get(route('update-data-ta.edit', $this->mahasiswa->id))
        ->assertStatus(200)
        ->assertSee($this->mahasiswa->nama_lengkap);
});

test('operator can update tugas akhir details without file', function () {
    $newTitle = 'Updated Thesis Title';
    $newAbstract = 'Updated Abstract Content';

    actingAs($this->operatorBox)
        ->put(route('update-data-ta.update', $this->mahasiswa->id), [
            'judul' => $newTitle,
            'abstrak' => $newAbstract,
        ])
        ->assertRedirect(route('update-data-ta.index'))
        ->assertSessionHas('success');

    $this->assertDatabaseHas('tugas_akhir', [
        'mahasiswa_id' => $this->mahasiswa->id,
        'judul' => $newTitle,
        'abstrak' => $newAbstract,
    ]);
});

test('operator can upload tugas akhir file', function () {
    Storage::fake('public');
    $file = UploadedFile::fake()->create('document.pdf', 1000, 'application/pdf');

    actingAs($this->operatorBox)
        ->put(route('update-data-ta.update', $this->mahasiswa->id), [
            'judul' => 'Title with File',
            'file_ta' => $file,
        ])
        ->assertRedirect(route('update-data-ta.index'));

    // Verify file storage
    // Note: The controller stores it in 'dokumen_ta'. The exact filename is hashed.
    // We check if "dokumen_ta/" exists in the path stored in DB.
    
    $ta = TugasAkhir::where('mahasiswa_id', $this->mahasiswa->id)->first();
    $this->assertNotNull($ta);
    
    $seminar = \App\Models\SeminarSidang::where('tugas_akhir_id', $ta->id)->first();
    $this->assertNotNull($seminar);
    $this->assertNotNull($seminar->dokumen_ta);
    
    Storage::disk('public')->assertExists($seminar->dokumen_ta);
});

test('operator can export data', function () {
    actingAs($this->operatorBox)
        ->get(route('update-data-ta.export'))
        ->assertStatus(200)
        ->assertDownload('data_tugas_akhir.xlsx');
});
