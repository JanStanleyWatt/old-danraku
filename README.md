# Danraku
自動で段落の頭に全角スペースを入れてくれる[League/CommonMark](https://commonmark.thephpleague.com/)拡張機能。

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
echo $converter->convertToHtml($markdown);
```

## 設定
```php
// 以下、デフォルトでの設定
$config = [
    'danraku' => [
        'ignore_alphabet' => false, // trueにすると、行頭が英数字だった場合には字下げをしなくなる
        'ignore_footnote' => true,  // trueにすると、FootnoteExtension使用時に脚注には字下げをしなくなる
        'ignore_dash' => true,      // trueにすると、全角ダッシュ（―）、ハイフンで字下げをしなくなる
    ],
];
```

## ライセンス
Apache License, Version 2.0  
- [英語原文](https://www.apache.org/licenses/LICENSE-2.0)
- [日本語参考訳](https://licenses.opensource.jp/Apache-2.0/Apache-2.0.html)