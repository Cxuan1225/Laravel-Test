<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_be_created()
    {
        $response = $this->post(route('users.create'), [
            'name'         => 'John Doe',
            'email'        => 'johndoe@example.com',
            'phone_number' => '0123456789',
            'password'     => 'Admin123',
            'status'       => 'active',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('users', [
            'name'  => 'John Doe',
            'email' => 'johndoe@example.com',
            'phone_number' => '0123456789',
            'status'       => 'active',
        ]);
        $user = User::where('email', 'johndoe@example.com')->first();
        $this->assertTrue(Hash::check('Admin123', $user->password));
    }

    public function test_user_can_be_updated()
    {
        $user = User::factory()->create(['status' => 'active']);

        $response = $this->put(route('users.update', $user->id), [
            'name'         => 'New Name',
            'email'        => 'newemail@example.com',
            'phone_number' => '1234567890',
            'status'       => 'inactive',
        ]);
        $user->refresh();
        $response->assertRedirect();
        $this->assertSoftDeleted('users', ['id' => $user->id]);
    }

    public function test_user_can_be_restored()
    {
        $user = User::factory()->create([
            'status'      => 'inactive',
            'deleted_at'  => now(),
        ]);
        $response = $this->put(route('users.update', $user->id), [
            'name'         => 'Restored User',
            'email'        => 'restore@example.com',
            'phone_number' => '9876543210',
            'status'       => 'active',
        ]);
        $user->refresh();
        $response->assertRedirect();
        $this->assertDatabaseHas('users', [
            'id'         => $user->id,
            'deleted_at' => null,
        ]);
    }

    public function test_user_can_be_deleted()
    {
        $user = User::factory()->create(['status' => 'active']);
        $response = $this->post(route('users.destroy', $user->id));
        $response->assertRedirect();
        $this->assertSoftDeleted('users', ['id' => $user->id]);
    }

    public function test_bulk_delete_for_user()
    {
        $users = User::factory()->count(5)->create();
        $userIds = $users->pluck('id')->toArray();
        $response = $this->post(route('users.bulkDelete'), ['selected_users' => $userIds]);
        $response->assertRedirect();
        foreach ($userIds as $id) {
            $user = User::withTrashed()->find($id);
            $this->assertNotNull($user->deleted_at, "User ID $id was not soft deleted.");
        }
    }

    public function test_users_export_creates_excel_file()
    {
        Excel::fake();
        $response = $this->get(route('users.export'));
        $response->assertStatus(200);
        Excel::assertDownloaded('users.xlsx');
    }
}
