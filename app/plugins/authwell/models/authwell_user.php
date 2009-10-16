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
    var $loginFormErrors = array();

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

        # login
        'email_login' => array(
            'required' => array(
                'rule' => 'notEmpty',
                'message' => 'please fill in a value'
            ),
            'email' => array(
                'rule' => 'email',
                'message' => 'your login is your email address'
            ),
        ),
        'password_login' => array(
            'required' => array(
                'rule' => 'notEmpty',
                'message' => 'please fill in a value'
            ),
        ),
        'honey_login' => array(
            'required' => array(
                'rule' => array('honeypot', 'honey_login'),
                'message' => 'no bots please (this field should be invisible and empty)'
            ),
        ),

        # signup
        'email' => array(
            'rule' => array('email'),
            'allowEmpty' => false,
        ),
        'email_confirm' => array(
            'rule' => array('match_emails'),
            'allowEmpty' => false,
            'message' => 'Please make sure the email addresses you enter match',
        ),
        'password' => array(
            'required' => array(
                'rule' => 'notEmpty',
                'message' => 'please fill in a value'
            ),
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

    function is_valid_login_request($FormData)
    {
        # set
        $this->set($FormData);

        # basic field validation
        if ( ! $this->validates() )
            return 0;

        # lookup user
        $UserDb = $this->find_user_by_email($FormData['AuthwellUser']['email']);

        # invalidate: user not found
        if ( empty($UserDb) )
            return $this->invalidate_login();

        # invalidate: user is not active (TODO)
        #if ( ! $UserDb['User']['active'] )
        #    return $this->invalidate_login('Your account is inactive.');

        # invalidate: password not found
        if ( $UserDb['User']['password'] != $this->password($FormData['AuthwellUser']['password']) )
            return $this->invalidate_login();

        // still here: valid
        return 1;
    }

    function invalidate_login($message='default')
    {
        if ( $message == 'default' )
            $message = 'Sorry.  We were unable to authenticate you.';
        $this->invalidate('form', $message);
        $this->loginFormErrors[] = $message;
        array_unique($this->loginFormErrors);
        return 0;
    }

    function find_user_by_email($email)
    {
        /*
            Finds user data by email address.  The first query is equivalent to
            findByEmail.  Then, this method finds the privilege list based on
            role list.
        */
        if ( !$UserData = $this->find('first', array(
                'conditions' => array( 'email' => $email )
           )) )
            return null;

        $RoleList = Set::extract($UserData, 'AuthwellRole.{n}.id');
        $PrivilegeList = $this->find_privilege_list_from_user_list($RoleList);

        $UserData = array(
            'User'      => $UserData['AuthwellUser'],
            'Roles'      => $UserData['AuthwellRole'],
            'Privileges' => $PrivilegeList,
        );

        return $UserData;
    }

    function find_privilege_list_from_user_list($RoleList)
    {
        $PrivilegeList = array();

        foreach ( $RoleList as $role_id ) {
            $RolePrivilegeList = $this->AuthwellRole->get_privilege_list($role_id);
            foreach ( $RolePrivilegeList as $Rec )
                $PrivilegeList[$Rec['id']] = $Rec;
        }

        return $PrivilegeList;
    }

    function get_privilege_list($user_id)
    {
        /*
            Returns list of privilege notations associated with this user.
        */
        $PrivilegeList =array();

        $RoleList = $this->get_role_list($user_id);

        return $this->extract_privilege_list_from_user_list($RoleList);
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

    function honeypot($data, $field)
    {
        return empty($this->data[$this->name][$field]);
    }

    function password($plaintext)
    {
        return md5($plaintext . Configure::read('Security.salt'));
    }
}

?>
