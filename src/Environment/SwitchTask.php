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

namespace TeamNeusta\Magallanes\Task\TYPO3\Environment;

use Mage\Runtime\Exception\RuntimeException;
use Mage\Task\BuiltIn\FS\CopyTask;
use Mage\Task\BuiltIn\FS\MoveTask;
use Psr\Log\LogLevel;
use TeamNeusta\Magallanes\Task\TYPO3\AbstractTypo3Task;

/**
 * Class SwitchTask
 *
 * @author Benjamin Kluge <b.kluge@neusta.de>
 * @package TeamNeusta\Magallanes\Task\TYPO3
 */
class SwitchTask extends AbstractTypo3Task
{
    /**
     * name
     *
     * @var string
     */
    protected $name = 'typo3-environment-switch';

    /**
     * description
     *
     * @var string
     */
    protected $description = '[TYPO3] TYPO3 switch environment Localconfiguration.php';


    /**
     * execute
     * @return bool
     * @throws RuntimeException
     */
    public function execute(): bool
    {
        $options = $this->getOptions(['web-dir' => 'web', 'environment-folder' => './Environments']);
        $environmentFile = $options['environment-folder'].DIRECTORY_SEPARATOR.'LocalConfiguration.'.$this->runtime->getEnvironment().'.php';
        $actualConfigurationFile = $options['web-dir'].DIRECTORY_SEPARATOR.'typo3conf'.DIRECTORY_SEPARATOR.'LocalConfiguration.php';
        $actualConfigurationFileTo = $options['environment-folder'].DIRECTORY_SEPARATOR.'LocalConfiguration.php';
        if ($this->runtime->getStage() !== 'post-deploy') {
            if (file_exists($environmentFile)) {
                $this->moveFile($actualConfigurationFile, $actualConfigurationFileTo);
                $this->setPostDeployTask();
                $this->copyFile($environmentFile, $actualConfigurationFile);
            } else {
                // throw new RuntimeException('Environment not exist: ' . $environmentFile);
                $this->runtime->log('Environment not exist: ' . $environmentFile, LogLevel::ERROR);
                return false;
            }
        } else {
            $this->moveFile($actualConfigurationFileTo, $actualConfigurationFile);
        }

        return true;
    }

    /**
     * moveFile
     *
     * @param string $from
     * @param string $to
     * @return void
     */
    protected function moveFile(string $from, string $to)
    {
        if (file_exists($from)) {
            $moveTaks = new MoveTask();
            $moveTaks->setRuntime($this->runtime);
            $moveTaks->setOptions($this->getOptions(['from' => $from, 'to' => $to]));
            $moveTaks->execute();
        }
    }

    /**
     * copyFile
     *
     * @param string $from
     * @param string $to
     * @return void
     */
    protected function copyFile(string $from, string $to)
    {
        if (file_exists($from)) {
            $copyTaks = new CopyTask();
            $copyTaks->setRuntime($this->runtime);
            $copyTaks->setOptions($this->getOptions(['from' => $from, 'to' => $to]));
            $copyTaks->execute();
        }
    }

    /**
     * setPostDeployTask
     *
     * @return void
     */
    protected function setPostDeployTask()
    {
        $configuration = $this->runtime->getConfiguration();
        $configuration['environments'][$this->runtime->getEnvironment()]['post-deploy'][] = self::class;
        $this->runtime->setConfiguration($configuration);
    }
}
