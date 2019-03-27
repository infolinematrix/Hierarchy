<?php

class BuilderServiceProviderTest {

    /** @test */
    function it_registers_model_builder()
    {
        $this->assertInstanceOf(
            'Reactor\Hierarchy\Builders\ModelBuilder',
            $this->app->make('Reactor\Hierarchy\Contract\Builders\ModelBuilderContract')
        );
    }

    /** @test */
    function it_registers_migration_builder()
    {
        $this->assertInstanceOf(
            'Reactor\Hierarchy\Builders\MigrationBuilder',
            $this->app->make('Reactor\Hierarchy\Contract\Builders\MigrationBuilderContract')
        );
    }

    /** @test */
    function it_registers_form_builder()
    {
        $this->assertInstanceOf(
            'Reactor\Hierarchy\Builders\FormBuilder',
            $this->app->make('Reactor\Hierarchy\Contract\Builders\FormBuilderContract')
        );
    }

    /** @test */
    function it_registers_cache_builder()
    {
        $this->assertInstanceOf(
            'Reactor\Hierarchy\Builders\CacheBuilder',
            $this->app->make('Reactor\Hierarchy\Contract\Builders\CacheBuilderContract')
        );
    }

    /** @test */
    function it_registers_builder_service()
    {
        $this->assertInstanceOf(
            'Reactor\Hierarchy\Builders\BuilderService',
            $this->app->make('Reactor\Hierarchy\Contract\Builders\BuilderServiceContract')
        );
    }

}