<?php

namespace App\View;

use Cake\Event\EventManager;
use Cake\Network\Request;
use Cake\Network\Response;


class AjaxView extends AppView
{

    
    public $layout = 'ajax';

    
    public function initialize()
    {
        parent::initialize();

        $this->response->type('ajax');
    }
}
