<?php

use Translate\Translator;

require_once __DIR__ . '/../vendor/autoload.php';

$T = new Translator('en_US', __DIR__ . '/translations');

echo $T->translate('USER_CREATED', [':username:' => 'AwesomeName']);