<?php

namespace InfyOm\Generator\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use InfyOm\Generator\Commands\API\APIControllerGeneratorCommand;
use InfyOm\Generator\Commands\API\APIGeneratorCommand;
use InfyOm\Generator\Commands\API\APIRequestsGeneratorCommand;
use InfyOm\Generator\Commands\API\TestsGeneratorCommand;
use InfyOm\Generator\Commands\APIScaffoldGeneratorCommand;
use InfyOm\Generator\Commands\Common\MigrationGeneratorCommand;
use InfyOm\Generator\Commands\Common\ModelGeneratorCommand;
use InfyOm\Generator\Commands\Common\RepositoryGeneratorCommand;
use InfyOm\Generator\Commands\Publish\GeneratorPublishCommand;
use InfyOm\Generator\Commands\Publish\PublishTablesCommand;
use InfyOm\Generator\Commands\Publish\PublishUserCommand;
use InfyOm\Generator\Commands\RollbackGeneratorCommand;
use InfyOm\Generator\Commands\Scaffold\ControllerGeneratorCommand;
use InfyOm\Generator\Commands\Scaffold\RequestsGeneratorCommand;
use InfyOm\Generator\Commands\Scaffold\ScaffoldGeneratorCommand;
use InfyOm\Generator\Commands\Scaffold\ViewsGeneratorCommand;
use InfyOm\Generator\Common\GeneratorConfig;

class InfyOmGeneratorServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom($this->getConfigPath(), 'laravel_generator');

        $this->app->singleton(GeneratorConfig::class, function () {
            return new GeneratorConfig();
        });

        $this->app->singleton('infyom:api', function ($app) {
            return new APIGeneratorCommand($app);
        });

        $this->app->singleton('infyom:api_scaffold', function ($app) {
            return new APIScaffoldGeneratorCommand($app);
        });

        $this->app->singleton('infyom:migration', function ($app) {
            return new MigrationGeneratorCommand($app);
        });

        $this->app->singleton('infyom:model', function ($app) {
            return new ModelGeneratorCommand($app);
        });

        $this->app->singleton('infyom:publish', function ($app) {
            return new GeneratorPublishCommand($app);
        });

        $this->app->singleton('infyom:repository', function ($app) {
            return new RepositoryGeneratorCommand($app);
        });

        $this->app->singleton('infyom:rollback', function ($app) {
            return new RollbackGeneratorCommand($app);
        });

        $this->app->singleton('infyom:scaffold', function ($app) {
            return new ScaffoldGeneratorCommand($app);
        });

        $this->app->singleton('infyom.api:controller', function ($app) {
            return new APIControllerGeneratorCommand($app);
        });

        $this->app->singleton('infyom.api:requests', function ($app) {
            return new APIRequestsGeneratorCommand($app);
        });

        $this->app->singleton('infyom.api:tests', function ($app) {
            return new TestsGeneratorCommand($app);
        });

        $this->app->singleton('infyom.publish:tables', function ($app) {
            return new PublishTablesCommand($app);
        });

        $this->app->singleton('infyom.publish:user', function ($app) {
            return new PublishUserCommand($app);
        });

        $this->app->singleton('infyom.scaffold:controller', function ($app) {
            return new ControllerGeneratorCommand($app);
        });

        $this->app->singleton('infyom.scaffold:requests', function ($app) {
            return new RequestsGeneratorCommand($app);
        });

        $this->app->singleton('infyom.scaffold:views', function ($app) {
            return new ViewsGeneratorCommand($app);
        });

        $this->commands([
            'infyom:api',
            'infyom:api_scaffold',
            'infyom:migration',
            'infyom:model',
            'infyom:publish',
            'infyom:repository',
            'infyom:rollback',
            'infyom:scaffold',
            'infyom.api:controller',
            'infyom.api:requests',
            'infyom.api:tests',
            'infyom.publish:tables',
            'infyom.publish:user',
            'infyom.scaffold:controller',
            'infyom.scaffold:requests',
            'infyom.scaffold:views'
        ]);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->publishes([
            $this->getConfigPath() => config_path('laravel_generator.php'),
        ], 'laravel-generator-config');

        $this->publishes([
            $this->getViewPath() => resource_path('views/vendor/laravel-generator'),
        ], 'laravel-generator-templates');

        $this->loadViewsFrom($this->getViewPath(), 'laravel-generator');

        View::composer('*', function ($view) {
            $view->with(['config' => app(GeneratorConfig::class)]);
        });

        Blade::directive('tab', function () {
            return '<?php echo infy_tab() ?>';
        });

        Blade::directive('tabs', function ($count) {
            return "<?php echo infy_tabs($count) ?>";
        });

        Blade::directive('nl', function () {
            return '<?php echo infy_nl() ?>';
        });

        Blade::directive('nls', function ($count) {
            return "<?php echo infy_nls($count) ?>";
        });
    }

    public function getConfigPath()
    {
        return __DIR__ . '/../../config/laravel_generator.php';
    }

    public function getViewPath()
    {
        return __DIR__ . '/../../views';
    }
}
