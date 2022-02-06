[![PHP Composer](https://github.com/whojinn/danraku/actions/workflows/php.yml/badge.svg)](https://github.com/whojinn/danraku/actions/workflows/php.yml)
![Packagist PHP Version Support](https://img.shields.io/packagist/php-v/whojinn/danraku)
![Packagist Version](https://img.shields.io/packagist/v/whojinn/danraku)
![Packagist Downloads](https://img.shields.io/packagist/dt/whojinn/danraku)
![GitHub](https://img.shields.io/github/license/whojinn/danraku)

# Danraku
自動で段落の頭に全角スペースを入れてくれたり、区切り約物の直後に全角スペースを入れてくれる[League/CommonMark](https://commonmark.thephpleague.com/)拡張機能。

## インストール方法
`$ composer require whojinn/danraku`

## 使用方法
```php
$environment = new Environment($config);

$environment
    ->addExtension(new CommonMarkCoreExtension())
    ->addExtension(new DanrakuExtension());

$converter = new MarkdownConverter($environment);

$markdown = 'この拡張機能は実によい・・・まさに革命的だ';

//<p>　この拡張機能は実によい・・・まさに革命的だ</p>
echo $converter->convert($markdown);
```

## 設定
```php
// 以下、デフォルトでの設定
$config = [
    'danraku' => [
        'ignore_alphabet' => false,     // trueにすると、行頭が英数字だった場合には字下げをしなくなる
        'ignore_footnote' => true,      // trueにすると、FootnoteExtension使用時に脚注には字下げをしなくなる
        'ignore_dash' => true,          // trueにすると、全角ダッシュ（―）、ハイフンで字下げをしなくなる
        'spacing_yakumono' => true,     // trueにすると、「？」と「！」の前に全角スペースを空けるようになる「閉じ括弧の直前を除く」
    ],
];
```

## ライセンス
Apache License, Version 2.0  
- [英語原文](https://www.apache.org/licenses/LICENSE-2.0)
- [日本語参考訳](https://licenses.opensource.jp/Apache-2.0/Apache-2.0.html)
