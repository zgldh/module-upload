<?php namespace $NAME$\Upload;

use $NAME$\Upload\Models\Upload;
use Illuminate\Support\ServiceProvider;

class UploadServiceProvider extends ServiceProvider
{

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
        parent::register();
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        Upload::deleted(
            function ($upload) {
                //
                $upload->deleteFile(false);
            }
        );

        $this->loadViewsFrom(__DIR__ . DIRECTORY_SEPARATOR . 'resources' . DIRECTORY_SEPARATOR . 'views',
            '$NAME$\Upload');
    }
}