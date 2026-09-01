<?php

namespace App\Controllers;

/**
 * This is the base controller for your Leaf MVC Project.
 * You can initialize packages or define methods here to use
 * them across all your other controllers which extend this one.
 */
class HelpController extends Controller
{
    // You can define methods here that would be used
    // throughout your controller classes
    // public function someMethod() {}

    /**
     * Obtiene el último día del año
     */
    public function last_year_day($fecha)
    {  
        date_default_timezone_set('America/Mexico_City');
        $fecha = explode("-", $fecha);
        /*$month = date('m');
        $year = date('Y');*/
        $year = $fecha[0];
        $month= $fecha[1];
        
        //$day = date("d", mktime(0,0,0, $month+1, 0, $year));
    
        return date('Y-m-d', mktime(0,0,0, 12, 31, $year));
    }
            
    /**
     * Ontiene el primer día del año
     */      
    public function first_year_day($fecha) 
    {
        date_default_timezone_set('America/Mexico_City');
        $fecha = explode("-", $fecha);   
        $year = $fecha[0];
        //$day = date("d", mktime(0,0,0, $month+1, 0, $year));
        return date('Y-m-d', mktime(0,0,0, 1, 1, $year));
    }
}
