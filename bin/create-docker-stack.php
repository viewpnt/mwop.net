#!/usr/bin/env php
<?php
/**
 * Generate the docker-stack.yml file from latest images.
 *
 * Usage:
 *
 *   create-docker-stack.php -n $NGINX_VERSION -p $PHP_FPM_VERSION
 *
 * If either is listed as "latest", this script will delegate to
 * get-latest-tag.php to identify the latest tag released for that
 * container.
 *
 * @license http://opensource.org/licenses/BSD-2-Clause BSD-2-Clause
 * @copyright Copyright (c) Matthew Weier O'Phinney
 */

const REPOS = ['mwopnginx', 'mwopphp'];
const STACKFILE = 'docker-stack.yml';
const TAGSCRIPT = './bin/get-latest-tag.php';
const TEMPLATE = 'docker-stack.yml.dist';
const USER = 'mwop';

chdir(dirname(__DIR__));

if ($argc < 5) {
    fwrite(STDERR, sprintf('Missing one or more arguments.%s', str_repeat(PHP_EOL, 2)));
    usage(STDERR, $argv[0]);
    exit(1);
}

if ($argv[1] !== '-n' || $argv[3] !== '-p') {
    fwrite(STDERR, sprintf('Invalid arguments provided.%s', str_repeat(PHP_EOL, 2)));
    usage(STDERR, $argv[0]);
    exit(1);
}

$versions = [
    'mwopnginx' => $argv[2],
    'mwopphp'   => $argv[4],
];

$substitutions = [];
foreach (REPOS as $repo) {
    // Was a version provided for this repo?
    if ($versions[$repo] !== 'latest') {
        $substitutions[sprintf('{%s}', $repo)] = $versions[$repo];
        continue;
    }

    // Look up the latest tagged version for this repo
    $command = sprintf('%s %s %s', TAGSCRIPT, USER, $repo);
    exec($command, $output, $return);
    if ($return !== 0) {
        fwrite(STDERR, implode($output, PHP_EOL));
        exit($return);
    }

    $substitutions[sprintf('{%s}', $repo)] = array_shift($output);
}

$stackFile = file_get_contents(TEMPLATE);
$stackFile = str_replace(array_keys($substitutions), array_values($substitutions), $stackFile);

file_put_contents(STACKFILE, $stackFile);

function usage($stream, string $scriptName)
{
    $message = <<<'EOM'
Usage:

  %s -n <nginx version> -p <php-fpm version>

where:

  <nginx version>     Version tag of nginx container to use
  <php-fpm version>   Version tag of php-fpm container to use

Generates the docker-stack.yml file to use during deployment, using the
specified tags for the nginx and php-fpm containers.

In either case, if the string "latest" is used, this script will look up
the latest tagged version of that container and use that to generate the
docker-stack.yml file.

EOM;

    $message = sprintf($message, $scriptName);
    $message = sprintf("\n", PHP_EOL, $message);
    fwrite($stream, $message);
}
