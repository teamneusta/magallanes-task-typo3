# Magallanes TYPO3 Tasks #

[![Build Status](https://travis-ci.org/teamneusta/magallanes-task-typo3.svg?branch=master)](https://travis-ci.org/teamneusta/magallanes-task-typo3)
[![Coverage Status](https://coveralls.io/repos/github/teamneusta/magallanes-task-typo3/badge.svg?branch=master)](https://coveralls.io/github/teamneusta/magallanes-task-typo3?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/teamneusta/magallanes-task-typo3/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/teamneusta/magallanes-task-typo3/?branch=master)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/877b9548-bbee-4a6c-8a49-63243f445266/mini.png)](https://insight.sensiolabs.com/projects/877b9548-bbee-4a6c-8a49-63243f445266)
[![Latest Stable Version](https://img.shields.io/packagist/v/teamneusta/magallanes-task-typo3.svg?label=stable)](https://packagist.org/packages/teamneusta/magallanes-task-typo3)
[![Latest Stable Version](https://img.shields.io/packagist/l/teamneusta/magallanes-task-typo3.svg?label=stable)](https://packagist.org/packages/teamneusta/magallanes-task-typo3)

### What's Magallanes TYPO3 Tasks? ###

**Magallanes TYPO3 Tasks** are tasks for easy deployment with Magallanes 3.

### Installing ###

Simply add the following dependency to your project’s composer.json file:

```json
    "require": {
        "teamneusta/magallanes-task-typo3": "^1.2"
    }
```
Finally you can use **Magallanes TYPO3 Tasks** in your mage.yml


## Tasks ##

### Permission task ###

This task set all necessary permission for TYPO3

```yaml
   post-release:
       - 'TeamNeusta\Magallanes\Task\TYPO3\PermissionsTask'
```

### TYPO3 console tasks ###

Set path to console:

```yaml
    typo3:
        console: vendor/helhum/typo3-console/Scripts/typo3cms
```

#### TYPO3 cache flush task ####

This task flushed the TYPO3 cache by [helhum/typo3-console](https://github.com/helhum/typo3_console)

Default usage:
```yaml
    on-deploy:
        - 'TeamNeusta\Magallanes\Task\TYPO3\Console\CacheFlushTask'
```

Force flush by inline definition:
```yaml
    on-deploy:
        - 'TeamNeusta\Magallanes\Task\TYPO3\Console\CacheFlushTask': { force-flush-cache: true }
```

Force flush by global definition:
```yaml
    typo3:
        force-flush-cache: true
    on-deploy:
        - 'TeamNeusta\Magallanes\Task\TYPO3\Console\CacheFlushTask'
```

#### TYPO3 database update schema task ####

This task update the database schema for TYPO3 by [helhum/typo3-console](https://github.com/helhum/typo3_console)

Default usage (\*.add,\*.change):
```yaml
    on-deploy:
        - 'TeamNeusta\Magallanes\Task\TYPO3\Console\DatabaseUpdateSchemaTask'
```

Update database schema by inline definition:
```yaml
    on-deploy:
        - 'TeamNeusta\Magallanes\Task\TYPO3\Console\DatabaseUpdateSchemaTask': { database-update-schema-mode: 'destructive' }
```

Update database schema by global definition:
```yaml
    typo3:
        database-update-schema-mode: 'destructive'
    on-deploy:
        - 'TeamNeusta\Magallanes\Task\TYPO3\Console\DatabaseUpdateSchemaTask'
```

#### TYPO3 install generatepackagestates task (deprecated) ####
New way to use setup your TYPO3 composer.json with follow scripts
```json
"scripts": {
    "package-states": [
        "@php vendor/helhum/typo3-console/Scripts/typo3cms install:generatepackagestates"
    ],
    "folder-structure": [
        "@php vendor/helhum/typo3-console/Scripts/typo3cms install:fixfolderstructure"
    ],
    "ext-setup": [
        "@php vendor/helhum/typo3-console/Scripts/typo3cms install:extensionsetupifpossible"

    ],
    "post-autoload-dump": [
        "@package-states",
        "@folder-structure",
        "@ext-setup"
    ]
}
```

Old way to use Default usage (--activate-default=true):
```yaml
    on-deploy:
        - 'TeamNeusta\Magallanes\Task\TYPO3\Console\InstallGeneratePackagestatesTask'
```

#### TYPO3 install fixfolderstructure task ####

Default usage:
```yaml
    on-deploy:
        - 'TeamNeusta\Magallanes\Task\TYPO3\Console\InstallFixFolderStructureTask'
```

#### TYPO3 install extension setupactive task ####

Default usage:
```yaml
    post-release:
        - 'TeamNeusta\Magallanes\Task\TYPO3\Console\ExtensionSetupActiveTask'
```

#### Example ####

```yaml
magephp:
    log_dir: ./Logs
    composer:
        path: /usr/bin/composer
    typo3:
        console: bin/typo3cms
        force-flush-cache: true
        database-update-schema-mode: '*.add,*.change'
        web-dir: web
    exclude:
        - ./app/typo3temp
        - ./app/fileadmin
        - ./app/uploads
    environments:
        Production:
            user: xxx
            host_path: xxx
            releases: 4
            hosts:
                - xxx
            pre-deploy:
                - composer/install: { flags: '--optimize-autoloader --no-dev --no-interaction --profile' }
            on-deploy:
            on-release:
                - 'TeamNeusta\Magallanes\Task\TYPO3\Console\InstallFixFolderStructureTask'
            post-release:
                - 'TeamNeusta\Magallanes\Task\TYPO3\Console\DatabaseUpdateSchemaTask'
                - 'TeamNeusta\Magallanes\Task\TYPO3\Console\ExtensionSetupActiveTask'
            post-deploy:

```
