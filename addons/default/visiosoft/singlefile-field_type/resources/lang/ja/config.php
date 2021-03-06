<?php

return [
    'folders' => [
        'name'         => 'フォルダー',
        'instructions' => 'このフィールドで使用できるフォルダを指定します。すべてのフォルダを表示するには、空白のままにします。',
        'warning'      => '既存のフォルダのアクセス許可は、選択したフォルダよりも優先されます。',
    ],
    'max'     => [
        'name'         => '最大アップロードサイズ',
        'instructions' => '最大アップロードサイズを <strong>メガバイト</strong>で指定します。',
        'warning'      => '指定されていない場合、フォルダーの最大値とサーバーの最大値が代わりに使用されます。',
    ],
    'mode'    => [
        'name'         => '入力モード',
        'instructions' => 'ユーザーはどのようにファイル入力を提供する必要がありますか？',
        'option'       => [
            'default' => 'ファイルをアップロードまたは選択します。',
            'select'  => 'ファイルのみを選択します。',
            'upload'  => 'ファイルのみをアップロードします。',
        ],
    ],
];
