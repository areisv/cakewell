<?php

/*
    Authwell Role Model

    A collection of privileges assigned to a user.

    REFERENCES
        http://book.cakephp.org/view/117/Plugin-Models
*/

class AuthwellRole extends AuthwellRoleAppModel
{
    var $name = 'AuthwellRole';
    var $useTable = 'authwell_roles';

    var $hasAndBelongsToMany = array(
        'AuthwellUser' => array(
            'className'              => 'AuthwellUser',
            'joinTable'              => 'authwell_users__authwell_roles',
            'foreignKey'             => 'authwell_role_id',
            'associationForeignKey'  => 'authwell_user_id',
            'unique'                 => true,
            'conditions'             => '',
        )
    );

    var $validate = array();
}

?>
