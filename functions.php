<?php

/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

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
            $word = substr($word, 0, -1);
    }
    
    return $word;
}

function exportJSON($object)
{
    return json_encode($object);
}