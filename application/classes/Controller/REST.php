<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Abstract Controller class for RESTful controller mapping. Supports GET, PUT,
 * POST, and DELETE. By default, these methods will be mapped to these actions:
 *
 * GET
 * :  Mapped to the "index" action, lists all objects
 *
 * POST
 * :  Mapped to the "create" action, creates a new object
 *
 * PUT
 * :  Mapped to the "update" action, update an existing object
 *
 * DELETE
 * :  Mapped to the "delete" action, delete an existing object
 *
 * Additional methods can be supported by adding the method and action to
 * the `$_action_map` property.
 *
 * [!!] Using this class within a website will require heavy modification,
 * due to most web browsers only supporting the GET and POST methods.
 * Generally, this class should only be used for web services and APIs.
 *
 * @package    Kohana
 * @category   Controller
 * @author     Kohana Team
 * @copyright  (c) 2009-2012 Kohana Team
 * @license    http://kohanaframework.org/license
 */
abstract class Controller_REST extends Controller {

    /**
     * @var  array  REST types
     */
    protected $_action_map = array
    (
        HTTP_Request::GET    => 'index',
        HTTP_Request::PUT    => 'update',
        HTTP_Request::POST   => 'create',
        HTTP_Request::DELETE => 'delete',
    );

    /**
     * @var  string  requested action
     */
    protected $_action_requested = '';

    protected $_user;

    protected $entityName = 'entity';

    /**
     * Checks the user authentication and the requested method against the available methods.
     * If the method is supported, sets the request action from the map. If not supported,
     * the "invalid" action will be called.
     */
    public function before() {
        $user = Auth::instance()->get_user();

        if( $user ) {
            $this->_user = $user;
            $this->_action_requested = $this->request->action();

            $method = Arr::get($_SERVER, 'HTTP_X_HTTP_METHOD_OVERRIDE', $this->request->method());

            if ( ! isset($this->_action_map[$method])) {
                $this->request->action('invalid');
            } else {
                $config = Kohana::$config->load('app');

                $this->response->headers('Access-Control-Allow-Origin', $config['base_url']);
                $this->response->headers('Content-Type','application/json; charset=utf-8');
                $this->request->action($this->_action_map[$method]);
            }

        } else {
            $this->request->action('unauthorized');
        }
        return parent::before();
    }

    public function after() {
        if (in_array(Arr::get($_SERVER, 'HTTP_X_HTTP_METHOD_OVERRIDE', $this->request->method()), array(
            HTTP_Request::PUT,
            HTTP_Request::POST,
            HTTP_Request::DELETE
        ))) {
            $this->response->headers('cache-control', 'no-cache, no-store, max-age=0, must-revalidate');
        }
    }

    /**
     * Sends a 405 "Method Not Allowed" response and a list of allowed actions.
     */
    public function action_invalid() {
        // Send the "Method Not Allowed" response
        $this->response->status(405)
            ->headers('Allow', implode(', ', array_keys($this->_action_map)));
    }

    /**
     * Sends a 401 "Unauthorized" response
     */
    public function action_unauthorized() {
        // Send the "Method Not Allowed" response
        $this->response->status(401);
    }

    protected function respond($entities) {
        $this->response->body(json_encode(array(
            'entityName' => $this->entityName,
            'entities' => $entities
        )));
    }

} // End REST