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

            if (!$result) {
                $this->app->response()->status(400);

                $this->output->resource = $this->obj;
                $this->output->errors   = $this->obj->getError();
            }

            $this->output->self = $this->output->root_uri . $this->output->resource_uri;
            $this->app->response()->body($this->wrapper->getObjectExport($this->output));
        }

        return $this->app;
    }
}