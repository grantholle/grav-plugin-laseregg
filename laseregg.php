<?php
namespace Grav\Plugin;

use Grav\Common\Plugin;
use RocketTheme\Toolbox\Event\Event;
use Grav\Common\GPM\Response;

/**
 * Class LasereggPlugin
 * @package Grav\Plugin
 */
class LasereggPlugin extends Plugin
{
    protected $key;
    protected $device;

    /**
     * @return array
     *
     * The getSubscribedEvents() gives the core a list of events
     *     that the plugin wants to listen to. The key of each
     *     array section is the event that the plugin listens to
     *     and the value (in the form of an array) contains the
     *     callable (or function) as well as the priority. The
     *     higher the number the higher the priority.
     */
    public static function getSubscribedEvents()
    {
        return [
            'onPluginsInitialized' => ['onPluginsInitialized', 0]
        ];
    }

    /**
     * Initialize the plugin
     */
    public function onPluginsInitialized()
    {
        // Don't proceed if we are in the admin plugin
        if ($this->isAdmin()) {
            return;
        }

        $uri = $this->grav['uri'];
        $route = $this->config->get('plugins.laseregg.route');
        $this->key = $uri->query('key');
        $this->device = $uri->query('device');

        if ($route && $route == $uri->path() && $this->key && $this->device) {
            header('Content-type: application/json');

            echo Response::get("https://api.origins-china.cn/v1/lasereggs/{$this->device}?key={$this->key}");

            exit();
        }
    }

    /**
     * Proxies the laser egg api so we can use it on the front-end
     *
     * @param Event $e
     */
    public function onPageContentRaw(Event $e)
    {
        header('Content-type: application/json');

        echo Response::get("https://api.origins-china.cn/v1/lasereggs/{$this->device}?key={$this->key}");

        exit();
    }
}
