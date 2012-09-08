<?php

abstract class web2project_API_Base {

    protected $app       = null;
    protected $module    = null;

    protected $id        = 0;
    protected $params    = array();

    protected $key       = '';
    protected $obj       = '';
    protected $classname = '';

    public function __construct(Slim $app, $module, $id = 0)
    {
        $this->app       = $app;
        $this->module    = $module;
        $this->key       = unPluralize($this->module).'_id';

        $this->classname = getClassName($this->module);
        $this->obj       = new $this->classname;

        $this->id        = $id;
        $this->params    = $app->request()->params();
    }

    abstract public function process();
}