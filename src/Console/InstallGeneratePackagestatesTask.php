<?php
/**
 * This file is part of the teamneusta/magallanes-task-typo3 package.
 *
 * Copyright (c) 2017 neusta GmbH | Ein team neusta Unternehmen
 *
 * For the full copyright and license information, please view the LICENSE file that was distributed with this source code.
 *
 * @license http://www.opensource.org/licenses/mit-license.html  MIT License
 */
declare(strict_types=1);

namespace TeamNeusta\Magallanes\Task\TYPO3\Console;

/**
 * Class GeneratepackagestatesTask
 *
 * @deprecated
 * @author Benjamin Kluge <b.kluge@neusta.de>
 * @package TeamNeusta\Magallanes\Task\TYPO3\Console
 */
class InstallGeneratePackagestatesTask extends AbstractConsoleTask
{
    /**
     * name
     *
     * @var string
     */
    protected $name = 'console-install-generate-packagestates';

    /**
     * description
     *
     * @var string
     */
    protected $description = '[TYPO3] TYPO3 console install generate packagestates task';

    /**
     * execute
     *
     * @return bool
     */
    public function execute(): bool
    {
        $options = $this->getOptions(
            [
                'flags' => '--activate-default=true'
            ]
        );
        $command = sprintf(
            $this->getConsoleCommand('install:generatepackagestates', $options) . '%s',
            $options['flags']
        );

        return $this->executeCommand($command);
    }
}
