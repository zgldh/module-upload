<?php

return [
    'title'  => "上传管理",
    'models' => [
        'upload' => [
            'title'  => '上传文件',
            'fields' => [
                'name'            => '名字',
                'description'     => '描述',
                'disk'            => '位置',
                'path'            => '路径',
                'size'            => '文件大小',
                'type'            => '类型',
                'user_id'         => 'User ID',
                'uploadable_id'   => 'Uploadable ID',
                'uploadable_type' => 'Uploadable Type',
                'file'            => '文件',
            ]
        ]
    ]
];
