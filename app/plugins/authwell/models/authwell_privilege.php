<?php

/*
    A Sample Plugin Model

    Summary of model here.

    REFERENCES
        http://book.cakephp.org/view/117/Plugin-Models
*/

class AuthwellPrivilege extends AuthwellAppModel
{
    var $name = 'AuthwellPrivilege';
    var $useTable = 'authwell_privileges';

    var $hasAndBelongsToMany = array(
        'AuthwellRole' => array(
            'className'              => 'Authwell.AuthwellRole',
            'joinTable'              => 'authwell_roles__authwell_privileges',
            'foreignKey'             => 'authwell_privilege_id',
            'associationForeignKey'  => 'authwell_role_id',
            'unique'                 => true,
            'conditions'             => '',
        )
    );

    var $validate = array();
}

?>
