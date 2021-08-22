<?php

namespace Jhonoryza\ArgoTunnel\Providers;

use Jhonoryza\ArgoTunnel\Jobs\CreateTunnel;
use Illuminate\Support\ServiceProvider;

class ArgoTunnelServiceProvider extends ServiceProvider
{
    protected $defer = false;
    protected $configPath = __DIR__ . '/../config/tunneler.php';

    public function boot()
    {
        // helps deal with Lumen vs Laravel differences
        if (function_exists('config_path')) {
            $publishPath = config_path('tunneler.php');
        } else {
            $publishPath = base_path('config/tunneler.php');
        }

        $this->publishes([$this->configPath => $publishPath], 'config');

        if (config('tunneler.on_boot')) {
            dispatch(new CreateTunnel());
        }
    }

    public function register()
    {
        //
    }
}
