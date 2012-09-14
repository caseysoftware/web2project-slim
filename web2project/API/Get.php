<?php

/*
 * Sample urls:
 *      projects/
 *      projects/283
 *      projects/?limit=10
 *      projects/?limit=10&page=3
 *      projects/?page=2&limit=5
 */

class web2project_API_Get extends web2project_API_Common {

    public function process()
    {
        $status = $this->app->response()->status();
        
        if ($status != 200) {
            return $this->app;
        }

        $this->params = array_map("intval", $this->params);

        if ($this->id) {
            $this->obj->loadFull(null, $this->id);
            $this->obj->this = '/'.$this->module.'/'.$this->id;
        } else {
            $this->obj->{$this->key} = -1;
            $this->obj->count = -1;

            $this->obj->prev = 'TODO: prev page';
            $this->obj->next = 'TODO: next page';
            $this->obj->this = '/'.$this->module.'/?'.http_build_query($this->params);
        }

        if(is_null($this->obj->{$this->key})) {
            $this->app->response()->status(404);
        } else {
            $api = new web2project_API_Wrapper($this->obj);
            $this->app->response()->body($api->getObjectExport());
        }

        return $this->app;
    }

}
