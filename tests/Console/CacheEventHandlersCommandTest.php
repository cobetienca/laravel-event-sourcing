<?php

namespace Spatie\EventSourcing\Tests\Console;

use Spatie\Snapshots\MatchesSnapshots;
use Spatie\EventSourcing\Projectionist;
use Spatie\EventSourcing\Tests\TestCase;
use Spatie\EventSourcing\Tests\TestClasses\Reactors\BrokeReactor;
use Spatie\EventSourcing\Tests\TestClasses\Projectors\BalanceProjector;

final class CacheEventHandlersCommandTest extends TestCase
{
    use MatchesSnapshots;

    /** @var \Spatie\EventSourcing\Projectionist */
    private $projectionist;

    public function setUp(): void
    {
        parent::setUp();

        $this->projectionist = app(Projectionist::class);
    }

    /** @test */
    public function it_can_cache_the_registered_projectors()
    {
        $this->projectionist->addProjector(BalanceProjector::class);

        $this->projectionist->addReactor(BrokeReactor::class);

        $this->artisan('event-sourcing:cache-event-handlers')->assertExitCode(0);

        $this->assertMatchesSnapshot(file_get_contents(config('event-sourcing.cache_path').'/event-handlers.php'));
    }
}
