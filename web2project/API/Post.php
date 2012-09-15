<?php

class web2project_API_Post extends web2project_API_Common {
    
    public function process()
    {
        $status = $this->app->response()->status();
        
        if ($status != 200) {
            return $this->app;
        }

        $this->obj->bind($this->params);
        $result = $this->obj->store();

        if ($result) {
            $this->app->response()->status(201);

            $this->output->self = $this->output->root_uri . $this->output->resource_uri .
                    '/' . $this->obj->{$this->key};
        } else {
            $this->app->response()->status(400);

            $this->output->self     = $this->output->root_uri . $this->output->resource_uri;
            $this->output->obj      = $this->obj;
            $this->output->errors   = $this->obj->getError();
        }
        $this->app->response()->body($this->wrapper->getObjectExport($this->output));

        return $this->app;
    }
}