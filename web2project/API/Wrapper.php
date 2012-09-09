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

        $this->object->super_resources = array();
        $this->setSuperResources();
        $this->object->sub_resources = array();
//        $this->setSubResources();
    }

    public function getObjectExport()
    {
        return json_encode($this->object);
    }
    
    /*
     * It's easy to know which modules this module/object is dependent on.
     */
    protected function setSuperResources()
    {
        $modules = $this->AppUI->getActiveModules();
        foreach($modules as $name => $value) {
            unset($modules[$name]);
            $modules[unPluralize($name)] = $name;
        }

        $fields  = get_class_vars($this->classname);
        foreach($fields as $field => $value) {
            $id = $this->object->{$field};
            $last_underscore = strrpos($field, '_') + 1;
            $suffix = ($last_underscore !== false) ? substr($field, $last_underscore) : $field;

            if(isset($modules[$suffix]) && $id) {
                $this->object->super_resources[$modules[$suffix]] = "/{$modules[$suffix]}/".$id;
            }
        }
    }

    /*
     * TODO: .. but how do we figure out which modules/objects are dependent on it?
     *
     * Do we need a hook_register?
     */
    protected function setSubResources()
    {
        
    }
}