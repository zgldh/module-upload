<?php

return [
    'title'  => "Upload Files",
    'models' => [
        'upload' => [
            'title'  => 'Upload File',
            'fields' => [
                'name'            => 'Name',
                'description'     => 'Description',
                'disk'            => 'Disk',
                'path'            => 'Path',
                'size'            => 'Size',
                'type'            => 'Type',
                'user_id'         => 'User ID',
                'uploadable_id'   => 'Uploadable ID',
                'uploadable_type' => 'Uploadable Type',
                'file'            => 'File',
            ]
        ]
    ]
];
