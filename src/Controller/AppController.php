<?php

namespace App\Controller;

use Cake\Controller\Controller;
use Cake\Event\Event;
use Cake\Network\Session\DatabaseSession;
use Cake\I18n\I18n;


class AppController extends Controller
{

//	public $helpers = ['AkkaCKEditor.CKEditor'];
	 public function initialize()
    {
        parent::initialize();
		
		
        $this->loadComponent('RequestHandler');
        $this->loadComponent('Flash');	
		$this->loadComponent('Auth',['loginRedirect'=>['controller'=>'Dashboard','action'=>'index'],
                                     'logoutRedirect'=>['controller'=>'Users',"action"=>"login"],
                                     'authorize' => array('Controller')  
                                    ]);			
    }

    /**
     * Before render callback.
     *
     * @param \Cake\Event\Event $event The beforeRender event.
     * @return void
     */
	 
	public function isAuthorized($users)
    {
        if(isset($users))
        {			
            return true;
        }
        else{
            return false;
        }
    }
	 
	
    public function beforeRender(Event $event)
    {
		if(file_exists(TMP.'installed.txt')) 
		{
			$this->loadComponent("GYMFunction");
			$check_alert_on = $this->GYMFunction->getSettings("enable_alert");
			if($check_alert_on)
			{			
				$this->GYMFunction->sendAlertEmail();			
			}		
		}
        if (!array_key_exists('_serialize', $this->viewVars) &&
            in_array($this->response->type(), ['application/json', 'application/xml'])
        ) {
            $this->set('_serialize', true);
        }		
    }
	
 	public function beforeFilter(Event $event)
	{
		parent::beforeFilter($event);
		/* $session = $this->request->session();
		 if($session->read("User") != null)
		{  */
		if(file_exists(TMP.'installed.txt') && $this->request->controller != "Installer")
		{
			$this->loadComponent("GYMFunction");
			@$lang = $this->GYMFunction->getSettings("sys_language");		
			if (empty($lang)) {
				return;
			}
			I18n::locale($lang);
		}
		/* } */
	} 
}
