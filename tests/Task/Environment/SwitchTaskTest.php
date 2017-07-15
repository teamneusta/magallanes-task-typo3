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

namespace TeamNeusta\Magallanes\Tests\Task\TYPO3\Environment;

use Mage\Command\AbstractCommand;
use Mage\Command\BuiltIn\DeployCommand;
use Mage\Tests\MageApplicationMockup;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * Class SwitchTaskTest
 *
 * @author Benjamin Kluge <b.kluge@neusta.de>
 * @package TeamNeusta\Magallanes\Tests\Task\TYPO3\Environment
 */
class SwitchTaskTest extends TestCase
{

    /**
     * switchEnvironmentDataProvider
     *
     * @return array
     */
    public function switchEnvironmentDataProvider(): array
    {
        return [
            'switch environment on pre deploy' => [
                'yaml' => 'switchEnvironmentPreDeploy.yml',
                'expected' => [
                    'mv  "/tmp/typo3conf/LocalConfiguration.php" "/tmp/LocalConfiguration.php"',
                    'cp -p "/tmp/LocalConfiguration.test.php" "/tmp/typo3conf/LocalConfiguration.php"',
                    'rsync -e "ssh -p 22 -q -o UserKnownHostsFile=/dev/null -o StrictHostKeyChecking=no" -avz --exclude=.git --exclude=./var/cache/* --exclude=./var/log/* --exclude=./web/app_dev.php ./ tester@testhost:/var/www/test',
                    'mv  "/tmp/LocalConfiguration.php" "/tmp/typo3conf/LocalConfiguration.php"',
                ]
            ]
        ];
    }

    /**
     * testPermissionsFlags
     *
     * @dataProvider switchEnvironmentDataProvider
     * @test
     * @param string $ymlFile
     * @param array $expectedCommands
     * @return void
     */
    public function testSwitchEnvironmentSuccess($ymlFile, array $expectedCommands)
    {
        $application = new MageApplicationMockup(__DIR__ . '/../../Resources/' . $ymlFile);
        copy(__DIR__ . '/../../Fixtures/LocalConfiguration.test.php', '/tmp/LocalConfiguration.test.php');
        copy(__DIR__ . '/../../Fixtures/LocalConfiguration.test.php', '/tmp/LocalConfiguration.php');
        if (!is_dir('/tmp/typo3conf')) {
            mkdir('/tmp/typo3conf', 0777, true);
        }
        copy(__DIR__ . '/../../Fixtures/LocalConfiguration.test.php', '/tmp/typo3conf/LocalConfiguration.php');

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
            if (!empty($ranCommands[$begin])) {
                $this->assertEquals($command, $ranCommands[$begin]);
            }
            $begin++;
        }

        $this->assertEquals(0, $tester->getStatusCode());
    }

    /**
     * testSwitchEnvironmentFail
     *
     * @dataProvider switchEnvironmentDataProvider
     * @test
     * @param string $ymlFile
     * @param array $expectedCommands
     * @return void
     */
    public function testSwitchEnvironmentFail($ymlFile, array $expectedCommands)
    {
        $application = new MageApplicationMockup(__DIR__ . '/../../Resources/' . $ymlFile);
        if (file_exists('/tmp/LocalConfiguration.test.php')) {
            unlink('/tmp/LocalConfiguration.test.php');
        }

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
            if (!empty($ranCommands[$begin])) {
                $this->assertEquals($command, $ranCommands[$begin]);
            }
            $begin++;
        }

        $this->assertEquals(7, $tester->getStatusCode());
    }
}
