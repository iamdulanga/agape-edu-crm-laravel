<?php

namespace Tests\Feature;

use App\Models\Lead;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LeadEditTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    private Lead $lead;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a role and user
        $role = Role::create(['name' => 'manager']);
        $this->user = User::factory()->create();
        $this->user->roles()->attach($role);

        // Create a test lead
        $this->lead = Lead::create([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john@example.com',
            'phone' => '1234567890',
            'age' => 25,
            'city' => 'New York',
            'passport' => 'yes',
            'inquiry_date' => '2024-01-15',
            'study_level' => 'bachelor',
            'priority' => 'high',
            'preferred_universities' => 'Harvard, MIT',
            'special_notes' => 'Interested in Computer Science',
            'status' => 'new',
        ]);
    }

    /** @test */
    public function it_displays_edit_page_with_lead_data()
    {
        $response = $this->actingAs($this->user)
            ->get(route('leads.edit', $this->lead));

        $response->assertStatus(200);
        $response->assertViewIs('leads.edit');
        $response->assertViewHas('lead', $this->lead);
        $response->assertSee('Edit Lead');
        $response->assertSee($this->lead->first_name);
        $response->assertSee($this->lead->last_name);
        $response->assertSee($this->lead->email);
    }

    /** @test */
    public function it_updates_lead_successfully()
    {
        $updatedData = [
            'first_name' => 'Jane',
            'last_name' => 'Smith',
            'email' => 'jane@example.com',
            'phone' => '9876543210',
            'age' => 30,
            'city' => 'Los Angeles',
            'passport' => 'no',
            'inquiry_date' => '2024-02-20',
            'study_level' => 'master',
            'priority' => 'very_high',
            'preferred_universities' => 'Stanford, Berkeley',
            'special_notes' => 'Interested in Data Science',
            'status' => 'contacted',
        ];

        $response = $this->actingAs($this->user)
            ->patch(route('leads.update', $this->lead), $updatedData);

        $response->assertRedirect(route('leads.index'));
        $response->assertSessionHas('success', 'Lead updated successfully!');

        $this->assertDatabaseHas('leads', [
            'id' => $this->lead->id,
            'first_name' => 'Jane',
            'last_name' => 'Smith',
            'email' => 'jane@example.com',
            'status' => 'contacted',
        ]);
    }

    /** @test */
    public function it_validates_required_fields()
    {
        $response = $this->actingAs($this->user)
            ->patch(route('leads.update', $this->lead), [
                'first_name' => '',
                'last_name' => '',
            ]);

        $response->assertSessionHasErrors(['first_name', 'last_name']);
    }

    /** @test */
    public function it_validates_email_format()
    {
        $response = $this->actingAs($this->user)
            ->patch(route('leads.update', $this->lead), [
                'first_name' => 'John',
                'last_name' => 'Doe',
                'email' => 'invalid-email',
            ]);

        $response->assertSessionHasErrors(['email']);
    }

    /** @test */
    // DISABLED: Test removed as assigned_to column has been removed from leads table
    // public function it_can_update_assigned_to_field()
    // {
    //     $counselor = User::factory()->create();
    //     $counselorRole = Role::create(['name' => 'counselor']);
    //     $counselor->roles()->attach($counselorRole);
    //
    //     $response = $this->actingAs($this->user)
    //         ->patch(route('leads.update', $this->lead), [
    //             'first_name' => $this->lead->first_name,
    //             'last_name' => $this->lead->last_name,
    //             'assigned_to' => $counselor->id,
    //         ]);
    //
    //     $response->assertRedirect(route('leads.index'));
    //     $this->assertDatabaseHas('leads', [
    //         'id' => $this->lead->id,
    //         'assigned_to' => $counselor->id,
    //     ]);
    // }

    /** @test */
    public function it_requires_authentication()
    {
        $response = $this->get(route('leads.edit', $this->lead));
        $response->assertRedirect(route('login'));

        $response = $this->patch(route('leads.update', $this->lead), [
            'first_name' => 'Test',
            'last_name' => 'User',
        ]);
        $response->assertRedirect(route('login'));
    }
}
