<?php

namespace PantheonSystems\UpstreamManagement\Plugin;

use Composer\Plugin\Capability\CommandProvider;
use PantheonSystems\UpstreamManagement\CommandProvider as UpstreamManagementCommandProvider;
use Composer\Plugin\Capable;
use Composer\Plugin\PluginInterface;
use Composer\Composer;
use Composer\IO\IOInterface;

/**
 * Composer plugin to handle upstream management.
 */
class UpstreamManagementPlugin implements PluginInterface, Capable
{

    protected Composer $composer;
    protected IoInterface $io;

    public function activate(Composer $composer, IOInterface $io)
    {
        $this->composer = $composer;
        $this->io = $io;
    }

    public function deactivate(Composer $composer, IOInterface $io)
    {
    }

    public function uninstall(Composer $composer, IOInterface $io)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function getCapabilities()
    {
        return [CommandProvider::class => UpstreamManagementCommandProvider::class];
    }
}
