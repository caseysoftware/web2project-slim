<?php

//NOTE: This must be configured manually:
$web2project_folder = '../web2project';

include_once 'web2project/web2project.php';

$app = new Slim(
            array('debug' => true)
        );

//TODO: figure out authentication
$GLOBALS['acl'] = new w2p_Mocks_Permissions();

$app->get('/:module/(:more+)', function($module, $more = array()) use ($app) {

    $get = new web2project_API_Get($app, $module, $more);
    $app = $get->process();
});

/*
 * Sample: projects
 */
$app->post('/:module', function($module) use ($app) {

    $classname = getClassName($module);
    $allPostParams = $app->request()->post();

    $obj = new $classname;
    $obj->bind($allPostParams);
    $result = $obj->store();

    if ($result) {
//TODO: if success, return the 200 along with the new path
echo "success \n\n";
    } else {
//TODO: if failure, return the corresponding 400 along with the error messages
echo "fail \n\n";
    }
});

/*
 * Sample: projects/283
 */
$app->put('/:module/:id', function($module, $id) use ($app) {

    $classname = getClassName($module);
    $key = unPluralize($module).'_id';

    $allPostParams = $app->request()->post();

    $obj = new $classname;
    $obj->load($id);
    if(is_null($obj->$key)) {
//TODO: set 404 header and return because the item wasn't found
    } else {
        $obj->bind($allPostParams);
        $result = $obj->store();

        if ($result) {
//TODO: if success, return the 200 along with the new path
        } else {
//TODO: if failure, return the corresponding 401 or 404 along with the error messages
        }
    }

});

/*
 * Sample: projects/283
 */
$app->delete('/:module/:id', function($module, $id) {
    $classname = getClassName($module);

    $obj = new $classname;
    $result = $obj->delete($id);

    if ($result) {
//TODO: if success, return the 204 along with the new path
        echo "it worked! \n\n";
    } else {
        $errors = $obj->getError();

        if (isset($errors['noDeletePermission'])) {
//TODO: if failure, return the 401
        } else {
//TODO: if failure, return the corresponding 400 along with the error messages
        }
    }
});

/*
 * Sample: projects
 */
$app->options('/:module', function($module) {
    $classname = getClassName($module);

echo "this is an option request! \n\n";
//TODO: display the resource properties and/or interaction methods
});

$app->run();