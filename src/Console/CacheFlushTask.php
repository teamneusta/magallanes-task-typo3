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
 * Class CacheFlushTask
 *
 * @author Benjamin Kluge <b.kluge@neusta.de>
 * @package TeamNeusta\Magallanes\Task\TYPO3\Console
 */
class CacheFlushTask extends AbstractConsoleTask
{
    /**
     * name
     *
     * @var string
     */
    protected $name = 'console-cache-flush';

    /**
     * description
     *
     * @var string
     */
    protected $description = '[TYPO3] TYPO3 console cache task';

    /**
     * execute
     *
     * @return bool
     */
    public function execute(): bool
    {
        $options = $this->getOptions(
            [
                'force-flush-cache' => null
            ]
        );
        $command = sprintf(
            $this->getConsoleCommand('cache:flush', $options).'%s',
            !empty($options['force-flush-cache']) ? '--force' : ''
        );

        return $this->executeCommand($command);
    }
}
