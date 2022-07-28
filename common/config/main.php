<?php
require_once __DIR__.'/../../common/helpers.php';
return [
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'formatter' => [
               'class' => 'yii\i18n\Formatter',
               'currencyCode' => '₹',
               'datetimeFormat' => 'php: d/M/y H:i'
        ]
    ],
];
