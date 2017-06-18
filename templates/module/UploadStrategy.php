<?php namespace WoXuanWang\Upload;

use zgldh\UploadManager\UploadStrategy as BaseStrategy;

class UploadStrategy extends BaseStrategy
{
    /**
     * 得到 disk public 内上传的文件的URL
     * @param $path
     * @return string
     */
    public function getUploadUrl($path)
    {
        $url = url('u/' . $path);
        return $url;
    }

    public function getQiniuResourcesUrl($path)
    {
        $url = 'http://' . trim(config('filesystems.disks.qiniu-resources.domains.default'), '/') . '/' . trim($path, '/');
        return $url;
    }
}