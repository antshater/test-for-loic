<?php


namespace Tests\Feature;


use App\Turnstile;
use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class TurnstileControllerTest extends TestCase
{
    use DatabaseTransactions;

    private $user;

    public function setUp()
    {
        parent::setUp();
        $this->user = factory(User::class)->create();
    }

    public function test_it_remains_opened_when_coin_inserted()
    {
        $turnstile = factory(Turnstile::class)->create();
        $this->assertTrue($turnstile->is_locked);
        $this->postJson(route('api.turnstiles.insert-coin', $turnstile))->assertSuccessful();

        $this->assertFalse($turnstile->fresh()->is_locked);
    }

    public function test_it_pass_user_when_unlocked_and_remains_locked()
    {
        $turnstile = factory(Turnstile::class)->create(['is_locked' => false]);
        $this->actingAs($this->user)->postJson(route('api.turnstiles.pass', $turnstile))
            ->assertSuccessful()
            ->assertExactJson(['result' => 'You passed !!!']);
        $this->assertTrue($turnstile->fresh()->is_locked);
    }

    public function test_it_doesnt_pass_user_when_locked()
    {
        $turnstile = factory(Turnstile::class)->create(['is_locked' => true]);
        $this->actingAs($this->user)->postJson(route('api.turnstiles.pass', $turnstile))->assertForbidden();
    }

    public function test_it_turn_alarm_when_somebody_try_to_pass_without_coin()
    {
        $turnstile = factory(Turnstile::class)->create(['is_locked' => true]);
        $this->actingAs($this->user)->postJson(route('api.turnstiles.pass', $turnstile));
        $this->assertTrue($turnstile->fresh()->alarm_on);
    }

    public function test_it_turn_off_alarm_after_coin_inserting()
    {
        $turnstile = factory(Turnstile::class)->create(['is_locked' => true, 'alarm_on' => true]);
        $this->postJson(route('api.turnstiles.insert-coin', $turnstile))->assertSuccessful();
        $this->assertFalse($turnstile->fresh()->alarm_on);
    }

    public function it_eat_money()
    {
        $turnstile = factory(Turnstile::class)->create(['is_locked' => true, 'alarm_on' => true]);
        $this->postJson(route('api.turnstiles.insert-coin', $turnstile))->assertSuccessful();
        $this->postJson(route('api.turnstiles.insert-coin', $turnstile))->assertSuccessful();
        $this->assertFalse($turnstile->fresh()->is_locked);
    }
}
