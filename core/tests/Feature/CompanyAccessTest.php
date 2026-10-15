<?php
namespace Tests\Feature;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
class CompanyAccessTest extends TestCase {
    use RefreshDatabase;
    public function test_companies_require_authentication(): void {
        $this->get('/companies')->assertRedirect('/login');
    }
    public function test_authenticated_user_can_open_company_directory(): void {
        $user = User::create(['name'=>'Test Admin','email'=>'admin@example.test','password'=>'a-strong-test-password']);
        $this->actingAs($user)->get('/companies')->assertOk()->assertSee('Companies');
    }
    public function test_authenticated_user_can_create_company(): void {
        $user = User::create(['name'=>'Test Admin','email'=>'admin@example.test','password'=>'a-strong-test-password']);
        $this->actingAs($user)->post('/companies', ['name'=>'Example Company','website'=>'https://example.test','industry'=>'Technology'])->assertRedirect('/companies');
        $this->assertDatabaseHas('companies', ['name'=>'Example Company','created_by'=>$user->id]);
    }
}