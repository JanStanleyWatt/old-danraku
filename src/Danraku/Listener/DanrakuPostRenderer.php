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

namespace Whojinn\Danraku\Listener;

use League\CommonMark\Event\DocumentRenderedEvent;
use League\CommonMark\Output\RenderedContent;
use League\Config\ConfigurationAwareInterface;
use League\Config\ConfigurationInterface;

/**
 * 基本的に、所謂インライン内で動作するように設定されている。
 */
class DanrakuPostRenderer implements ConfigurationAwareInterface
{
    private const TOP = '^(.*?)<(p|(p .*?))>';
    private const BASIC = '(?!<img|\p{Ps}|(\\\)';
    private const BOTTOM = ')';

    private const ALPHA_BET = '|([A-Za-z0-9]+?)';

    private const FOOT_NOTE_BEGIN = '<li class="footnote"';
    private const FOOT_NOTE_END = '</li>';

    private const KINSOKU_DASH = '|\p{Pd}';
    private const KINSOKU_KAKKO = '|\p{Ps}';

    private $config;

    /**
     * 基本形 '^<(p|(p .*?))>(?!<img (.*?))'
     * */
    private function setPattern(): string
    {

        $basic_pattern = self::TOP . self::BASIC . self::KINSOKU_KAKKO;

        // 以下、設定
        if ($this->config->get('danraku/ignore_alphabet')) {
            $basic_pattern .= self::ALPHA_BET;
        }
        if ($this->config->get('danraku/ignore_dash')) {
            $basic_pattern .= self::KINSOKU_DASH;
        }

        return $basic_pattern . self::BOTTOM;
    }

    public function setConfiguration(ConfigurationInterface $configuration): void
    {
        $this->config = $configuration;
    }


    public function postRender(DocumentRenderedEvent $event)
    {
        // 文を改行ごとに分割する
        $html_array = mb_split("\n", $event->getOutput()->getContent());

        $document = $event->getOutput()->getDocument();
        $pattern = $this->setPattern();

        // 設定一覧
        // $ignore_alpha = $this->config->get('danraku/ignore_alphabet');
        $ignore_footnote = $this->config->get('danraku/ignore_footnote');
        $ignore_dash = $this->config->get('danraku/ignore_dash');

        // ignore_~で設定された文字が行中または行頭にあるかどうか
        $footnote_flag = false;
        $dash_flag = false;

        // バッファ
        $replaced = "";

        // 置換したコードをバッファに追加
        foreach ($html_array as $html) {

            // 脚注があったときにはfootnote_flagを立てる
            if ($ignore_footnote && !$footnote_flag && (mb_strpos($html, self::FOOT_NOTE_BEGIN) !== false)) {
                $footnote_flag = true;
            }

            // 行頭にダッシュ記号があったときにはdash_flagを立てる
            if ($ignore_dash && (mb_ereg(self::TOP . '((\\\)\p{Pd})', $html))) {
                $dash_flag = true;
            }

            // 既に字下げ済みの行は処理を飛ばす
            if (mb_strpos($html, self::TOP . '　') !== false) {
                $replaced .= $html . "\n";
                continue;
            }

            // ignoreしない記号のエスケープ処理を行う
            if (mb_ereg(self::TOP . '(?=\\\)', $html, $match)) {

                $escape_footnote = (!$ignore_footnote && $footnote_flag);
                $escape_dash = (!$ignore_dash && $dash_flag);
                $escape_other = (!$footnote_flag && !$dash_flag);

                if ($escape_other || $escape_dash || $escape_footnote) {
                    $replaced .= mb_ereg_replace($match[0] . '(\\\)', $match[0], $html);
                    $replaced .= "\n";
                    continue;
                }
            }


            // 基本的な置換。
            if (!$footnote_flag && mb_ereg($pattern, $html, $match)) {
                $replaced .= mb_ereg_replace($pattern, $match[0] . "　", $html);
            } else {
                $replaced .= $html;
            }

            // 脚注の終わりが行内にあったらfootnote_flagを倒す
            if ($footnote_flag && (mb_strpos($html, self::FOOT_NOTE_END) !== false)) {
                $footnote_flag = false;
            }

            // dash_flagは行頭にあるときのみ立てるので、ここで問答無用に倒す
            $dash_flag = false;

            // 行末に消した改行コードを加える
            if (mb_strlen($html) > 0) {
                $replaced .= "\n";
            }
        }

        // 最後にまとめて置換
        $event->replaceOutput(new RenderedContent($document, $replaced));
    }
}
