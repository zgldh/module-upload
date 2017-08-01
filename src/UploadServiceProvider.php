<?php namespace zgldh\ModuleUpload;

use Illuminate\Support\ServiceProvider;
use zgldh\UploadManager\UploadManagerServiceProvider;

/**
 * Created by PhpStorm.
 * User: zhangwb-pc
 * Date: 11/14/2016
 * Time: 2:00 PM
 */
class UploadServiceProvider extends ServiceProvider
{

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(UploadManagerServiceProvider::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
    }
}