<?php
namespace Tests\Feature;
use App\Models\Company;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
class ContactAccessTest extends TestCase {
    use RefreshDatabase;
    public function test_contacts_require_authentication(): void {
        $this->get('/contacts')->assertRedirect('/login');
    }
    public function test_authenticated_user_can_create_contact_linked_to_company(): void {
        $user = User::create(['name'=>'Test Admin','email'=>'admin@example.test','password'=>'a-strong-test-password']);
        $company = Company::create(['name'=>'Example Company','created_by'=>$user->id]);
        $this->actingAs($user)->post('/contacts', ['first_name'=>'Alex','last_name'=>'Smith','email'=>'alex@example.test','company_id'=>$company->id])->assertRedirect('/contacts');
        $this->assertDatabaseHas('contacts', ['first_name'=>'Alex','company_id'=>$company->id,'created_by'=>$user->id]);
    }
}