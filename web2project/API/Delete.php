<?php

/*
 * Sample urls:
 *      projects/283
 *      links/123
 */

class web2project_API_Delete extends web2project_API_Base {
    
    public function process()
    {
        $result = $this->obj->delete($this->id);
//print_r($result);
//TODO: fix the error messages
//echo 'x'.$result.'x'."\n\n";
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