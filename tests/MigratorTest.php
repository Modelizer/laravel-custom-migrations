<?php

use Codengine\CustomMigrations\Migrator;
use Illuminate\Database\ConnectionResolverInterface;
use Illuminate\Database\Migrations\MigrationRepositoryInterface;
use Illuminate\Filesystem\Filesystem;

/**
 * Author: Stefan Hueg
 * Copyright: Codengine @ 2015
 */
class MigratorTest extends Orchestra\Testbench\TestCase {
	/** @var Migrator|PHPUnit_Framework_MockObject_MockObject $migrator */
	protected $migrator;

	/** @var MigrationRepositoryInterface|PHPUnit_Framework_MockObject_MockObject */
	protected $migrationRepository;

	/** @var ConnectionResolverInterface|PHPUnit_Framework_MockObject_MockObject $connectionResolver */
	protected $connectionResolver;

	/** @var Filesystem|PHPUnit_Framework_MockObject_MockObject $fileSystem */
	protected $fileSystem;

	protected $migrationList = [
		'2015_03_05_012633_default_test_migration',
		'2015_03_05_012634_custom_test_migration',
	];

	public function setUp()
	{
		parent::setUp();
		$this->migrationRepository = $this->getMockBuilder(MigrationRepositoryInterface::class)->getMock();
		$this->connectionResolver  = $this->getMockBuilder(ConnectionResolverInterface::class)->getMock();
		$this->fileSystem          = $this->getMockBuilder(Filesystem::class)->getMock();
        $this->migrator            = $this->getMockBuilder(Migrator::class)
            ->setConstructorArgs(
                [
                    $this->migrationRepository,
                    $this->connectionResolver = $this->getMockBuilder(ConnectionResolverInterface::class)->getMock(),
                    $this->fileSystem
                ]
            )
            ->setMethods(['resolve'])
            ->getMock();

        $this->migrationRepository
            ->expects($this->any())
            ->method('log')
            ->willReturn(null);
	}

    /**
     * @param array $resolves
     */
	protected function setMigrationResolves(array $resolves)
	{
		$this->migrator->expects($this->any())
			->method('resolve')
			->will($this->returnValueMap($resolves));
	}

	public function testMigratorHasDefaultMigrationType()
	{
		$this->assertEquals('default', $this->migrator->getMigrationType());
	}

	public function testMigratorSetsMigrationType()
	{
		$this->migrator->setMigrationType('custom');
		$this->assertEquals('custom', $this->migrator->getMigrationType());
	}

	public function testMigratorOnlyExecutesDefaultMigration()
	{
		$this->migrationRepository->expects($this->once())
			->method('getNextBatchNumber')
			->willReturn(1);

		$default = $this->getMockBuilder(DefaultTestMigration::class)
			->setMethods(['up'])
			->getMock();
		$default->expects($this->once())->method('up');

		$custom = $this->getMockBuilder(CustomTestMigration::class)
			->setMethods(['up'])
			->getMock();
		$custom->expects($this->never())->method('up');

		$this->setMigrationResolves(
		    [
                ['2015_03_05_012633_default_test_migration', $default],
                ['2015_03_05_012634_custom_test_migration', $custom]
		    ]
        );

		$this->migrator->runMigrationList($this->migrationList);
	}

	public function testMigratorOnlyExecutesCustomMigration()
	{
		$this->migrator->setMigrationType('custom');

		$this->migrationRepository->expects($this->once())
			->method('getNextBatchNumber')
			->willReturn(1);

		$default = $this->getMockBuilder(DefaultTestMigration::class)
			->setMethods(['up'])
			->getMock();
		$default->expects($this->never())->method('up');

		$custom = $this->getMockBuilder(CustomTestMigration::class)
			->setMethods(['up'])
			->getMock();
		$custom->expects($this->once())->method('up');

		$this->setMigrationResolves(
		    [
		        ['2015_03_05_012633_default_test_migration', $default],
			    ['2015_03_05_012634_custom_test_migration', $custom]
	    	]
        );

		$this->migrator->runMigrationList($this->migrationList);
	}
}