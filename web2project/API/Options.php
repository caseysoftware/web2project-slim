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
            'index'  => array('href'        => $class->resource, 'method' => 'GET'),
            'info'   => array('href'        => $class->self, 'method' => 'OPTIONS'),
            'filter' => array('href'        => $class->resource,
                              'method'      => 'GET',
                              'optional'    => array('page', 'limit', 'offset')),
            'search' => array('href'        => $class->resource, 
                              'method'      => 'GET',
                              'required'    => array('search')),
            'view'   => array('href'        => $class->resource . '/:id',
                              'method'      => 'GET',
                              'required'    => array($this->key)),
            'create' => array('href'        => $class->resource,
                              'method'      => 'POST',
                              'required'    => $this->getRequired(),
                              'optional'    => $this->getOptional()),
            'update' => array('href'        => $class->resource . '/:id',
                              'method'      => 'PATCH',
                              'required'    => array($this->key),
                              'optional'    => $this->getOptional()),
            'delete' => array('href'        => $class->resource . '/:id',
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