<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace common\models;

/**
 * Description of WebLoginForm
 *
 * @author Administrator
 */
class WebLoginForm extends LoginForm {
    public $role;
    //put your code here
    protected function getUser()
    {
        if ($this->_user === null) {
            $this->_user = WebUser::findByUsername($this->username, $this->role);
        }
       
        return $this->_user;
    }
    
    /**
     * 登录校验成功后，为用户生成新的token
     * 如果token失效，则重新生成token
     */
    public function onGenerateAccessToken ()
    {
        if (!WebUser::isAccessTokenValid($this->_user->access_token)){
            $this->_user->generateAccessToken();
            $this->_user->save(false);
        }
    }
}
