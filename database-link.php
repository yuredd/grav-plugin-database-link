<?php
namespace Grav\Plugin;

use \PDO;
use Grav\Common\Plugin;
use RocketTheme\Toolbox\Event\Event;
use RocketTheme\Toolbox\Session\Message;

/**
 * Class DatabaseLinkPlugin
 * @package Grav\Plugin
 */
class DatabaseLinkPlugin extends Plugin
{

    private static $connection = FALSE;

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
            'onPluginsInitialized' => ['onPluginsInitialized', 0],
            'onFormProcessed' => ['onFormProcessed', 0]
        ];
    }

    /**
     * Initialize the plugin
     */
    public function onPluginsInitialized()
    {

        // Don't initialize the DB if we are in the Admin plugin
        //if ($this->isAdmin()) {
        //    return;
        //}

        // TODO - Initialize the DB
        $dbdriver = $this->grav['config']->get('plugins.database-link.text_dbdriver');
        $dbhost = $this->grav['config']->get('plugins.database-link.text_dbhost');
        $dbname = $this->grav['config']->get('plugins.database-link.text_dbname');
        $dbuser = $this->grav['config']->get('plugins.database-link.text_dbuser');
        $dbpassword = $this->grav['config']->get('plugins.database-link.password_dbpassword');

        try {
           $this->connection = new PDO("$dbdriver:host=$dbhost;dbname=$dbname;charset=utf8mb4", $dbuser, $dbpassword);
           $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
           $this->connection->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        } catch(\PDOException $error) {
           $messages = $this->grav['messages'];
           $messages->add('Database Link Error: '.$error->getMessage(), 'error');
        }

    }

    public function onFormProcessed(Event $event)
    {
       $form = $event['form'];
       $action = $event['action'];
       $params = $event['params'];

       // TODO - if we are in a normal page, catch the 'database' action and execute the 'query' field query
       switch ($action) {
           case 'database':
               // parameter 'query' contains the insert/update/delete
               $query = $params['query'];
               try {
                   $count = $this->connection->exec($query);
                   dump("$count rows affected");
               } catch(PDOException $error) {
                   $messages = $this->grav['messages'];
                   $messages->add('Database Link Error: '.$error->getMessage(), 'error');
               }
       }

    }

    /**
     * Do some work for this event, full details of events can be found
     * on the learn site: http://learn.getgrav.org/plugins/event-hooks
     *
     * @param Event $e
     */
/*
    public function onPageContentRaw(Event $e)
    {
        // Get a variable from the plugin configuration
        $text = $this->grav['config']->get('plugins.database-link.text_var');

        // Get the current raw content
        $content = $e['page']->getRawContent();

        // Prepend the output with the custom text and set back on the page
        $e['page']->setRawContent($text . "\n\n" . $content);
    }
*/
}
