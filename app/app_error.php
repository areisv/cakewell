<?php

/*
    The Cakewell App Error Handler

    NOTES
        Reference: http://book.cakephp.org/view/154/Error-Handling
*/

class AppError extends ErrorHandler
{
    function cakewellTestError($params)
    {
        $this->controller->set('message', $params['message']);
        $this->controller->set('name', 'cakewellTestError');
        $this->_outputMessage('t_');
    }
}

?>
