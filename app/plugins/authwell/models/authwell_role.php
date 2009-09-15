<?php

/*
    Authwell Role Model

    A collection of privileges assigned to a user.

    REFERENCES
        http://book.cakephp.org/view/117/Plugin-Models
*/

class AuthwellRole extends AuthwellAppModel
{
    var $name = 'AuthwellRole';
    var $useTable = 'authwell_roles';

    var $hasAndBelongsToMany = array(
        'AuthwellUser' => array(
            'className'              => 'Authwell.AuthwellUser',
            'joinTable'              => 'authwell_users__authwell_roles',
            'foreignKey'             => 'authwell_role_id',
            'associationForeignKey'  => 'authwell_user_id',
            'unique'                 => true,
            'conditions'             => '',
        ),
        'AuthwellPrivilege' => array(
            'className'              => 'Authwell.AuthwellPrivilege',
            'joinTable'              => 'authwell_roles__authwell_privileges',
            'foreignKey'             => 'authwell_role_id',
            'associationForeignKey'  => 'authwell_privilege_id',
            'unique'                 => true,
            'conditions'             => '',
        )
    );

    var $validate = array();
}

?>
