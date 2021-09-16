<?php

namespace Whojinn\Test;

require __DIR__ . '/../vendor/autoload.php';

use League\CommonMark\Environment\Environment;
use League\CommonMark\Extension\Attributes\AttributesExtension;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\Extension\GithubFlavoredMarkdownExtension;
use League\CommonMark\MarkdownConverter;
use PHPUnit\Framework\TestCase;
use Whojinn\Danraku\DanrakuExtension;

use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertFileEquals;
use function PHPUnit\Framework\assertFileExists;
use function PHPUnit\Framework\assertSame;
use function PHPUnit\Framework\assertStringContainsString;
use function PHPUnit\Framework\assertStringEqualsFile;

final class DanrakuTest extends TestCase
{
    public array $config;

    public $environment;

    /**
     * テストに必要な前処理を共通化させる。
     * 特に断りが無い場合、導入する拡張機能はCommonMarkとGFMのみとする
     */
    private function testTemplate(string $markdown_path, string $otehon_path): array
    {

        $template_path = __DIR__ . '/data/';

        $this->environment->addExtension(new CommonMarkCoreExtension())
            ->addExtension(new GithubFlavoredMarkdownExtension())
            ->addExtension(new DanrakuExtension());


        $converter = new MarkdownConverter($this->environment);

        $test = $converter->convertToHtml(file_get_contents($template_path . $markdown_path));

        return [
            "markdown" => $test,
            "otehon" => file_get_contents($template_path . $otehon_path),
        ];
    }

    protected function setUp(): void
    {
        $this->config = [
            'danraku' => [
                'ignore_alphabet' => false,
            ]
        ];

        clearstatcache();

        $this->environment = new Environment($this->config);
    }

    final public function testDanrakuNormal(): void
    {
        $test_data = $this->testTemplate('paragraph.md', 'paragraph.html');

        // assertFileEquals($test_data["otehon"], $test_data["markdown"], "基本テストがうまくいかなかったでござる");
        assertEquals($test_data["otehon"], $test_data["markdown"], "基本テストがうまくいかなかったでござる");
    }

    final public function testDanrakuAttribute(): void
    {
        $test_data = $this->testTemplate('attribute.md', 'attribute.html');
        $this->environment->addExtension(new AttributesExtension());

        assertEquals($test_data["otehon"], $test_data["markdown"], "属性テストがうまくいかなかったでござる");
    }
}
