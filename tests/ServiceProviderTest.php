<?php

use Codengine\CustomMigrations\Commands\MigrateCommand;
use Codengine\CustomMigrations\Commands\RefreshCommand;
use Codengine\CustomMigrations\Commands\ResetCommand;
use Codengine\CustomMigrations\Commands\RollbackCommand;
use Codengine\CustomMigrations\CustomMigrationsServiceProvider;
use Codengine\CustomMigrations\Migrator;
use Illuminate\Foundation\Application;

/**
 * Author: Stefan Hueg
 * Copyright: Codengine @ 2015
 */
class ServiceProviderTest extends Orchestra\Testbench\TestCase {
    /**
     * @param Application $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
	{
		return [
			CustomMigrationsServiceProvider::class
		];
	}

	public function testMigratorServiceDoesResolve()
	{
		$this->assertInstanceOf(Migrator::class, $this->app->make('migrator'));
	}

	public function testMigrateCommandDoesResolve()
	{
		$this->assertInstanceOf(MigrateCommand::class, $this->app->make('command.migrate'));
	}

	public function testMigrateCommandHasTypeOption()
	{
		/** @var MigrateCommand $command */
		$command = $this->app->make('command.migrate');
		$this->assertTrue($command->getDefinition()->hasOption('type'));
		$this->assertTrue($command->getDefinition()->hasOption('connection_name'));
		$this->assertTrue($command->getDefinition()->hasOption('db_name'));
	}

	public function testRefreshCommandDoesResolve()
	{
		$this->assertInstanceOf(RefreshCommand::class, $this->app->make('command.migrate.refresh'));
	}

	public function testRefreshCommandHasTypeOption()
	{
		/** @var RefreshCommand $command */
		$command = $this->app->make('command.migrate.refresh');
		$this->assertTrue($command->getDefinition()->hasOption('type'));
        $this->assertTrue($command->getDefinition()->hasOption('connection_name'));
        $this->assertTrue($command->getDefinition()->hasOption('db_name'));
	}

	public function testResetCommandDoesResolve()
	{
		$this->assertInstanceOf(ResetCommand::class, $this->app->make('command.migrate.reset'));
	}

	public function testResetCommandHasTypeOption()
	{
		/** @var ResetCommand $command */
		$command = $this->app->make('command.migrate.reset');
		$this->assertTrue($command->getDefinition()->hasOption('type'));
        $this->assertTrue($command->getDefinition()->hasOption('connection_name'));
        $this->assertTrue($command->getDefinition()->hasOption('db_name'));
	}

	public function testRollbackCommandDoesResolve()
	{
		$this->assertInstanceOf(RollbackCommand::class, $this->app->make('command.migrate.rollback'));
	}

	public function testRollbackCommandHasTypeOption()
	{
		/** @var RollbackCommand $command */
		$command = $this->app->make('command.migrate.rollback');
		$this->assertTrue($command->getDefinition()->hasOption('type'));
        $this->assertTrue($command->getDefinition()->hasOption('connection_name'));
        $this->assertTrue($command->getDefinition()->hasOption('db_name'));
	}
}