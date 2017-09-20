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
    //put your code here
    protected function getUser()
    {
        if ($this->_user === null) {
            $this->_user = WebUser::findByUsername($this->username);
        }
        return $this->_user;
    }
}
