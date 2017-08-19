<?php namespace zgldh\ModuleUpload;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Artisan;
use zgldh\Scaffold\Installer\ModuleInstaller as BaseInstaller;
use zgldh\Scaffold\Installer\Utils;
use zgldh\UploadManager\UploadManagerServiceProvider;

/**
 * Created by PhpStorm.
 * User: zhangwb-pc
 * Date: 08/01/2017
 * Time: 2:00 PM
 */
class ModuleInstaller extends BaseInstaller
{
    public function __construct()
    {
        parent::__construct();
        $this->moduleTemplatePath = __DIR__ . '/../templates/';
    }

    public function run()
    {
        $this->copyModuleFilesTo('Upload');

        $this->addServiceProvider('Upload', 'UploadServiceProvider::class');
        $this->addRoute('Upload');
        $this->addToVueRoute('Upload');
        $this->addAdminMenuItem($this->getModuleTemplateContent('menu.blade.php'));
        $this->copyLanguageFiles('module-upload');

        // Install laravel-permission
        App::register(UploadManagerServiceProvider::class);
        Artisan::call('vendor:publish', ['--provider' => UploadManagerServiceProvider::class]);

        // Update configuration
        $this->updateConfiguration();

        // Publish migrations
        $this->publishMigration('UpdateUploadsTable', __DIR__ . '/../migrations/update_uploads_table.php');

        Artisan::call('migrate');

        exec('composer dumpautoload');
    }

    private function updateConfiguration()
    {
        $moduleRoot = $this->moduleRootNamespace();
        $replacement = [
            "local"                                     => "public",
            "App\Upload::class"                         => "{$moduleRoot}\Upload\Models\Upload::class",
            "zgldh\UploadManager\UploadStrategy::class" => "{$moduleRoot}\Upload\UploadStrategy::class"
        ];
        Utils::replaceFilePlaceholders(config_path('upload.php'), $replacement, null, null);
    }
}