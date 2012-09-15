<?php

require_once $web2project_folder.'/base.php';
require_once W2P_BASE_DIR . '/includes/config.php';
require_once W2P_BASE_DIR . '/includes/main_functions.php';
require_once W2P_BASE_DIR . '/includes/db_adodb.php';

function web2project_API_autoload($className) {
    $library_name = 'web2project_';

    if (substr($className, 0, strlen($library_name)) != $library_name) {
        return false;
    }
    $file = str_replace('_', '/', $className);
    $file = str_replace('web2project/', '', $file);
    return include dirname(__FILE__) . "/$file.php";
}

spl_autoload_register('web2project_API_autoload');


/*
 * The input is just the module name such as:
 *      projects
 *      contacts
 *      companies
 * 
 * and the out should be CProject, CContact, or CCompany respectively.
 */
function getClassName($module)
{    
    $output = unPluralize($module);
    $output = 'C'.ucwords($output);

    return $output;
}

function unPluralize($word)
{
    $suffix = substr($word, -3);
    switch ($suffix) {
        case 'ies':
            $word = substr($word, 0, -3).'y';
            break;
        default:
            $character = substr($word, -1);
            if('s' == $character) {
                $word = substr($word, 0, -1);
            }
    }
    
    return $word;
}