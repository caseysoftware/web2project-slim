<?php

$slim_folder = '../Slim/';
require $slim_folder.'Slim/Slim.php';

$web2project_folder = '../web2project';
require_once $web2project_folder.'/base.php';
require_once W2P_BASE_DIR . '/includes/config.php';
require_once W2P_BASE_DIR . '/includes/main_functions.php';
require_once W2P_BASE_DIR . '/includes/db_adodb.php';

include 'functions.php';

$app = new Slim(
            array('debug' => true)
        );

//TODO: figure out authentication

/*
 * Sample: projects/283
 */
$app->get('/:module/:id', function($module, $id) {
    $classname = getClassName($module);

    $obj = new $classname;
    $obj->load($id);

    
    echo exportJSON($obj);
//TODO: process the object and turn it into the xml/json object as needed
//TODO: figure out how to not the sub-resources and/or super resources
});

/*
 * Sample: projects
 */
$app->post('/:module', function($module) {
    $classname = getClassName($module);

    $allPostParams = $app->request()->post();

    $obj = new $classname;
    $obj->bind($allPostParams);
    $result = $obj->store();
//TODO: take the $result and detect if it was success or failure
//TODO: if success, return the 200 along with the new path
//TODO: if failure, return the corresponding 400 along with the error messages
});

/*
 * Sample: projects/283
 */
$app->put('/:module/:id', function($module, $id) {
    $classname = getClassName($module);

    $allPostParams = $app->request()->post();

    $obj = new $classname;
    $obj->load($id);
    $obj->bind($allPostParams);
    $result = $obj->store();
//TODO: take the $result and detect if it was success or failure
//TODO: if success, return the 200 along with the new path
//TODO: if failure, return the corresponding 400 along with the error messages
});

/*
 * Sample: projects/283
 */
$app->delete('/:module/:id', function($module, $id) {
    $classname = getClassName($module);

    $allPostParams = $app->request()->post();

    $obj = new $classname;
    $result = $obj->delete($id);
//TODO: take the $result and detect if it was success or failure
//TODO: if success, return the 200 along with the new path
//TODO: if failure, return the corresponding 400 along with the error messages
});

/*
 * Sample: projects
 */
$app->options('/:module', function($module) {
    $classname = getClassName($module);

//TODO: display the resource properties and/or interaction methods
});

$app->run();