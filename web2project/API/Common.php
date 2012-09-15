<?php

abstract class web2project_API_Common {

    protected $app       = null;
    protected $module    = null;

    protected $id        = 0;
    protected $params    = array();

    protected $key       = '';
    protected $obj       = '';
    protected $classname = '';

    protected $AppUI     = null;
    protected $resources = array();

    abstract public function process();

    public function __construct(Slim $app, $module, $id = 0, $submodule = '')
    {
        $this->app       = $app;
        $this->module    = $module;
        $this->submodule = $submodule;
        $this->key       = unPluralize($this->module).'_id';
        $this->id        = $id;
        $this->request   = $this->app->request();
        $this->params    = $this->app->request()->params();

        $this->wrapper   = new web2project_API_Wrapper();
        $this->output    = new stdClass();

        $this->output->root_uri = $this->request->getRootUri();
        $this->output->resource_uri = $this->request->getResourceUri();

        $this->output->self = $this->output->root_uri . $this->output->resource_uri .
                $this->processParams($this->params);

        return $this->init();
    }

    protected function init()
    {
        $this->AppUI = new w2p_Core_CAppUI();
        $this->resources = $this->AppUI->getActiveModules();

        if(isset($this->resources[$this->module])) {
            $this->classname = getClassName($this->module);
            $this->obj       = new $this->classname;

            if(isset($this->resources[$this->submodule])) {
                $this->key       = unPluralize($this->submodule).'_id';
                $this->classname = getClassName($this->submodule);
                $this->obj       = new $this->classname;
            }
        } else {
            $this->app->response()->status(404);
        }

        return $this->app;
    }

    /*
     * It's easy to know which modules this module/object is dependent on.
     */
    protected function setSuperResources()
    {
        $resources = array();

        $fields  = get_class_vars($this->classname);
        foreach($fields as $field => $value) {
            $id = $this->obj->{$field};
            $last_underscore = strrpos($field, '_') + 1;
            $suffix = ($last_underscore !== false) ? substr($field, $last_underscore) : $field;
            unset($fields[$field]);
            $fields[w2p_pluralize($suffix)] = $id;
        }
//TODO: figure out what to do with _parents
        $modules = $this->resources;
        foreach($fields as $field => $value) {
            if(isset($modules[$field]) && $value) {
                $resources[$field] = array('name' => $modules[$field], 'href' => "/$field/".$value);
            }
        }

        return $resources;
    }

    protected function setSubResources()
    {
        $resources = array();

        $modules = $this->resources;

        foreach($modules as $path => $name) {
            $classname = getClassName($path);
            $fields  = get_class_vars($classname);
            try {
                foreach($fields as $field => $value) {
                    $last_underscore = strrpos($field, '_') + 1;
                    $suffix = ($last_underscore !== false) ? substr($field, $last_underscore) : $field;
                    $suffix = w2p_pluralize($suffix);
//TODO: figure out what to do with _parents
                    if ($suffix == $this->module) {
                        $resources[$path] = array('name' => $name, 'href' => "/{$this->module}/{$this->id}/$path");
                    }
                }
            } catch (Exception $exc) {
//TODO: do we care about what happens here?
            }
        }

        return $resources;
    }

    protected function processParams($params)
    {
        $string = http_build_query($params);

        return (0 < strlen($string) ? '?'.$string : '');
    }
}