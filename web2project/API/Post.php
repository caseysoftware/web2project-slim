<?php

class web2project_API_Post extends web2project_API_Base {
    
    public function process()
    {
        $this->obj->bind($this->params);
        $result = $this->obj->store();

        if (!$result) {
            $this->app->response()->status(400);

            $error = new web2project_API_Error();
            $error->status   = 400;
            $error->this     = $this->obj->this = '/'.$this->module;
            $error->resource = $this->obj;
            $error->errors   = $this->obj->getError();

            $this->app->response()->body($error->getObjectExport());
        }

        return $this->app;
    }
}