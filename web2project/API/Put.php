<?php

/*
 * Sample urls:
 *      projects/283
 *      links/123
 */

class web2project_API_Put extends web2project_API_Common {
    
    public function process()
    {
        $status = $this->app->response()->status();
        
        if ($status != 200) {
            return $this->app;
        }

        $this->obj->load($this->id);

        if(is_null($this->obj->{$this->key})) {
            $this->app->response()->status(404);
        } else {
            $this->obj->bind($this->params);
            $result = $this->obj->store();

            if ($result) {
                $this->obj->this = '/'.$this->module.'/' .$this->id;
                $api = new web2project_API_Wrapper($this->obj);
                $this->app->response()->body($api->getObjectExport());
            } else {
                $this->app->response()->status(400);

                $error = new web2project_API_Error();
                $error->status   = 400;
                $error->this     = $this->obj->this = '/'.$this->module;
                $error->resource = $this->obj;
                $error->errors   = $this->obj->getError();

                $this->app->response()->body($error->getObjectExport());
            }
        }

        return $this->app;
    }
}