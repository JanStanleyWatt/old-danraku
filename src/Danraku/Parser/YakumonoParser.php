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

namespace Whojinn\Danraku\Parser;

use League\CommonMark\Node\Inline\Text;
use League\CommonMark\Parser\Inline\InlineParserInterface;
use League\CommonMark\Parser\Inline\InlineParserMatch;
use League\CommonMark\Parser\InlineParserContext;
use League\Config\ConfigurationAwareInterface;
use League\Config\ConfigurationInterface;

class YakumonoParser implements InlineParserInterface, ConfigurationAwareInterface
{
    private $config;

    public function setConfiguration(ConfigurationInterface $configuration): void
    {
        $this->config = $configuration;
    }

    public function getMatchDefinition(): InlineParserMatch
    {
        return InlineParserMatch::oneOf('?', '!', '？', '！');
    }

    public function parse(InlineParserContext $inline_context): bool
    {
        // 設定でオフになっていたらその時点でfalse
        if (!$this->config->get('danraku/spacing_yakumono')) {
            return false;
        }

        $cursor = $inline_context->getCursor();
        $now_char = $cursor->getCurrentCharacter();
        $next_char = $cursor->peek();
        $is_null = ($now_char === null || $next_char === null);

        // 飛ばす必要が無い、または飛ばせない場合はfalse
        if ($is_null || mb_ereg('\p{Pe}|\n|\s', $next_char)) {
            return false;
        }

        $cursor->advance();
        $inline_context->getContainer()->appendChild(new Text($now_char . '　'));
        return true;
    }
}
