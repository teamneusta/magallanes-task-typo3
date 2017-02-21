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

namespace TeamNeusta\Magallanes\Task\TYPO3;

use Mage\Task\AbstractTask;
use Symfony\Component\Process\Process;

/**
 * Class AbstractTypo3Task
 *
 * @author Benjamin Kluge <b.kluge@neusta.de>
 * @package TeamNeusta\Magallanes\Task\TYPO3
 */
abstract class AbstractTypo3Task extends AbstractTask
{
    /**
     * namePrefix
     *
     * @var string
     */
    protected $namePrefix = 'typo3/';

    /**
     * getContextCommand
     *
     * @param $cmd
     * @param array $options
     * @return string
     */
    protected function getContextCommand($cmd, array $options): string
    {
        if (!empty($options['context'])) {
            return sprintf(
                       'export TYPO3_CONTEXT="%s" && ',
                       $options['context']
                   ).$cmd;
        }

        return $cmd;
    }

    /**
     * getOptions
     *
     * @param array $defaults
     * @return array
     */
    protected function getOptions(array $defaults): array
    {
        $userGlobalOptions = $this->runtime->getConfigOption('typo3', []);
        $userEnvOptions = $this->runtime->getEnvOption('typo3', []);
        $options = array_merge(
            ['context' => null],
            $defaults,
            (is_array($userGlobalOptions) ? $userGlobalOptions : []),
            (is_array($userEnvOptions) ? $userEnvOptions : []),
            $this->options
        );

        return $options;
    }

    /**
     * executeCommand
     *
     * @param $command
     * @return bool
     */
    protected function executeCommand($command): bool
    {
        /** @var Process $process */
        $process = $this->runtime->runCommand(trim($command));

        return $process->isSuccessful();
    }
}
