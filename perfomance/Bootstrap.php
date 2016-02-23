<?php

class Bootstrap
{
    protected $_di;

    protected $_arguments = [];

    public function __construct($argv)
    {
        $this->_di = new Phalcon\Di\FactoryDefault\Cli();
        $this->_arguments = $this->normalizeArguments($argv);
    }

    protected function registerServices()
    {
        $di = $this->_di;
        $arguments = $this->_arguments;

        $this->_di->set('config', function(){
            return new Phalcon\Config\Adapter\Ini(APP_PATH.'config/main.ini');
        }, true);

        $this->_di->set('dispatcher', function() use ($arguments){
            $dispatcher = new Phalcon\Cli\Dispatcher();
            $dispatcher->setDefaultAction('main');
            $dispatcher->setTaskSuffix($arguments['task_suffix']);
            $dispatcher->setTaskName($arguments['namespace'].'\\'.$arguments['task']);
            $dispatcher->setActionName($arguments['action']);
            $dispatcher->setParams($arguments['params']);

            return $dispatcher;
        }, true);

        $this->_di->set('db', function() use ($di) {
            $config = $di->getShared('config');

            return new Phalcon\Db\Adapter\Pdo\Mysql([
                'host' => $config->database->host,
                'username' => $config->database->username,
                'password' => $config->database->password,
                'dbname' => $config->database->name,
                'port' => $config->database->port,
                "options" => array(
                    PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'
                )
            ]);
        }, true);

        $this->_di->set('modelsMetadata', function() {
            return new Phalcon\Mvc\Model\MetaData\Memory();
        }, true);

        $this->_di->set('modelsManager', function() {
            return new Phalcon\Mvc\Model\Manager();
        }, true);
    }

    protected function registerLoaders()
    {
        $loader = new Phalcon\Loader();
        $loader->registerNamespaces([
            'Models' => APP_PATH.'models',
            'Tests' => APP_PATH.'tests',
            'Fixtures' => APP_PATH.'fixtures'
        ]);
        $loader->register();

        require_once realpath(APP_PATH.'../vendor/autoload.php');
    }

    protected function normalizeArguments($argv)
    {
        // Unset run file name
        unset($argv[0]);

        $fullTask = array_shift($argv);
        preg_match('#[A-Z]{1}[a-z]+$#', $fullTask, $matches);
        $taskSuffix = $matches[0];
        $task = substr($fullTask, 0, strlen($fullTask) - strlen($taskSuffix));
        $namespace = $taskSuffix.'s';

        return [
            'namespace' => $namespace,
            'task' => $task,
            'task_suffix' => $taskSuffix,
            'action' => array_shift($argv),
            'params' => $argv,
        ];
    }

    public function handle()
    {
        $this->registerLoaders();
        $this->registerServices();

        $dispatcher = $this->_di->get('dispatcher');
        $dispatcher->dispatch();
    }
}