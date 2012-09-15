<?php

/*
 * Sample urls:
 *      projects/283
 *      links/123
 */

class web2project_API_Delete extends web2project_API_Common {
    
    public function process()
    {
        $status = $this->app->response()->status();
        
        if ($status != 200) {
            return $this->app;
        }

        $result = $this->obj->delete($this->id);

        if ($result) {
            $this->app->response()->status(204);
        } else {
            $status   = (isset($errors['noDeletePermission'])) ? '401' : '400';
            $this->app->response()->status($error->status);

            $this->output->self     = $this->output->root_uri . $this->output->resource_uri;
            $this->output->errors   = $this->obj->getError();

            $this->app->response()->body($this->wrapper->getObjectExport($this->output));
        }

        return $this->app;
    }
}