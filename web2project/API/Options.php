<?php

/*
 * Sample urls:
 *      projects/
 *      links/
 */

class web2project_API_Options extends web2project_API_Base {
    
    public function process()
    {
        $class = new stdClass();
        $class->resource = '/'.$this->module .'/';
        
        $class->actions = array(
            'index'  => array('href'        => $class->resource, 'method' => 'GET'),
            'filter' => array('href'        => $class->resource, 
                              'method'      => 'GET',
                              'optional'    => array('page', 'limit', 'offset')),
            'search' => array('href'        => $class->resource, 
                              'method'      => 'GET',
                              'required'    => array('search')),
            'view'   => array('href'        => $class->resource, 
                              'method'      => 'GET',
                              'required'    => array($this->key)),
            'create' => array('href'        => $class->resource, 
                              'method'      => 'POST',
                              'required'    => $this->getRequired(),
                              'optional'    => $this->getOptional()),
            'update' => array('href'        => $class->resource, 
                              'method'      => 'PATCH',
                              'required'    => $this->key,
//TODO: This next getOptional isn't quite correct.. we should remove $this->key
                              'optional'    => $this->getOptional()),  
            'delete' => array('href'        => $class->resource,
                              'method'      => 'DELETE',
                              'required'    => $this->key),

        );

        $this->app->response()->body(json_encode($class));

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
        $this->obj->isValid();
        $optional = array_diff($fields, $this->obj->getError());

        return array_keys($optional);
    }
}