<?php

class web2project_Output_Base {

    public function __construct($output = '')
    {
        
    }

    public function getObjectExport($object)
    {
        return json_encode($object);
    }
}