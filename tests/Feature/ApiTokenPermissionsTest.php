<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Laravel\Jetstream\Features;
use Laravel\Jetstream\Http\Livewire\ApiTokenManager;
use Livewire\Livewire;
use Tests\TestCase;

class ApiTokenPermissionsTest extends TestCase
{
    use RefreshDatabase;

    public function test_api_token_permissions_can_be_updated(): void
    {
        // Skip the test if API features are not enabled
        if (! Features::hasApiFeatures()) {
            $this->markTestSkipped('API support is not enabled.');
            return;
        }

        // Create a user with a personal team
        $this->actingAs($user = User::factory()->withPersonalTeam()->create());

        // Create an API token with 'create' and 'read' abilities
        $token = $user->tokens()->create([
            'name' => 'Test Token',
            'token' => Str::random(40),
            'abilities' => ['create', 'read'],
        ]);

        // Test updating the token permissions using the Livewire component
        Livewire::test(ApiTokenManager::class)
                    ->set(['managingPermissionsFor' => $token])
                    ->set(['updateApiTokenForm' => [
                        'permissions' => [
                            'delete',
                            'missing-permission',
                        ],
                    ]])
                    ->call('updateApiToken');

        // Reload the user to get the latest token data
        $updatedToken = $user->fresh()->tokens->first();

        // Assert the token now has 'delete' permission
        $this->assertTrue($updatedToken->can('delete'));

        // Assert the token no longer has 'read' permission
        $this->assertFalse($updatedToken->can('read'));

        // Assert the token does not have the 'missing-permission'
        $this->assertFalse($updatedToken->can('missing-permission'));

        // Assert that the final abilities are exactly ['delete']
        $this->assertSame(['delete'], $updatedToken->abilities);
    }
}
