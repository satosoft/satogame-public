<?php
namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
//use App\Http\Helpers\Helpers\activeTemplate;
use Illuminate\Support\Facades\Log;
use App\Constants\Status;


class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    public $activeTemplate;
    public $general;

    public function __construct()
    {
        $this->activeTemplate = activeTemplate();

        $this->general = gs();
        //Log::info('The value of active template in controller class is: ' . $this->activeTemplate); 
        //Log::info('The value of general in controller class is: ' . $this->general); 
        $status = new Status();
        //Log::info('The value status in controller class is existaha');

        if (class_exists('App\Constants\Status')) {
            Log::info('The value status in controller class is existaha');
        } else {
            Log::info('The value status in controller class is not existaha');
        }
        $className = get_called_class();
       // Onumoti::mySite($this,$className);
    }
}
