<?php

namespace Tests\Feature;

use App\Models\Campaign;
use App\Models\SmtpAccount;
use App\Models\SubscriberList;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CampaignTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private SmtpAccount $smtpAccount;
    private SubscriberList $subscriberList;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->smtpAccount = SmtpAccount::factory()->create(['user_id' => $this->user->id]);
        $this->subscriberList = SubscriberList::factory()->create(['user_id' => $this->user->id]);
    }

    /**
     * Test authenticated user can create campaign
     */
    public function test_user_can_create_campaign(): void
    {
        $campaignData = [
            'name' => 'Test Campaign',
            'subject' => 'Test Subject',
            'smtp_account_id' => $this->smtpAccount->id,
            'html_content' => '<h1>Test Content</h1>',
            'plain_text_content' => 'Test Content',
            'subscriber_list_ids' => [$this->subscriberList->id],
        ];

        $response = $this->actingAs($this->user)
            ->post(route('campaigns.store'), $campaignData);

        $response->assertRedirect();
        $this->assertDatabaseHas('campaigns', [
            'name' => 'Test Campaign',
            'subject' => 'Test Subject',
            'user_id' => $this->user->id,
        ]);
    }

    /**
     * Test campaign creation validation
     */
    public function test_campaign_creation_validation(): void
    {
        $invalidData = [
            'name' => '',
            'subject' => '',
            'smtp_account_id' => '',
            'html_content' => '',
        ];

        $response = $this->actingAs($this->user)
            ->post(route('campaigns.store'), $invalidData);

        $response->assertRedirect();
        $response->assertSessionHasErrors(['name', 'subject', 'smtp_account_id', 'html_content']);
    }

    /**
     * Test user can view their campaigns
     */
    public function test_user_can_view_campaigns(): void
    {
        Campaign::factory()->create(['user_id' => $this->user->id]);
        Campaign::factory()->create(); // Different user

        $response = $this->actingAs($this->user)
            ->get(route('campaigns.index'));

        $response->assertStatus(200);
        $response->assertViewHas('campaigns');

        $campaigns = $response->viewData('campaigns');
        $this->assertCount(1, $campaigns); // Only user's campaign
    }

    /**
     * Test user cannot view other users campaigns
     */
    public function test_user_cannot_view_other_users_campaigns(): void
    {
        $otherUser = User::factory()->create();
        $otherCampaign = Campaign::factory()->create(['user_id' => $otherUser->id]);

        $response = $this->actingAs($this->user)
            ->get(route('campaigns.show', $otherCampaign));

        $response->assertStatus(403); // Forbidden
    }

    /**
     * Test user can update their campaign
     */
    public function test_user_can_update_campaign(): void
    {
        $campaign = Campaign::factory()->create(['user_id' => $this->user->id]);

        $updateData = [
            'name' => 'Updated Campaign Name',
            'subject' => 'Updated Subject',
            'html_content' => '<h1>Updated Content</h1>',
        ];

        $response = $this->actingAs($this->user)
            ->put(route('campaigns.update', $campaign), $updateData);

        $response->assertRedirect();
        $this->assertDatabaseHas('campaigns', [
            'id' => $campaign->id,
            'name' => 'Updated Campaign Name',
            'subject' => 'Updated Subject',
        ]);
    }

    /**
     * Test user can delete draft campaign
     */
    public function test_user_can_delete_draft_campaign(): void
    {
        $campaign = Campaign::factory()->create([
            'user_id' => $this->user->id,
            'status' => 'draft'
        ]);

        $response = $this->actingAs($this->user)
            ->delete(route('campaigns.destroy', $campaign));

        $response->assertRedirect();
        $this->assertDatabaseMissing('campaigns', ['id' => $campaign->id]);
    }

    /**
     * Test user cannot delete sent campaign
     */
    public function test_user_cannot_delete_sent_campaign(): void
    {
        $campaign = Campaign::factory()->create([
            'user_id' => $this->user->id,
            'status' => 'sent'
        ]);

        $response = $this->actingAs($this->user)
            ->delete(route('campaigns.destroy', $campaign));

        $response->assertStatus(422); // Unprocessable Entity
        $this->assertDatabaseHas('campaigns', ['id' => $campaign->id]);
    }
}
