<?php

/**
 * @var \Codeception\Scenario $scenario
 */

use cweagans\Composer\Tests\AcceptanceTester;

$I = new AcceptanceTester($scenario);

$I->wantTo('apply patches only from a lock file if present');
$I->amInPath(codecept_data_dir('fixtures/apply-patch-from-lock-file'));
$I->runShellCommand('composer install');
$I->canSeeFileFound('./vendor/cweagans/composer-patches-testrepo/src/OneMoreTest.php');

// Now that a lock file is generated, change the patch and make sure the old patch from the lock file is still used.

$I->runShellCommand('mv composer.json composer_old.json');
$I->runShellCommand('cp composer2.json composer.json');
$I->runShellCommand('composer install');
$I->canSeeFileFound('./vendor/cweagans/composer-patches-testrepo/src/OneMoreTest.php');
$I->cantSeeFileFound('./vendor/cweagans/composer-patches-testrepo/src/YetAnotherTest.php');

// Reset composer.json so we don't accidentally commit the new version.
$I->runShellCommand('mv composer_old.json composer.json');
