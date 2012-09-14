<?php

class web2project_API_Wrapper {
    public $object      = null;
    public $classname   = '';

    protected $AppUI    = null;

    protected $supraresources = array();
    protected $subresources = array();

    public function __construct($object) {
        $this->object    = $object;
        $this->classname = get_class($this->object);
        $this->AppUI     = new w2p_Core_CAppUI();
    }

    public function getObjectExport()
    {
        return json_encode($this->object);
    }
}