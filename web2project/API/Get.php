<?php

class web2project_API_Get {

    protected $app         = null;
    protected $module   = null;
    protected $id       = 0;

    public function __construct($app, $module, $id)
    {
        $this->id       = $id;
        $this->app      = $app;
        $this->module   = $module;
    }

    public function process()
    {
        $classname = getClassName($this->module);
        $key = unPluralize($this->module).'_id';

        $obj = new $classname;
        $obj->load($this->id);

        if(is_null($obj->$key)) {
            $this->app->response()->status(404);
        } else {
            $api = new web2project_API_Wrapper($obj);
            $this->app->response()->body($api->getObjectExport());
        }

        return $this->app;
    }

}
