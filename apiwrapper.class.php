<?php

class APIWrapper {
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
        $supra = array_flip($this->supraresources);
        $sub = array_flip($this->subresources);
        $this->object->links = (array_keys($supra + $sub));

        return exportJSON($this->object);
    }

    protected function setSuperResources()
    {
//These are the supraresources for projects
        $this->supraresources[] = 'companies';
        $this->supraresources[] = 'users';
    }

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