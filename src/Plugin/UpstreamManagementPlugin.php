<?php

namespace PantheonSystems\UpstreamManagement\Plugin;

use Composer\Plugin\Capability\CommandProvider;
use PantheonSystems\UpstreamManagement\CommandProvider as UpstreamManagementCommandProvider;
use Composer\Plugin\Capable;
use Composer\Plugin\PluginInterface;
use Composer\Composer;
use Composer\IO\IOInterface;
use Composer\EventDispatcher\EventSubscriberInterface;
use Composer\Script\ScriptEvents;
use Composer\Script\Event;
use PantheonSystems\UpstreamManagement\UpstreamManagementTrait;

/**
 * Composer plugin to handle upstream management.
 */
class UpstreamManagementPlugin implements PluginInterface, Capable, EventSubscriberInterface
{

    use UpstreamManagementTrait;

    protected Composer $composer;
    protected IoInterface $io;

    public static function getSubscribedEvents()
    {
        return [
            ScriptEvents::POST_UPDATE_CMD => ['onPostUpdate', 100],
        ];
    }

    public function onPostUpdate(Event $event)
    {
        $composerJsonContents = file_get_contents("composer.json");
        $composerJson = json_decode($composerJsonContents, true);

        if (isset($composerJson['scripts']['upstream-require'])) {
            unset($composerJson['scripts']['upstream-require']);
        }
        if (isset($composerJson['scripts-descriptions']['upstream-require'])) {
            unset($composerJson['scripts-descriptions']['upstream-require']);
        }
        if (isset($composerJson['scripts']['update-upstream-dependencies'])) {
            unset($composerJson['scripts']['update-upstream-dependenciess']);
        }
        if (isset($composerJson['scripts-descriptions']['update-upstream-dependencies'])) {
            unset($composerJson['scripts-descriptions']['update-upstream-dependencies']);
        }

        $composerJsonContents = $this->jsonEncodePretty($composerJson);
        file_put_contents("composer.json", $composerJsonContents . PHP_EOL);
    }

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
