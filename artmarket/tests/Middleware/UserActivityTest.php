<?php

namespace Tests\Middleware;

use Carbon\Carbon;
use Tests\TestCase;

class UserActivityTest extends TestCase
{
    public function testLastSeenAt()
    {
        $this->actingAs($user = $this->createUser());
        $this->get(route('home'))->assertStatus(200);
        $user->refresh();

        $lastSeenAt = $user->last_seen_at->unix();
        $this->assertTrue($user->last_seen_at->isCurrentMinute());

        $this->travel(55)->seconds();
        $this->get(route('home'))->assertStatus(200);
        $user->refresh();
        $this->assertEquals($lastSeenAt, $user->last_seen_at->unix());

        $this->travel(61)->seconds();
        $this->get(route('home'))->assertStatus(200);
        $user->refresh();
        $this->assertEquals(Carbon::now()->unix(), $user->last_seen_at->unix());
    }
}