<?php

/*
 * Sample urls:
 *      projects/
 *      links/
 */

class web2project_API_Options extends web2project_API_Common {
    
    public function process()
    {
        $status = $this->app->response()->status();
        
        if ($status != 200) {
            return $this->app;
        }

        $this->output->actions = array(
            'index'  => array('href'        => $this->output->self, 'method' => 'GET'),
            'info'   => array('href'        => $this->output->self, 'method' => 'OPTIONS'),
            'filter' => array('href'        => $this->output->self,
                              'method'      => 'GET',
                              'optional'    => array('page', 'limit')),
            'search' => array('href'        => $this->output->self, 
                              'method'      => 'GET',
                              'required'    => array('search')),
            'view'   => array('href'        => $this->output->self . '/:id',
                              'method'      => 'GET',
                              'required'    => array($this->key)),
            'create' => array('href'        => $this->output->self,
                              'method'      => 'POST',
                              'required'    => $this->getRequired(),
                              'optional'    => $this->getOptional()),
            'update' => array('href'        => $this->output->self . '/:id',
                              'method'      => 'PATCH',
                              'required'    => array($this->key),
                              'optional'    => $this->getOptional()),
            'delete' => array('href'        => $this->output->self . '/:id',
                              'method'      => 'DELETE',
                              'required'    => array($this->key)),
        );

        $this->app->response()->body($this->wrapper->getObjectExport($this->output));

        return $this->app;
    }

    protected function getRequired()
    {
        $this->obj->isValid();

        return array_keys($this->obj->getError());
    }

    protected function getOptional()
    {        
        $fields = get_class_vars($this->classname);
        unset($fields[$this->key]);
        $this->obj->isValid();
        $optional = array_diff($fields, $this->obj->getError());

        return array_keys($optional);
    }
}