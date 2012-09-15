<?php

class web2project_API_Root {

    protected $app      = null;
    protected $AppUI    = null;
    protected $request  = null;

    protected $object   = null;

    public function __construct(Slim $app)
    {
        $this->app      = $app;
        $this->AppUI    = new w2p_Core_CAppUI();
        $this->request  = $this->app->request();

        $this->output   = new stdClass();
        $this->output->root_uri = $this->request->getRootUri();
        $this->output->resource_uri = $this->request->getResourceUri();
    }

    public function options()
    {
        $subresources = array();

        $modules = $this->AppUI->getActiveModules();
//TODO: There are some non-API-able modules in this list. We need to figure out how to remove them.
        foreach($modules as $path => $module) {
            $subresources[$path] = array('name' => $module, 'href' => $this->output->self.$path);
        }
        
        $this->output->sub_resources = $subresources;

        $api = new web2project_API_Wrapper();
        $this->app->response()->body($api->getObjectExport($this->output));

        return $this->app;
    }
}