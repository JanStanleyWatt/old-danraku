# Danraku
自動で段落の頭に全角スペースを入れてくれるLeague/CommonMark拡張機能。

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

## config
```php
$config = [
    'danraku' => [
        'ignore_alphabet' => false, // trueにすると、行頭が英単語だった場合には全角スペースを入れなくなる
    ],
];
```