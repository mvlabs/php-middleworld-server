<?php

require 'recipe/common.php';

// Set configurations
set('repository', 'ssh://git@git.mvlabs.it:4222/internals/php-middleworld-server.git');
set('shared_files', []);
set('shared_dirs', []);
set('writable_dirs', []);

// Configure servers
server("production", "95.110.184.84", 4222) // krk
    ->user('root')
    ->identityFile()
    ->stage('production')
    ->env('branch', 'master')
    ->env('deploy_path', '/srv/apps/php-middleworld/backend');

task(
    'deploy:vendors:docker',
    function () {
        // run composer install
        $cmd = [];
        $cmd[] = "rm -rf {{release_path}}/src/vendor";
        $cmd[] = "mkdir -p {{deploy_path}}/shared/vendor";
        $cmd[] = "mv {{deploy_path}}/shared/vendor {{release_path}}/src/";
        $cmd[] = "cd {{release_path}}/src";
        $cmd[] = "docker run --rm -v {{deploy_path}}/shared:{{deploy_path}}/shared -v $(pwd):/app composer/composer install --no-dev";
        run(implode(" && ", $cmd));

        // chown the vendor dir to the apache user
        $chown = "docker run --rm -v $(pwd):/app busybox chown -R 48:48 /app/vendor";
        run("cd {{release_path}}/src && $chown && cp -r {{release_path}}/src/vendor {{deploy_path}}/shared/vendor");
    }
)->desc('Installing vendors');

task('cleanup:docker', function () {
    $releases = env('releases_list');

    $keep = get('keep_releases');

    while ($keep > 0) {
        array_shift($releases);
        --$keep;
    }

    foreach ($releases as $release) {
        run("docker run --rm -v {{deploy_path}}:/app busybox rm -rf /app/releases/$release");
    }

    run("cd {{deploy_path}} && if [ -e release ]; then rm release; fi");
    run("cd {{deploy_path}} && if [ -h release ]; then rm release; fi");

})->desc('Cleaning up old releases');

/**
 * Main task
 */
task('deploy', [
    'deploy:prepare',
    'deploy:release',
    'deploy:update_code',
    'deploy:vendors:docker',
    'deploy:shared',
    'deploy:writable',
    'deploy:symlink',
    'cleanup:docker',
])->desc('Deploy your project');

after('deploy', 'success');
