<?php
namespace Tests\Feature;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
class LeadAccessTest extends TestCase {
    use RefreshDatabase;
    public function test_leads_require_authentication(): void {
        $this->get('/leads')->assertRedirect('/login');
    }
    public function test_authenticated_user_can_open_leads(): void {
        $user = User::create(['name'=>'Test Admin','email'=>'admin@example.test','password'=>'a-strong-test-password']);
        $this->actingAs($user)->get('/leads')->assertOk();
    }
    public function test_setup_closes_after_first_account_exists(): void {
        User::create(['name'=>'Test Admin','email'=>'admin@example.test','password'=>'a-strong-test-password']);
        $this->get('/setup')->assertNotFound();
    }
}