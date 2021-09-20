<?php

namespace Whojinn\Test;

require __DIR__ . '/../vendor/autoload.php';

use League\CommonMark\Environment\Environment;
use League\CommonMark\Extension\Attributes\AttributesExtension;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\Extension\Footnote\FootnoteExtension;
use League\CommonMark\Extension\GithubFlavoredMarkdownExtension;
use League\CommonMark\MarkdownConverter;
use PHPUnit\Framework\TestCase;
use Whojinn\Danraku\DanrakuExtension;
use Whojinn\Sapphire\SapphireExtension;

use function PHPUnit\Framework\assertStringEqualsFile;

final class DanrakuTest extends TestCase
{
    public array $config;

    public $environment;

    /**
     * テストに必要な前処理を共通化させる。
     */
    private function testTemplate(string $markdown_path, string $otehon_path): array
    {

        $template_path = __DIR__ . '/data/';

        $this->environment->addExtension(new CommonMarkCoreExtension())
            ->addExtension(new GithubFlavoredMarkdownExtension())
            ->addExtension(new AttributesExtension())
            ->addExtension(new FootnoteExtension())
            ->addExtension(new SapphireExtension())
            ->addExtension(new DanrakuExtension());

        $converter = new MarkdownConverter($this->environment);

        $test = $converter->convertToHtml(file_get_contents($template_path . $markdown_path));

        return [
            "markdown" => $test,                        //html化させたMarkdownテキスト
            "otehon" => $template_path . $otehon_path,  //お手本ファイルのパス
        ];
    }

    protected function setUp(): void
    {
        $this->config = [
            'danraku' => [
                'ignore_alphabet' => false,
                'ignore_footnote' => true,
                'ignore_dash' => true,
            ]
        ];

        clearstatcache();

        $this->environment = new Environment($this->config);
    }

    final public function testDanrakuNormal(): void
    {
        $test_data = $this->testTemplate('paragraph.md', 'paragraph.html');

        // assertFileEquals($test_data["otehon"], $test_data["markdown"], "基本テストがうまくいかなかったでござる");
        assertStringEqualsFile($test_data["otehon"], $test_data["markdown"], "基本テストがうまくいかなかったでござる");
    }

    final public function testDanrakuAttribute(): void
    {
        $test_data = $this->testTemplate('attribute.md', 'attribute.html');


        assertStringEqualsFile($test_data["otehon"], $test_data["markdown"], "属性テストがうまくいかなかったでござる");
    }

    final public function testDanrakuIgnoreAlphabet(): void
    {
        $this->environment->mergeConfig([
            'danraku' => [
                'ignore_alphabet' => true,
            ]
        ]);

        $test_data = $this->testTemplate('ignore_alphabet.md', 'ignore_alphabet.html');

        assertStringEqualsFile($test_data["otehon"], $test_data["markdown"], "アルファベット無視機能「オン」テストがうまくいかなかったでござる");
    }

    final public function testDanrakuOffIgnoreAlphabet(): void
    {
        $this->environment->mergeConfig([
            'danraku' => [
                'ignore_alphabet' => false,
            ]
        ]);

        $test_data = $this->testTemplate('ignore_alphabet.md', 'ignore_alphabet_off.html');

        assertStringEqualsFile($test_data["otehon"], $test_data["markdown"], "アルファベット無視機能「オフ」のテストがうまくいかなかったでござる");
    }

    /**
     * @test 脚注無視機能「オン」テスト
     */
    final public function testDanrakuIgnoreFootnote(): void
    {
        $this->environment->mergeConfig([
            'danraku' => [
                'ignore_footnote' => true,
            ]
        ]);

        $test_data = $this->testTemplate('ignore_footnote.md', 'ignore_footnote.html');

        assertStringEqualsFile($test_data["otehon"], $test_data["markdown"], "脚注無視機能「オン」テストがうまくいかなかったでござる");
    }

    /**
     * @test 脚注無視機能「オフ」テスト
     */
    final public function testDanrakuOffIgnoreFootnote(): void
    {
        $this->environment->mergeConfig([
            'danraku' => [
                'ignore_footnote' => false,
            ]
        ]);

        $test_data = $this->testTemplate('ignore_footnote.md', 'ignore_footnote_off.html');

        assertStringEqualsFile($test_data["otehon"], $test_data["markdown"], "脚注無視機能「オフ」テストがうまくいかなかったでござる");
    }

    final public function testDanrakuEscape(): void
    {
        $test_data = $this->testTemplate('escape.md', 'escape.html');

        assertStringEqualsFile($test_data["otehon"], $test_data["markdown"], "エスケープテストがうまくいかなかったでござる");
    }

    final public function testDanrakuIgnoreDash(): void
    {
        $this->environment->mergeConfig([
            'danraku' => [
                'ignore_dash' => true,
            ]
        ]);

        $test_data = $this->testTemplate('ignore_dash.md', 'ignore_dash.html');

        assertStringEqualsFile($test_data["otehon"], $test_data["markdown"], "ダッシュ無視機能「オン」テストがうまくいかなかったでござる");
    }

    final public function testDanrakuOffIgnoreDash(): void
    {
        $this->environment->mergeConfig([
            'danraku' => [
                'ignore_dash' => false,
            ]
        ]);

        $test_data = $this->testTemplate('ignore_dash.md', 'ignore_dash_off.html');

        assertStringEqualsFile($test_data["otehon"], $test_data["markdown"], "ダッシュ無視機能「オフ」テストがうまくいかなかったでござる");
    }

    final public function testDanrakuIgnoreBrackets(): void
    {
        $test_data = $this->testTemplate('ignore_brackets.md', 'ignore_brackets.html');

        assertStringEqualsFile($test_data["otehon"], $test_data["markdown"], "開き括弧無視機能テストがうまくいかなかったでござる");
    }
}
