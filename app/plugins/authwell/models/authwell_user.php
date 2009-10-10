<?php

/*
    Authwell Plugin User Model

    A basic user record.

    REFERENCES
        http://book.cakephp.org/view/117/Plugin-Models
*/

class AuthwellUser extends AuthwellAppModel
{
    var $name = 'AuthwellUser';
    var $useTable = 'authwell_users';

    var $hasAndBelongsToMany = array(
        'AuthwellRole' => array(
            'className'              => 'Authwell.AuthwellRole',
            'joinTable'              => 'authwell_users__authwell_roles',
            'foreignKey'             => 'authwell_user_id',
            'associationForeignKey'  => 'authwell_role_id',
            'unique'                 => true,
            'conditions'             => '',
        )
    );

    var $validate = array(
        'email' => array(
            'rule' => array('email'),
            'allowEmpty' => false,
        ),
        'email_confirm' => array(
            'rule' => array('match_emails'),
            'allowEmpty' => false,
            'message' => 'Please make sure the email addresses you enter match',
        ),
        'password_plain' => array(
            'syntax' => array(
                'rule' => array('custom', '/^[A-Za-z0-9!@#$%^&*\(\)\/]{3,24}$/i'),
                'message' => '3-24 characters please (recommend 8+)'
                ),
            'required' => array(
                'rule' => 'notEmpty',
                'message' => 'please fill in a value'
            ),
        ),
        'new_password' => array(),
        'new_password_match' => array()
    );

    function beforeValidate()
    {
        $this->validate['new_password'] = $this->validate['password_plain'];
        $this->validate['new_password_match'] = $this->validate['password_plain'];
        return 1;
    }

    function beforeSave()
    {
        $this->data['AuthwellUser']['password'] =
            $this->password($this->data['AuthwellUser']['password']);
        return 1;
    }

    function find_user_by_email()
    {
        if ( !$Data = $this->findByEmail($email) )
            return null;

        return $Data;
    }

    function get_privilege_list($user_id)
    {
        /*
            Returns list of privilege notations associated with this user.
        */
        $PrivilegeList =array();

        $RoleList = $this->get_role_list($user_id);

        foreach ( $RoleList as $role_id )
            $PrivilegeList = array_merge($PrivilegeList, $this->Role->get_privilege_list($role_id));

        return $PrivilegeList;
    }

    function get_role_list($user_id)
    {
        /*
            Return list of role names associated with this user
        */
        $RoleList = array();

        $Result = $this->Role->find( 'all', array(
            'conditions' => array( 'user_id' => $user_id )
        ));

        if ( $Result )
            $RoleList = Set::extract($Result, '{n}.Role.id');

        return $RoleList;
    }

    function match_emails($data)
    {
        return ( $this->data[$this->name]['email'] == $data['email_confirm'] );
    }

    function password($plaintext)
    {
        return md5($plaintext . Configure::read('Security.salt'));
    }
}

?>
