<?php
namespace Tests\\Feature;
use Tests\\TestCase;
class CoreSmokeTest extends TestCase {
 public function test_health_endpoint_returns_success(): void {
  $this->get('/up')->assertOk();
 }
 public function test_login_page_is_available(): void {
  $this->get('/login')->assertOk();
 }
 public function test_setup_page_is_available_before_first_user(): void {
  $this->get('/setup')->assertOk();
 }
}