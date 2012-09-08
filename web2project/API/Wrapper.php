<?php

class web2project_API_Wrapper {
    public $object = null;
    public $classname = '';

    protected $supraresources = array();
    protected $subresources = array();

    public function __construct($object) {
        $this->object = $object;
        $this->classname = get_class($this->object);

        $this->setSuperResources();
        $this->setSubResources();
    }

    public function getObjectExport()
    {
//TODO: figure out how to auto-determine the sub-resources and/or super resources
        $supra = array_flip($this->supraresources);
        $sub = array_flip($this->subresources);
        $this->object->links = (array_keys($supra + $sub));

        return json_encode($this->object);
    }

    /*
     * It's easy to know which modules this module/object is dependent on.
     */
    protected function setSuperResources()
    {
//These are the supraresources for projects
        $this->supraresources[] = 'companies';
        $this->supraresources[] = 'users';
    }

    /*
     * .. but how do we figure out which modules/objects are dependent on it?
     * 
     * Do we need a hook_register?
     */
    protected function setSubResources()
    {
//These are the subresources for projects
        $this->subresources[] = 'tasks';
        $this->subresources[] = 'contacts';
        $this->subresources[] = 'events';
        $this->subresources[] = 'files';
        $this->subresources[] = 'links';
        $this->subresources[] = 'forums';
    }
}