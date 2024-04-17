<?php

use yii\web\JsExpression;

return [
    'adminEmail' => 'admin@example.com',
    'supportEmail' => 'support@example.com',
    'user.passwordResetTokenExpire' => 3600,


	// >>> ADMIN/INFO >>>

	'required-php-version' => '7.1',
	// 'upload-max-size' => 1024*1024*10, // Максимальный вес файла для загрузки = 10Mb

	// <<< ADMIN/INFO <<<

	// DICTIONARIES
    // !!! WARNING! AFTER CHANGING THE VALUES BELOW CHECK UP THE DATA BASE !!!
    'dictionary' => [

        // YES-NO
        'yes-no' => [
            0 => ['name' => 'no', 'description' => 'Нет', 'color' => 'red' ],
            1 => ['name' => 'yes', 'description' => 'Да', 'color' => 'green' ],
        ],

        // ACTIVE
        'active' => [
            0 => ['name' => 'inactive', 'description' => 'Неактивен', 'color' => 'gray' ],
            1 => ['name' => 'active', 'description' => 'Активен', 'color' => 'green' ],
        ],

        // MODERATION STATUS
        'moderation-status' => [
            0 => ['name' => 'new', 'description' => 'Новое', 'color' => 'black' ],
            10 => ['name' => 'approved', 'description' => 'Одобрено', 'color' => 'green' ],
            20 => ['name' => 'rejected', 'description' => 'Отклонено', 'color' => 'red' ],
        ],


        // ------- APP ---------

        // IMAGE TYPES
        'uploaded-image-type' => [
            1 => ['name' => 'check', 'description' => 'Чек', 'color' => 'black' ],
            2 => ['name' => 'photo', 'description' => 'Фото', 'color' => 'black' ],
        ],

        // CHEATING TYPES
        'cheating-type' => [
            1 => ['name' => 'game-start', 'description' => 'Старт игры' ],
            2 => ['name' => 'game-save', 'description' => 'Сохранение игры' ],
        ],

    ],

    'editorjs-widget/plugins' => [
        'header' => [
            'repository' => 'editorjs/header',
            'class' => new JsExpression('Header'),
            'inlineToolbar' => true,
            'config' => ['placeholder' => 'Header', 'levels' => [2, 3, 4, 5]],
            'shortcut' => 'CMD+SHIFT+H'
        ],
        'paragraph' => [
            'repository' => 'editorjs/paragraph',
            'class' => new JsExpression('Paragraph'),
            'inlineToolbar' => true,
        ],
        'image' => [
            'repository' => 'editorjs/image',
            'class' => new JsExpression('ImageTool'),
            'config' => [
                'field' => 'image',
                'additionalRequestHeaders' => [],
                'endpoints' => [
                ]
            ]
        ],
        'list' => [
            'repository' => 'editorjs/list',
            'class' => new JsExpression('List'),
            'inlineToolbar' => true,
            'shortcut' => 'CMD+SHIFT+L'
        ],
        'table' => [
            'repository' => 'editorjs/table',
            'class' => new JsExpression('Table'),
            'inlineToolbar' => true,
            'shortcut' => 'CMD+ALT+T'
        ],
        'quote' => [
            'repository' => 'editorjs/quote',
            'class' => new JsExpression('Quote'),
            'inlineToolbar' => true,
            'config' => ['quotePlaceholder' => 'Quote', 'captionPlaceholder' => 'Author'],
            'shortcut' => 'CMD+SHIFT+O'
        ],
    ],

    // >>> Kartik config >>>
    'bsVersion' => '5',
    'icon-framework' => kartik\icons\Icon::FAS
];
