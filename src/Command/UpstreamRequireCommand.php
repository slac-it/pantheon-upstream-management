<?php

namespace PantheonSystems\UpstreamManagement\Command;

use Composer\Command\BaseCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use PantheonSystems\UpstreamManagement\UpstreamManagementTrait;

/**
 * The "upstream:require" command.
 */
class UpstreamRequireCommand extends BaseCommand {

    use UpstreamManagementTrait;

    /**
     * {@inheritdoc}
     */
    protected function configure() {
        $this
            ->setName('upstream:require')
            ->setAliases(['upstream-require'])
            ->setDescription('Require a new package to be added to the upstream.')
            ->setHelp('The <info>upstream:require</info> command adds a new package to the upstream.');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output) {
        $output->writeln('Hello World');
        $io = $this->getIO();
        $composer = $this->getComposer();
        $arguments = $this->getArguments();

        // This command can only be used in custom upstreams
        $this->failUnlessIsCustomUpstream($io, $composer);
        $hasNoUpdate = array_search('--no-update', $arguments) !== false;
        // Remove --working-dir, --no-update and --no-install, if provided
        $arguments = array_filter($arguments, function ($item) {
        return
            (substr($item, 0, 13) != '--working-dir') &&
            ($item != '--no-update') &&
            ($item != '--no-install');
        });
        // Escape the arguments passed in.
        $args = array_map(function ($item) {
            return escapeshellarg($item);
        }, $arguments);

        // Run `require` with '--no-update' if there is no composer.lock file,
        // and without it if there is.
        // @todo Path!?
        $addNoUpdate = $hasNoUpdate || !file_exists('upstream-configuration/composer.lock');

        if ($addNoUpdate) {
            $args[] = '--no-update';
        }
        else {
            $args[] = '--no-install';
        }

        // Insert the new projects into the upstream-configuration composer.json
        // without writing vendor & etc to the upstream-configuration directory.
        // @todo Path!?
        $cmd = "composer --working-dir=upstream-configuration require " . implode(' ', $args);
        $io->writeError($cmd . PHP_EOL);
        passthru($cmd, $statusCode);

        if ($statusCode) {
            throw new \RuntimeException("Could not add dependency to upstream.");
        }

        $io->writeError('upstream-configuration/composer.json updated. Commit the upstream-configuration/composer.lock file if you wish to lock your upstream dependency versions in sites created from this upstream.');
        return $statusCode;
    }

}