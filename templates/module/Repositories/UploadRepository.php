<?php

namespace $NAME$\Upload\Repositories;

use $NAME$\Upload\Models\Upload;
use zgldh\Scaffold\BaseRepository;
use zgldh\UploadManager\UploadException;
use zgldh\UploadManager\UploadManager;

class UploadRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'description',
        'path'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Upload::class;
    }

    public function create(array $attributes)
    {
        unset($attributes['path']);
        unset($attributes['size']);

        $uploadManager = UploadManager::getInstance();
        $upload = $uploadManager->withDisk($attributes['disk'])->upload($attributes['file']);
        if (!$upload) {
            throw new UploadException($uploadManager->getErrors());
        }

        $upload->name = @$attributes['name'] ?: $attributes['file']->getClientOriginalName();
        $upload->description = @$attributes['description'];
        $upload->user_id = $attributes['user_id'];
        $upload->save();
        return $upload;
    }

    public function update(array $attributes, $id)
    {
        unset($attributes['user_id']);
        unset($attributes['path']);
        unset($attributes['size']);
        unset($attributes['uploadable_id']);
        unset($attributes['uploadable_type']);

        $upload = $this->find($id);
        $upload->name = @$attributes['name'] ?: $attributes['file']->getClientOriginalName();
        $upload->description = @$attributes['description'];
        if (isset($attributes['file'])) {
            $uploadManager = UploadManager::getInstance();
            $uploadManager->withDisk($attributes['disk'])->update($upload, $attributes['file']);
        };
        $upload->save();

        return $upload;
    }
}
