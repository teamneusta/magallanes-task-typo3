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

use TeamNeusta\Magallanes\Task\TYPO3\AbstractTypo3Task;

/**
 * Class AbstractConsoleTask
 *
 * @author Benjamin Kluge <b.kluge@neusta.de>
 * @package TeamNeusta\Magallanes\Task\TYPO3\Console
 */
abstract class AbstractConsoleTask extends AbstractTypo3Task
{

    /**
     * getConsoleCommand
     *
     * @param $consoleCommandName
     * @param array $options
     *
     * @return string
     */
    protected function getConsoleCommand(string $consoleCommandName, array $options): string
    {
        return sprintf(
            $this->getContextCommand('%s %s ', $options),
            $options['console'],
            $consoleCommandName
        );
    }

    /**
     * getOptions
     *
     * @param array $defaults
     * @return array
     */
    protected function getOptions(array $defaults): array
    {
        return parent::getOptions(array_merge(['console' => 'vendor/bin/typo3cms'], $defaults));
    }
}
