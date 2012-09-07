<?php

class web2project_API_Base {

    protected $app      = null;
    protected $module   = null;
    protected $more     = 0;

    protected $id       = 0;
    protected $params   = array();

    protected $key      = '';
    protected $obj      = '';

    public function __construct($app, $module, $more)
    {
        $this->app      = $app;
        $this->module   = $module;
        $this->key      = unPluralize($this->module).'_id';

        $classname      = getClassName($this->module);
        $this->obj      = new $classname;

        $this->more     = $more;
        $this->_parseParameters();
    }
}