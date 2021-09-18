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

namespace Whojinn\Danraku;

use League\CommonMark\Environment\EnvironmentBuilderInterface;
use League\CommonMark\Event\DocumentRenderedEvent;
use League\CommonMark\Extension\ConfigurableExtensionInterface;
use League\Config\ConfigurationBuilderInterface;
use Nette\Schema\Expect;
use Whojinn\Danraku\Listener\DanrakuPostRenderer;

class DanrakuExtension implements ConfigurableExtensionInterface
{
    public function configureSchema(ConfigurationBuilderInterface $builder): void
    {
        $builder->addSchema(
            'danraku',
            Expect::structure([
                // trueにすると、行頭が英単語だった場合には全角スペースを入れなくなる
                'ignore_alphabet' => Expect::bool()->default(false),

                // trueにすると、脚注には全角スペースを入れなくなる
                'ignore_footnote' => Expect::bool()->default(true),
            ])
        );
    }

    public function register(EnvironmentBuilderInterface $environment): void
    {
        // Danraku独自のコード
        $environment
            ->addEventListener(DocumentRenderedEvent::class, [new DanrakuPostRenderer(), 'postRender']);
    }
}
