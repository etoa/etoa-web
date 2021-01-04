<?php
/*
 * Smarty plugin
 * -------------------------------------------------------------
 * File:     function.asset.php
 * Type:     function
 * Name:     asset
 * Purpose:  outputs a random magic answer
 * -------------------------------------------------------------
 */
function smarty_function_asset($params, Smarty_Internal_Template $template)
{
    return baseUrl($params['file']);
}
