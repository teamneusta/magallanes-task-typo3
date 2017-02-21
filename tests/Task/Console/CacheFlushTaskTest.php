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

namespace TeamNeusta\Magallanes\Tests\Task\TYPO3\Console;

use Mage\Command\AbstractCommand;
use Mage\Command\BuiltIn\DeployCommand;
use Mage\Tests\MageApplicationMockup;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * Class CacheFlushTaskTest
 *
 * @author Benjamin Kluge <b.kluge@neusta.de>
 * @package TeamNeusta\Magallanes\Tests\Task\TYPO3\Console
 */
class CacheFlushTaskTest extends TestCase
{

    /**
     * consoleCacheFlushFlagsDataProvider
     *
     * @return array
     */
    public function consoleCacheFlushFlagsDataProvider(): array
    {
        return [
            'cache flush default'                   => [
                'yaml'     => 'consoleCacheFlushDefault.yml',
                'expected' => [
                    'rsync -e "ssh -p 22 -q -o UserKnownHostsFile=/dev/null -o StrictHostKeyChecking=no" -avz --exclude=.git --exclude=./var/cache/* --exclude=./var/log/* --exclude=./web/app_dev.php ./ tester@testhost:/var/www/test',
                    'ssh -p 22 -q -o UserKnownHostsFile=/dev/null -o StrictHostKeyChecking=no tester@testhost "cd /var/www/test && vendor/bin/typo3cms cache:flush"'
                ]
            ],
            'cache flush force'                     => [
                'yaml'     => 'consoleCacheFlushForce.yml',
                'expected' => [
                    'rsync -e "ssh -p 22 -q -o UserKnownHostsFile=/dev/null -o StrictHostKeyChecking=no" -avz --exclude=.git --exclude=./var/cache/* --exclude=./var/log/* --exclude=./web/app_dev.php ./ tester@testhost:/var/www/test',
                    'ssh -p 22 -q -o UserKnownHostsFile=/dev/null -o StrictHostKeyChecking=no tester@testhost "cd /var/www/test && vendor/bin/typo3cms cache:flush true"'
                ]
            ],
            'cache flush custom console path'       => [
                'yaml'     => 'consoleCacheFlushCustomConsolePathGlobal.yml',
                'expected' => [
                    'rsync -e "ssh -p 22 -q -o UserKnownHostsFile=/dev/null -o StrictHostKeyChecking=no" -avz --exclude=.git --exclude=./var/cache/* --exclude=./var/log/* --exclude=./web/app_dev.php ./ tester@testhost:/var/www/test',
                    'ssh -p 22 -q -o UserKnownHostsFile=/dev/null -o StrictHostKeyChecking=no tester@testhost "cd /var/www/test && vendor/helhum/typo3-console/Scripts/typo3cms cache:flush"'
                ]
            ],
            'cache flush with custom TYPO3 context' => [
                'yaml'     => 'consoleCacheFlushWithTYPO3Context.yml',
                'expected' => [
                    'rsync -e "ssh -p 22 -q -o UserKnownHostsFile=/dev/null -o StrictHostKeyChecking=no" -avz --exclude=.git --exclude=./var/cache/* --exclude=./var/log/* --exclude=./web/app_dev.php ./ tester@testhost:/var/www/test',
                    'ssh -p 22 -q -o UserKnownHostsFile=/dev/null -o StrictHostKeyChecking=no tester@testhost "cd /var/www/test && export TYPO3_CONTEXT=\"Production/Prod\" && vendor/bin/typo3cms cache:flush"'
                ]
            ]
        ];
    }

    /**
     * testConsoleCacheFlushFlags
     *
     * @dataProvider consoleCacheFlushFlagsDataProvider
     * @test
     * @param string $ymlFile
     * @param array $expectedCommands
     * @return void
     */
    public function testConsoleCacheFlushFlags($ymlFile, array $expectedCommands)
    {
        $application = new MageApplicationMockup(__DIR__.'/../../Resources/'.$ymlFile);

        /** @var AbstractCommand $command */
        $command = $application->find('deploy');
        $this->assertTrue($command instanceof DeployCommand);

        $tester = new CommandTester($command);
        $tester->execute(['command' => $command->getName(), 'environment' => 'test']);

        $ranCommands = $application->getRuntime()->getRanCommands();

        // Check Generated Commands
        $begin = null;
        foreach ($expectedCommands as $command) {
            $begin = is_null($begin) ? array_search($command, $ranCommands) : $begin;
            $this->assertEquals($command, $ranCommands[$begin]);
            $begin++;
        }

        $this->assertEquals(0, $tester->getStatusCode());
    }
}