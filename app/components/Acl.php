<?php

use Phalcon\Mvc\User\Component;
use Phalcon\Acl\Adapter\Memory as AclMemory;
use Phalcon\Acl\Role as AclRole;
use Phalcon\Acl\Resource as AclResource;

/**
 * Vokuro\Acl\Acl
 */
class Acl extends Component
{

    /**
     * The ACL Object
     *
     * @var \Phalcon\Acl\Adapter\Memory
     */
    private $_acl;

    /**
     * The filepath of the ACL cache file from APP_DIR
     *
     * @var string
     */
    private $_file_path;

    /**
     * Define the resources that are considered "private". These controller => actions require authentication.
     *
     * @var array
     */
    private $_private_resources = [
        'user' => [
            'index' => Users::ROLE_MODERATOR,
            'search' => Users::ROLE_USER,
            'update' => Users::ROLE_ADMIN,
            'delete' => Users::ROLE_ADMIN,
            'changePassword' => Users::ROLE_USER,
        ],
        'item' => [
            'index' => Users::ROLE_USER,
            'search' => Users::ROLE_USER,
            'edit' => Users::ROLE_USER,
            'create' => Users::ROLE_USER,
            'delete' => Users::ROLE_USER,
        ],
        'shop' => [
            'index' => Users::ROLE_USER,
            'search' => Users::ROLE_USER,
            'edit' => Users::ROLE_USER,
            'create' => Users::ROLE_USER,
            'delete' => Users::ROLE_USER,
        ],
    ];


    public function __construct()
    {
        $this->_file_path = __DIR__ . '/../cache/acl_data.txt';
    }
    /**
     * Checks if a controller is private or not
     *
     * @param string $controller_name
     * @param string $action_name
     * @return boolean
     */
    public function isPrivate($controller_name, $action_name)
    {
        if (!isset($this->_private_resources[$controller_name])) {
            return false;
        }
        $controller_resource = $this->_private_resources[$controller_name];
        return isset($controller_resource[$action_name]);
    }

    /**
     * Checks if the current profile is allowed to access a resource
     *
     * @param string $role
     * @param string $controller
     * @param string $action
     * @return boolean
     */
    public function isAllowed($role, $controller, $action)
    {
        return $this->getAcl()->isAllowed($role, $controller, $action);
    }

    /**
     * Returns the ACL list
     *
     * @return Phalcon\Acl\Adapter\Memory
     */
    public function getAcl()
    {
        // Check if the ACL is already created
        if (is_object($this->_acl)) {
            return $this->_acl;
        }

        // Check if the ACL is in APC
        if (function_exists('apc_fetch')) {
            $acl = apc_fetch('hkt-acl');
            if (is_object($acl)) {
                $this->_acl = $acl;
                return $acl;
            }
        }

        // Check if the ACL is already generated
        if (!file_exists($this->_file_path)) {
            $this->_acl = $this->rebuild();
            return $this->_acl;
        }

        // Get the ACL from the data file
        $data = file_get_contents($this->_file_path);
        $this->_acl = unserialize($data);

        // Store the ACL in APC
        if (function_exists('apc_store')) {
            apc_store('hkt-acl', $this->_acl);
        }

        return $this->_acl;
    }

    /**
     * Returns all the resoruces and their actions available in the application
     *
     * @return array
     */
    public function getResources()
    {
        return $this->_private_resources;
    }

    /**
     * Rebuilds the access list into a file
     *
     * @return \Phalcon\Acl\Adapter\Memory
     */
    public function rebuild()
    {
        $acl = new AclMemory();

        $acl->setDefaultAction(\Phalcon\Acl::DENY);

        // Register roles
        $roles = Users::$user_roles;

        foreach ($roles as $key => $role_name) {
            $acl->addRole(new AclRole($role_name));
        }

        foreach ($this->_private_resources as $resource => $actions) {
            foreach ($actions as $action => $role) {
                $acl->addResource(new AclResource($resource), $action);
                $accessable_roles = Users::getUpperRoles($role);
                foreach ($accessable_roles as $role_key => $role_name) {
                    $acl->allow($role_name, $resource, $action);
                }
            }
        }
        if (touch($this->_file_path) && is_writable($this->_file_path)) {

            file_put_contents($this->_file_path, serialize($acl));

            // Store the ACL in APC
            if (function_exists('apc_store')) {
                apc_store('hkt-acl', $acl);
            }
        } else {
            $this->flash->error(
                'The user does not have write permissions to create the ACL list at ' . $this->_file_path
            );
        }

        return $acl;
    }
}
