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
 * Class DatabaseUpdateSchemaTask
 *
 * @author Benjamin Kluge <b.kluge@neusta.de>
 * @package TeamNeusta\Magallanes\Task\TYPO3\Console
 */
class DatabaseUpdateSchemaTask extends AbstractConsoleTask
{
    /**
     * getName
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->namePrefix.'console-database-update-schema';
    }

    /**
     * getDescription
     *
     * @return string
     */
    public function getDescription(): string
    {
        return '[TYPO3] TYPO3 console update schema task';
    }

    /**
     * execute
     *
     * @return bool
     */
    public function execute(): bool
    {
        $options = $this->getOptions(
            [
                'database-update-schema-mode' => '*.add,*.change'
            ]
        );
        $command = sprintf(
            $this->getConsoleCommand('database:updateschema', $options).'"%s"',
            $options['database-update-schema-mode']
        );

        return $this->executeCommand($command);
    }
}
