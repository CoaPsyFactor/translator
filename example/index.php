<?php

use Translate\Translator;

require_once __DIR__ . '/../vendor/autoload.php';

$T = new Translator('en_US', __DIR__ . '/translations');

// Basic translate
echo $T->translate('WELCOME') . "\n";

// Translate with replacable placeholders
// NOTE: not defining section nor strict only - script will look for first translation with id "CREATED"
echo $T->translate('CREATED', [':username:' => 'Test User']) . "\n";

// Translate with replacable placeholders
// NOTE: defining section without using strict only, script will look for "CREATED" in section "BLOG_POST"
echo $T->translate('CREATED', [':username:' => 'BestUser', ':title:' => 'Post Title'], 'BLOG_POST') . "\n";

// Translate with replacable placeholders
// NOTE: defining section while using strict only - script wil throw an exception if requested identifier doesn't exist
// in provided section
echo $T->translate('REMOVED', [':username:' => 'BestUser', ':title:' => 'Its a title'], 'USER', true);