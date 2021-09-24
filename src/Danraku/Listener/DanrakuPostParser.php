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

use League\CommonMark\Event\DocumentParsedEvent;
use League\CommonMark\Node\Inline\Text;
use League\Config\ConfigurationAwareInterface;
use League\Config\ConfigurationInterface;

class DanrakuPostParser implements ConfigurationAwareInterface
{
    private $config;

    public function setConfiguration(ConfigurationInterface $configuration): void
    {
        $this->config = $configuration;
    }

    public function postParse(DocumentParsedEvent $event)
    {
        $document = $event->getDocument();

        if ($this->config->get("danraku/spacing_yakumono")) {

            foreach ($document->iterator() as $node) {
                if ($node instanceof Text) {
                    $text = $node->getLiteral();
                    if (mb_ereg('(?m)([!?！？])(?!\p{Pe}|$)', $text, $match)) {
                        $node->setLiteral(str_replace($match[0], $match[0] . '　', $text));
                    }
                }
            }
        } // if($this->config->get("danraku/spacing_yakumono"))終端
    }
}
