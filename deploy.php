<?php

// Run build step:   bin/composer build
// Deploy on DEV:    bin/php vendor/bin/dep deploy
// Deploy on BAMBOO: bin/php vendor/bin/dep -vv deploy s2-staging

namespace Deployer;

require 'vendor/shopping/deployer-recipes/recipe/c24symfony4.php';

// Needed global variables
set('app_name', 'ek-csv-checker');
set('unix_user', get('app_name'));

// OPTIONAL: de-/activate needed features
set('feature_parameters', true);
set('feature_migration', true);   // needs role 'migration'
//set('feature_fluent_bit', true);

// Import server roles (managed by IT-OPS)
loadInventory(get('unix_user'));

// Define local environment for DEV (vagrant)
localhost()
    ->set('unix_user', 'vagrant')
    ->set('labels', ['roles' => ['nginx', 'php-fpm', 'migration', 'supervisor'], 'stage' => getDefaultStage()]);

// OPTIONAL: define custom configuration files
task('configuration:custom', function () {
    // render the config file
    twig_render(
        'etc/special/special.yaml.twig',
        'etc/special/special.yaml',
        'special',
        // OPTIONAL: if needed, you can add dynamic values
        array(
            'deploy_date' => date(DATE_RFC822)
        ),
        INI_SCANNER_RAW
    );
});
