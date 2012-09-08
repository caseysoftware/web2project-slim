<?php

/*
 * Sample urls:
 *      projects/
 *      projects/283
 *      projects/?limit=10
 *      projects/?limit=10&page=3
 *      projects/?page=2&limit=5
 */

class web2project_API_Get extends web2project_API_Base {

    public function process()
    {
        $this->params = array_map("intval", $this->params);

        if(count($this->params)) {
            $this->obj->{$this->key} = -1;
            $this->obj->count = -1;

            $this->obj->prev = 'prev page';
            $this->obj->next = 'next page';
        } else {
            $this->obj->load($this->id);
            $this->params['id'] = $this->id;
        }

        if(is_null($this->obj->{$this->key})) {
            $this->app->response()->status(404);
        } else {
            $this->obj->this = '/'.$this->module.'/?'.http_build_query($this->params);
            $api = new web2project_API_Wrapper($this->obj);
            $this->app->response()->body($api->getObjectExport());
        }

        return $this->app;
    }

}
