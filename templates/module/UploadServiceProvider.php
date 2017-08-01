<?php namespace $NAME$\Upload;

use $NAME$\Upload\Models\Upload;

class UploadServiceProvider extends \zgldh\ModuleUpload\UploadServiceProvider
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
        $this->loadViewsFrom(__DIR__ . DIRECTORY_SEPARATOR . 'resources' . DIRECTORY_SEPARATOR . 'views',
            '$NAME$\Upload');

        Upload::deleted(
            function ($upload) {
                //
                $upload->deleteFile(false);
            }
        );

    }
}