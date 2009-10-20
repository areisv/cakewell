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
    var $UserDataCache = array();           # a per-request cache of user data

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
                'rule' => array('is_honeypot', 'honey_login'),
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
        /*
           Validates Form Data and verifies that user attempting to login is
           in the database and active.
        */

        # set
        $this->set($FormData);

        # basic field validation
        if ( ! $this->validates() )
            return 0;

        # lookup user
        $UserDb = $this->find_user_by_email($FormData['AuthwellUser']['email_login']);

        # invalidate: user not found
        if ( empty($UserDb) )
            return $this->invalidate_login();

        # invalidate: user is not active (TODO)
        if ( ! $UserDb['User']['active'] )
            return $this->invalidate_login('Your account is inactive.');

        # invalidate: password not found
        if ( $UserDb['User']['password'] != $this->password($FormData['AuthwellUser']['password_login']) )
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

    function find_user_by_email($email, $reload=0)
    {
        /*
            Finds user data by email address.  The first query is equivalent to
            findByEmail.  Then, this method finds the privilege list based on
            role list.

            As a side-effect, it also sets the UserDataCache property, which
            serves as an in-request cache.
        */
        # check cache
        if ( !empty($this->UserDataCache) && !$reload )
            return $this->UserDataCache;

        if ( !$UserData = $this->find('first', array(
                'conditions' => array( 'email' => $email ),
                'recursive' => 2
           )) )
            return null;

        #debug($UserData);

        $RoleList = Set::extract($UserData, 'AuthwellRole.{n}.id');
        $PrivilegeList = $this->extract_privileges_from_user_data($UserData);
        $UserData['AuthwellUser']['rolenames'] = Set::extract(
            $UserData['AuthwellRole'], '{n}.name');
        $UserData['AuthwellUser']['dotpaths'] = Set::extract(
            $PrivilegeList, '{n}.dotpath');

        $UserData = array(
            'User'      => $UserData['AuthwellUser'],
            'Roles'      => $UserData['AuthwellRole'],
            'Privileges' => $PrivilegeList,
        );

        #debug($UserData);

        $this->UserDataCache = $UserData;
        return $UserData;
    }

    function extract_privileges_from_user_data($UserData)
    {
        $PrivilegeList = array();

        if ( !isset($UserData['AuthwellRole']) || empty($UserData['AuthwellRole']) )
            return $PrivilegeList;

        foreach ( $UserData['AuthwellRole'] as $RoleData )
        {
            if ( !isset($RoleData['AuthwellPrivilege']) ) continue;
            foreach ( $RoleData['AuthwellPrivilege'] as $PrivRec )
                $PrivilegeList[$PrivRec['id']] = $PrivRec;
        }

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

    function is_honeypot($data, $field)
    {
        return empty($this->data[$this->name][$field]);
    }

    function password($plaintext)
    {
        return md5($plaintext . Configure::read('Security.salt'));
    }

    function as_binary($plaintext)
    {
        $select = 'SELECT HEX("%s") as hex';
        $Result = $this->query(sprintf($select, $plaintext));
        return '0x' . $Result[0][0]['hex'];
    }

    function _0x_password($plaintext)
    {
        return $this->as_binary( $this->password($plaintext) );
    }
}

?>
