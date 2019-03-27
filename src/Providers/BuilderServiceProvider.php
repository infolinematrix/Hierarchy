<?php

namespace Reactor\Hierarchy\Providers;


use Illuminate\Support\ServiceProvider;

class BuilderServiceProvider extends ServiceProvider {

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            'Reactor\Hierarchy\Contract\Builders\ModelBuilderContract',
            'Reactor\Hierarchy\Contract\Builders\MigrationBuilderContract',
            'Reactor\Hierarchy\Contract\Builders\FormBuilderContract',
            'Reactor\Hierarchy\Contract\Builders\CacheBuilderContract',
            'Reactor\Hierarchy\Contract\Builders\BuilderServiceContract'
        ];
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerModelBuilder();
        $this->registerMigrationBuilder();
        $this->registerFormBuilder();
        $this->registerCacheBuilder();
        $this->registerBuilderService();
    }

    /**
     * Registers the model builder
     *
     * @return void
     */
    protected function registerModelBuilder()
    {
        $this->app->bind(
            'Reactor\Hierarchy\Contract\Builders\ModelBuilderContract',
            'Reactor\Hierarchy\Builders\ModelBuilder'
        );
    }

    /**
     * Registers the migration builder
     *
     * @return void
     */
    protected function registerMigrationBuilder()
    {
        $this->app->bind(
            'Reactor\Hierarchy\Contract\Builders\MigrationBuilderContract',
            'Reactor\Hierarchy\Builders\MigrationBuilder'
        );
    }

    /**
     * Registers the form builder
     *
     * @return void
     */
    protected function registerFormBuilder()
    {
        $this->app->bind(
            'Reactor\Hierarchy\Contract\Builders\FormBuilderContract',
            'Reactor\Hierarchy\Builders\FormBuilder'
        );
    }

    /**
     * Registers the migration builder
     *
     * @return void
     */
    protected function registerCacheBuilder()
    {
        $this->app->bind(
            'Reactor\Hierarchy\Contract\Builders\CacheBuilderContract',
            'Reactor\Hierarchy\Builders\CacheBuilder'
        );
    }

    /**
     * Registers the builder service
     *
     * @return void
     */
    protected function registerBuilderService()
    {
        $this->app->bind(
            'Reactor\Hierarchy\Contract\Builders\BuilderServiceContract',
            'Reactor\Hierarchy\Builders\BuilderService'
        );
    }
    
}