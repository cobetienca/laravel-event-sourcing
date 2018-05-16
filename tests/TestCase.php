<?php

namespace Spatie\EventSaucer\Tests;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Orchestra\Testbench\TestCase as Orchestra;
use Spatie\EventSaucer\EventSaucerServiceProvider;

abstract class TestCase extends Orchestra
{
    public function setUp()
    {
        parent::setUp();

        $this->setUpDatabase($this->app);
    }

    protected function getPackageProviders($app)
    {
        return [
            EventSaucerServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
    }

    protected function setUpDatabase($app)
    {
        Schema::create('accounts', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('amount')->default(0);
            $table->timestamps();
        });

        include_once __DIR__.'/../database/migrations/create_stored_events_table.php.stub';

        (new \CreateStoredEventsTable())->up();
    }
}