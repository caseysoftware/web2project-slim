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

        if (!$result) {
            $error = new web2project_API_Error();
            $error->this     = $this->obj->this = '/'.$this->module . '/' . $this->id;
            $error->status   = (isset($errors['noDeletePermission'])) ? '401' : '400';
            $error->errors   = $this->obj->getError();

            $this->app->response()->status($error->status);
            $this->app->response()->body($error->getObjectExport());
        }

        return $this->app;
    }
}