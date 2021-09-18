<?php

/**
 * Copyright 2021 whojinn

 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at

 *  http://www.apache.org/licenses/LICENSE-2.0

 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

declare(strict_types=1);

require_once __DIR__ . '/vendor/autoload.php';

use League\CommonMark\Environment\Environment;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\MarkdownConverter;
use Whojinn\Danraku\DanrakuExtension;

$config = [
    'danraku' => [
        'ignore_alphabet' => false, // trueにすると、行頭が英数字だった場合には全角スペースを入れなくなる
        'ignore_footnote' => true, // falseにすると、FootnoteExtension使用時に脚注にも全角スペースを入れるようになる
    ],
];

$environment = new Environment($config);

$environment
    ->addExtension(new CommonMarkCoreExtension())
    ->addExtension(new DanrakuExtension());

$converter = new MarkdownConverter($environment);

$markdown = 'この拡張機能は実によい・・・まさに革命的だ';

//<p>　この拡張機能は実によい・・・まさに革命的だ</p>
echo $converter->convertToHtml($markdown);
