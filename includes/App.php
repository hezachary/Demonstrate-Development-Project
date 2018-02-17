<?php
/**
 * App
 *
 * @package demo
 * @author Zhehai He
 * @copyright 2018
 * @version 0.1
 * @access public
 */
class App
{
    /**
     * app related values
     * @var array
     */
    private $app = [];

    /**
     * app singleton instance
     * @var \App
     */
    private static $singleton;

    /**
     * get singleton
     * @return \App
     */
    public static function get()
    {
        if (!self::$singleton) {
            self::$singleton = new self;
        }
        return self::$singleton;
    }

    /**
     * App constructor.
     */
    private function __construct()
    {

    }

    /**
     * auto store set get related method data
     * such as: setRouter('post', \Controller\Post::class);
     *
     * @param string $name
     * @param array $arguments
     *
     * @return null
     */
    public function __call($name, array $arguments)
    {
        $action = substr($name, 0, 3);
        $category = substr($name, 3);
        switch($action) {
            case 'set':
                $this->app[$category][$arguments[0]] = $arguments[1];
                break;
            case 'get':
                return $this->app[$category][$arguments[0]]??null;
        }
    }

    /**
     * run the application, get response
     */
    public function run()
    {
        /** Since there is only one page, no need for router compare, call default controller directly*/
        $strController = $this->getRouter('Default');

        /**
         * Dependency injection applied here
         * it make the unit test easy to apply by using
         * mock objects
         */
        $objController = $this->resolve($strController);

        /**
         * flex-able action, can easy control by router (here is by frontend
         * form data)
         */
        $strAction = !$_REQUEST
        || !array_key_exists('action', $_REQUEST)
        || !method_exists($objController, $_REQUEST['action'])
        || !is_callable([$objController, $_REQUEST['action']])
          ? 'index'
          : $_REQUEST['action'];

        $objController->$strAction();

        $objController->render();
    }

    /**
     * resolve dependency injection
     * @param $strClassName
     *
     * @return object|void
     */
    protected function resolve($strClassName)
    {
        if(!class_exists($strClassName, true)){
            return;
        }

        $reflectionClass = new ReflectionClass($strClassName);
        $reflectionConstruct = $reflectionClass->getConstructor();
        $reflectionParams = $reflectionConstruct->getParameters();

        $args = [];
        foreach ($reflectionParams as $parameter) {
            if($reflectionParameterClass = $parameter->getClass()) {
                $class = $reflectionParameterClass->getName();
                $args[] = $this->getContainer($class);
            }
        }

        return !empty($args) ?
          $reflectionClass->newInstanceArgs($args) :
          new $strClassName();
    }

    /**
     * get repository, does not support recursive load yet
     *
     * @param string $name
     *
     * @return object/null
     */
    public function getContainer($name)
    {
        $class = $this->getRepo($name) ?? $name;
        if (class_exists($class, true)) {
            return new $class();
        }
        return null;
    }
}