<?php

declare(strict_types=1);

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
    const TOP = '<p>(?!(';
    const BASIC = '(<h[1-9]>)|(<li>)|(<ol>)|(<img (.*?)>)|(<th>)|(<td>)';
    const BOTTOM = '))';
    private $config;

    private function setPattern(): string
    {
        // 基本形
        $basic_pattern = '(<h[1-9]>)|(<li>)|(<ol>)|(<img (.*?)>)|(<th>)|(<td>)';


        // 設定の羅列
        $ignore_alpha = $this->config->get('danraku/ignore_alphabet');

        // 以下、設定
        if ($ignore_alpha) {
            $basic_pattern .= '|([[:alpha:]]+?)';
        }

        return $basic_pattern;
    }

    public function setConfiguration(ConfigurationInterface $configuration): void
    {
        $this->config = $configuration;
    }

    public function postRender(DocumentRenderedEvent $event)
    {
        $html = $event->getOutput()->getContent();
        $document = $event->getOutput()->getDocument();
        $pattern = self::TOP . $this->setPattern() . self::BOTTOM;

        // バッファ
        $replaced = "";

        // 置換したコードをバッファに追加
        $replaced .= mb_ereg_replace($pattern, "<p>　", $html);

        // 最後にまとめて置換
        $event->replaceOutput(new RenderedContent($document, $replaced));
    }
}
