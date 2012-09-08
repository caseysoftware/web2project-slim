<?php

//NOTE: This must be configured manually:
$web2project_folder = '../web2project';

include_once 'web2project/web2project.php';

$app = new Slim(
            array('debug' => true)
        );

//TODO: figure out authentication
$GLOBALS['acl'] = new w2p_Mocks_Permissions();

$app->get('/:module/(:id)', function($module, $id = 0) use ($app) {
    $get = new web2project_API_Get($app, $module, $id);
    $app = $get->process();
});

$app->post('/:module', function($module) use ($app) {
    $post = new web2project_API_Post($app, $module);
    $app = $post->process();
});

$app->put('/:module/:id', function($module, $id) use ($app) {
    $put = new web2project_API_Put($app, $module, $id);
    $app = $put->process();
});

$app->delete('/:module/:id', function($module, $id) use ($app) {
    $delete = new web2project_API_Delete($app, $module, $id);
    $app = $delete->process();
});

$app->options('/:module', function($module) {
    $options = new web2project_API_Option($app, $module);
    $app = $options->process();
});

$app->run();