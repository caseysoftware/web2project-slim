<?php

abstract class web2project_API_Common {

    protected $app       = null;
    protected $module    = null;

    protected $id        = 0;
    protected $params    = array();

    protected $key       = '';
    protected $obj       = '';
    protected $classname = '';

    protected $resources = array();

    public function __construct(Slim $app, $module, $id = 0)
    {
        $this->app       = $app;
        $this->module    = $module;
        $this->key       = unPluralize($this->module).'_id';
        $this->id        = $id;

        return $this->init();
    }

    protected function init()
    {
        $AppUI = new w2p_Core_CAppUI();
        $this->resources = $AppUI->getActiveModules();

        if(isset($this->resources[$this->module])) {
            $this->classname = getClassName($this->module);
            $this->obj       = new $this->classname;
            $this->params    = $this->app->request()->params();
        } else {
            $this->app->response()->status(404);
        }

        return $this->app;
    }

    abstract public function process();
}