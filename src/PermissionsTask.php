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

/**
 * Class PermissionsTask
 *
 * @author Benjamin Kluge <b.kluge@neusta.de>
 * @package TeamNeusta\Magallanes\Task\TYPO3
 */
class PermissionsTask extends AbstractTypo3Task
{
    /**
     * name
     *
     * @var string
     */
    protected $name = 'permissions';

    /**
     * description
     *
     * @var string
     */
    protected $description = '[TYPO3] TYPO3 permissions task';

    /**
     * permissions
     *
     * @var array
     */
    protected $permissions = [
        '/typo3conf/PackageStates.php'      => 2775,
        '/typo3conf/LocalConfiguration.php' => 2775,
    ];

    /**
     * execute
     *
     * @return bool
     */
    public function execute(): bool
    {
        $options = $this->getOptions(['web-dir' => 'web']);
        $process = null;
        foreach ($this->permissions as $path => $permission) {
            $command = sprintf('chmod -f %s "%s"', $permission, $options['web-dir'].$path);
            $process = $this->executeCommand($command);
        }

        return $process;
    }
}
