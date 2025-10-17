<?php

namespace Tests\Feature;

use App\Models\Lead;
use App\Models\User;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LeadPaginationTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a role and user
        $role = Role::create(['name' => 'manager']);
        $this->user = User::factory()->create();
        $this->user->roles()->attach($role);
    }

    /** @test */
    public function it_paginates_leads_with_15_items_per_page()
    {
        // Create 20 test leads
        Lead::factory()->count(20)->create();

        $response = $this->actingAs($this->user)
            ->get(route('leads.index'));

        $response->assertStatus(200);
        $response->assertViewHas('leads', function ($leads) {
            return $leads->count() === 15 && $leads->total() === 20;
        });
    }

    /** @test */
    public function it_displays_pagination_links()
    {
        // Create 20 test leads
        Lead::factory()->count(20)->create();

        $response = $this->actingAs($this->user)
            ->get(route('leads.index'));

        $response->assertStatus(200);
        $response->assertSee('Showing');
        $response->assertSee('15');
        $response->assertSee('20');
        $response->assertSee('results');
    }

    /** @test */
    public function it_navigates_to_second_page()
    {
        // Create 20 test leads
        Lead::factory()->count(20)->create();

        $response = $this->actingAs($this->user)
            ->get(route('leads.index', ['page' => 2]));

        $response->assertStatus(200);
        $response->assertViewHas('leads', function ($leads) {
            return $leads->count() === 5 && $leads->currentPage() === 2;
        });
    }

    /** @test */
    public function it_maintains_pagination_with_search()
    {
        // Create 20 test leads
        Lead::factory()->count(20)->create([
            'first_name' => 'John',
            'last_name' => 'Doe',
        ]);

        $response = $this->actingAs($this->user)
            ->get(route('leads.search', ['search' => 'John']));

        $response->assertStatus(200);
        $response->assertViewHas('leads', function ($leads) {
            return $leads->count() === 15 && $leads->total() === 20;
        });
    }
}
