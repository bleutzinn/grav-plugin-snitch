<?php
namespace Grav\Plugin;

use Grav\Common\Grav;
use Grav\Common\Plugin;
//use Grav\Common\User\Interfaces\UserCollectionInterface;
//use Grav\Common\User\DataUser\UserCollection;
use Grav\Common\Utils;
use RocketTheme\Toolbox\Event\Event;
use Symfony\Component\Yaml\Yaml;

/**
 * Class SnitchPlugin
 * @package Grav\Plugin
 */
class SnitchPlugin extends Plugin
{
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

        // Enable the main event we are interested in
        $this->enable([
            'onPagesInitialized' => ['onPagesInitialized', 0]
        ]);
    }

    /**
     * 
     *
     * @param Event $e
     */
    public function onPagesInitialized(Event $e)
    {

        //$twig = $this->grav['twig'];
        $twig = Grav::instance()['twig'];
        
        $file = $this->grav['locator']->findResource('config://groups' . YAML_EXT, true, true);
        $groups = Yaml::parse(file_get_contents($file));
        
        dump($groups);
        $twig->twig_vars['groups'] = $groups;
        
        $accounts = [];

        // Fields for which the value must be kept hidden and get replaced
        $fields = ['hashed_password' => ''];

        // Process all YAML files
        $account_dir = $this->grav['locator']->findResource('account://');
        $files = $account_dir ? array_diff(scandir($account_dir), ['.', '..']) : [];

        foreach ($files as $file) {
            if (Utils::endsWith($file, YAML_EXT)) {
                // Get content of YAML file as a parsed array
                $account = Yaml::parse($this->getContents($account_dir . DS . $file));

                // Add username
                $username = trim(pathinfo($file, PATHINFO_FILENAME));
                Utils::setDotNotation($account, 'username', $username, $merge = false);

                // Mask 
                foreach ($fields as $field => $value) {
                    Utils::setDotNotation($account, 'hashed_password', $value, $merge = false);

                }
                $accounts[$username] = $account;
            }
        }
        dump($accounts);

        $twig->twig_vars['accounts'] = $accounts;

        dump($twig->twig_vars);
        
    }


    private function getContents($fn) {
        if (strpos($fn, '://') !== false ){
            $path = $this->grav['locator']->findResource($fn, true);
        } else {
            $path = $fn;
        }
        if (file_exists($path)) {
            return file_get_contents($path);
        }
        return null;
    }

}
