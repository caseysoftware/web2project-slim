<?php

/*
 * Sample urls:
 *      projects/
 *      projects/283
 *      projects/limit/10
 *      projects/limit/10/page/3
 *      projects/page/2/limit/5
 */

class web2project_API_Get extends web2project_API_Base {

    protected function _parseParameters()
    {
        switch(count($this->more)) {
            case 0:
                $this->params['limit'] = 25;
                $this->params['page']  = 0;
                break;
            case 1:
                $this->id = (int) $this->more[0];
                break;
            default:
//TODO: process a series of elements
        }
    }
    
    public function process()
    {
        switch(count($this->more)) {
            case 1:
                $this->obj->load($this->id);
                break;
            default:
                $this->obj->{$this->key} = -1;
//TODO: load a list of objects from the params
//TODO: implement pagination in the core object

                $this->obj->count = -1;
                $this->obj->prev = 'prev page';
                $this->obj->next = 'next page';
        }

        if(is_null($this->obj->{$this->key})) {
            $this->app->response()->status(404);
        } else {
            $this->obj->this = '/'.$this->module.'/'.implode('/', $this->more);
            $api = new web2project_API_Wrapper($this->obj);
            $this->app->response()->body($api->getObjectExport());
        }

        return $this->app;
    }

}
