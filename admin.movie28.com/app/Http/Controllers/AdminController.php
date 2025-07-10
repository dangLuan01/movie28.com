<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\App;

class AdminController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    protected $_params = [];
    protected $_viewAction = '';

    public function __construct(Request $request)
    {
        // created by Dang Minh Luan
        // create param then use in controller, view, model
        $this->middleware('web');
        if (!App::runningInConsole()) { // off when runing in background
            $requestAction                  = $request->route()->getAction();
            
            $alias                          = explode('.', $requestAction['as']);
            
            $this->_params['prefix']        = isset($alias[0]) ? $alias[0] : 'admin'; // componnent
            $this->_params['controller']    = isset($alias[1]) ? $alias[1] : 'index';
            $this->_params['action']        = isset($alias[2]) ? $alias[2] : 'index';
            $this->_params['as']            = $requestAction['as'];
            $this->_params = array_merge($this->_params, $request->all());

            // create view layout with component/controller/action.blade.php
            $this->_viewAction              = $this->_params['prefix'] . '.' . $this->_params['controller'] . '.' . $this->_params['action'];
        }
    }
    protected function getCookie($key, $defaultValue)
    {
        $name = $this->_params['prefix']  . '-' .  $this->_params['controller']   . '-' .  $this->_params['action'] . $key;
        return isset($_COOKIE[$name]) ? $_COOKIE[$name] : $defaultValue;
    }
}
