<?php
    /**
     * Copyright 2005 Zervaas Enterprises (www.zervaas.com.au)
     *
     * Licensed under the Apache License, Version 2.0 (the "License");
     * you may not use this file except in compliance with the License.
     * You may obtain a copy of the License at
     *
     *     http://www.apache.org/licenses/LICENSE-2.0
     *
     * Unless required by applicable law or agreed to in writing, software
     * distributed under the License is distributed on an "AS IS" BASIS,
     * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
     * See the License for the specific language governing permissions and
     * limitations under the License.
     *
     * @package AjaxAC
     */

    /**
     * The various JavaScript events that can occur
     */
    define('AJAXAC_EV_ONFOCUS',            'onfocus');
    define('AJAXAC_EV_ONBLUR',             'onblur');
    define('AJAXAC_EV_ONMOUSEOVER',        'onmouseover');
    define('AJAXAC_EV_ONMOUSEOUT',         'onmouseout');
    define('AJAXAC_EV_ONMOUSEDOWN',        'onmousedown');
    define('AJAXAC_EV_ONMOUSEUP',          'onmouseup');
    define('AJAXAC_EV_ONSUBMIT',           'onsubmit');
    define('AJAXAC_EV_ONCLICK',            'onclick');
    define('AJAXAC_EV_ONLOAD',             'onload');
    define('AJAXAC_EV_ONCHANGE',           'onchange');
    define('AJAXAC_EV_ONKEYPRESS',         'onkeypress');
    define('AJAXAC_EV_ONKEYDOWN',          'onkeydown');
    define('AJAXAC_EV_ONKEYUP',            'onkeyup');

    /**
     * The HTTP subrequest types that can occur
     */
    define('AJAXAC_METH_GET',  'get');
    define('AJAXAC_METH_POST', 'post');

    /**
     * The file containing the core AjaxAC code (non-application specific code)
     */
    define('AJAXAC_CORE_JS_LIB', 'core.js');

    /**
     * The base class for all AjaxAC applications. Contains a wide range of functionality
     * for handling requests, subrequest, attaching actions/events/widgets/etc.. as well
     * as generating subrequest URL's and piecing together application JavaScript code.
     * This class should never been instantiated directly, as it won't do anything. Instead,
     * specific applications should extend this class, creating widgets + their events, as
     * well as actions + handlers for sub-requests
     *
     * @author  Quentin Zervaas
     * @access  Public
     */
    class AjaxACApplication
    {
        /**
         * An array holding all the widgets for our application
         *
         * @access  protected
         */
        var $_widgets = array();


        /**
         * An array holding paths to any additional JavaScript files
         * that need to be loaded in. They will be included directly
         * with the generated JavaScript
         *
         * @access  private
         */
        var $_jslibs = array();


        var $_responseTypes = array('text'    => 'text/plain',
                                    'jsarray' => 'text/plain',
                                    'xml'     => 'text/xml',
                                    'csv'     => 'text/plain');

        /**
         * AjaxACApplication
         *
         * Constructor. Sets up the base actions, as well as installing
         * the application configuration
         *
         * @access  public
         * @param   array   $config     The application configuration, containing key/name pairs
         */
        function AjaxACApplication($config = array())
        {
            $this->registerActions('jscore', 'jsapp');
            // 'jscore' is the subrequest action for outputting AjaxAC core JavaScript
            // 'jsapp' is the subrequest action for outputting application specific JavaScript

            if (!is_array($config))
                $config = array($config);

            $this->_config = $config;
        }


        /**
         * createWidget
         *
         * Create a new widget with the specified name
         *
         * @access  protected
         * @param   string  $name       The internal application name for our widget
         * @param   bool    $listener   True if the widget is a listener, false if not
         * @param   bool    $talker     True if the widget is a talker, false if not
         * @return  &AjaxAcWidget       A reference to the newly created widget
         */
        function &createWidget($name, $listener = false, $talker = false)
        {
            require_once('Widgets/AjaxACWidget.class.php');
            return new AjaxACWidget($this, $name, $listener, $talker);
        }


        /**
         * handleAction
         *
         * Handle a subrequest. This determines if the request action exists, and if
         * so, then performs if, using the passed on params and request data
         *
         * @access  package
         * @param   string  $action         The action name. The callback will be action_$action
         * @param   array   $params         A 0-indexed array containing each request path element after the action.
         *                                  For example, /path/to/ajaxac/actionname/param1/param2
         * @param   array   $requestData    The '$_GET' data from the request
         */
        function handleAction($action = null, $params = array(), $requestData = array())
        {
            $this->_params = $params;
            $this->_requestData = $requestData;
            $callback = 'action_' . $action;
            if (method_exists($this, $callback)) {
                $this->$callback();
            }
        }


        /**
         * loadJsCore
         *
         * Loads the AjaxAC core JavaScript code, either by displaying HTML code
         * to fetch it, or displaying the actual code. If displaying the actual code,
         * then optionally the HTTP headers can also be sent with the request. The script
         * will exit on completion of sending the code with HTTP headers.
         *
         * @access  public
         * @param   bool    $externalRef    True if only returning HTML code to call JavaScript
         * @param   bool    $sendHeaders    If $externalRef false, then set this true to include HTTP headers
         * @return  string                  If $externalRef true, then the HTML code will be returned
         */
        function loadJsCore($externalRef = false, $sendHeaders = false)
        {
            if ($externalRef) {
                $html = sprintf('<script type="text/javascript" src="%s"></script>',
                                htmlSpecialChars($this->getApplicationUrl('jscore')));
                return $html;
            }
            else {
                $jsLib = dirname(__FILE__) . '/' . AJAXAC_CORE_JS_LIB;
                if (!is_readable($jsLib)) {
                    if ($sendHeaders)
                        header('HTTP/1.0 404 Not Found');
                    exit;
                }
                $js = file_get_contents($jsLib);

                if ($sendHeaders) {
                    /**
                     * @todo Add etag/last-modified stuff to enable browser caching
                     */
                    header('Content-type: text/javascript');
                    header('Content-length: ' . strlen($js));
                }
                echo $js;
                if ($sendHeaders)
                    exit;
            }
        }


        /**
         * loadJsApp
         *
         * Loads the application JavaScript code, either by displaying HTML code
         * to fetch it, or displaying the actual code. If displaying the actual code,
         * then optionally the HTTP headers can also be sent with the request. The script
         * will exit on completion of sending the code with HTTP headers.
         *
         * @access  public
         * @param   bool    $externalRef    True if only returning HTML code to call JavaScript
         * @param   bool    $sendHeaders    If $externalRef false, then set this true to include HTTP headers
         * @return  string                  If $externalRef true, then the HTML code will be returned
         */
        function loadJsApp($externalRef = false, $sendHeaders = false)
        {
            if ($externalRef) {
                $html = sprintf('<script type="text/javascript" src="%s"></script>',
                                htmlSpecialChars($this->getApplicationUrl('jsapp')));
                return $html;
            }
            else {
                $js = $this->generateJsApp();
                if ($sendHeaders) {
                    /**
                     * @todo Add etag/last-modified stuff to enable browser caching
                     * @todo reuse this code with core stuff above
                     */
                    header('Content-type: text/javascript');
                    header('Content-length: ' . strlen($js));
                }

                echo $js;

                if ($sendHeaders)
                    exit;
            }
        }


        /**
         * getApplicationUrl
         *
         * Generates the application URL, for the specified action. This is used
         * to generate the request file for XMLHttp sub-requests
         *
         * @access  package
         * @todo    Add extra options for more path/request params
         * @param   string  $action     The action to generate the URL for. Must be a valid action
         * @return  string              The generated application URL
         */
        function getApplicationUrl($action = '')
        {
            if (!isset($this->_web_path)) {
                //$_url = array();
                //parse_str($_SERVER['REQUEST_URI'], $_url);
                //$this->_web_path = isset($_url['path']) ? $_url['path'] : '';
                $this->_web_path = $_SERVER['SCRIPT_NAME'];
            }

            if (strlen($action) > 0 && $this->actionExists($action))
                return $this->_web_path . '/' . $action;

            return $this->_web_path;
        }


        /**
         * handleRequest
         *
         * Fetches parameters + options from the current request (be it the main
         * request or a sub-request), and hands it to the application for handling
         *
         * @access  public
         */
        function handleRequest()
        {
            $path = isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : '';
            $params = array_filter(explode('/', $path));

            $action = count($params) > 0 ? array_shift($params) : '';
            $this->handleAction($action, $params, $_GET);
        }


        /**
         * addJsLib
         *
         * Add an external JavaScript library to the application code
         *
         * @access  protected
         * @param   $file       An absolute or relative file-system path to the code
         */
        function addJsLib($file)
        {
            $this->_jslibs[] = $file;
        }


        /**
         * generateJsApp
         *
         * Generates the application JavaScript code, combining the additional
         * libs with the generated code for widgets
         *
         * @access  private
         * @return  string      The generate JavaScript code
         */
        function generateJsApp()
        {
            $js = array();

            foreach ($this->_jslibs as $file) {
                if (is_readable($file) && is_file($file))
                    $js[] = file_get_contents($file);
            }

            $keys = array_keys($this->_widgets);

            $numWidgets = count($keys);
            for ($i = 0; $i < $numWidgets; $i++) {
                $key = $keys[$i];
                $obj = &$this->_widgets[$key];

                // now generate the js code for each widget
                $js[] = $obj->getJsCode();
            }

            return join("\n", $js);
        }

        /**
         * getHookName
         *
         * Determines the hookname for the passed internal application ID.
         * This is used to determine a hookname for an element we know the
         * name of but don't have direct access to
         *
         * @access  protected
         * @param   string  $name   The internal application ID of the widget
         * @return  string          The hook name of the element, or null if not found
         */
        function getHookName($name)
        {
            if (array_key_exists($name, $this->_widgets))
                return $this->_widgets[$name]->getHookName();
            return null;
        }


        /**
         * registerActions
         *
         * @access protected
         *
         * Register one or more actions, to be handled using a callback
         * named something like action_actionName(). Takes an arbitrary
         * number of parameters.
         */
        function registerActions()
        {
            foreach (func_get_args() as $action) {
                $action = trim($action);
                if (strlen($action) > 0)
                    $this->_actions[] = $action;
            }
        }


        /**
         * actionExists
         *
         * Returns whether or not an action exists
         *
         * @access  protected
         * @return  bool        True if the action exists, false if not
         */
        function actionExists($action)
        {
            return in_array($action, $this->_actions);
        }


        /**
         * attachWidget
         *
         * Attach a single widget. That is, connect a HTML element to an internal
         * widget so events and actions can be applied to that HTML element
         *
         * @access  public
         * @param   string  $internalId     The internal widget name being connected to
         * @param   string  $jsId           The ID of the connecting HTML element
         * @return  string                  The necessary JavaScript code to attach the widget
         */
        function attachWidget($internalId, $jsId)
        {
            $ret = sprintf("<script type=\"text/javascript\">ajaxac_attachWidget('%s', '%s');</script>",
                           $this->getHookName($internalId),
                           $jsId);
            return $ret;
        }


        /**
         * attachWidgets
         *
         * Attach multiple widgets. That is, connect HTML elements to internal
         * widgets so events and actions can be applied to them.
         *
         * @access  public
         * @param   array   $arr    An array of widgets. Key is widget name, value is HTML ID
         * @return  string          The necessary JavaScript code to attach the widgets
         */
        function attachWidgets($arr)
        {
            if (!is_array($arr))
                return '';

            $lines[] = '<script type="text/javascript">';
            foreach ($arr as $internalId => $jsId) {
                $lines[] = sprintf("ajaxac_attachWidget('%s', '%s');",
                                   $this->getHookName($internalId),
                                   $jsId);
            }
            $lines[] = '</script>';

            return join("\n", $lines);
        }


        /**
         * addWidget
         *
         * Adds a widget to the application
         *
         * @access  protected
         * @param   AjaxACWidget    &$widget    The widget to add
         */
        function addWidget(&$widget)
        {
            $this->_widgets[$widget->getName()] = &$widget;
        }


        /**
         * XmlHttpRequest
         *
         * Creates a XmlHttpRequest widget, with specified name
         * and action. Further parameters and event handlers
         * are added later on
         *
         * @access  protected
         * @param   string  $name   The internal name for the widget. Will not conflict with other widgets
         * @param   string  $method The HTTP request method, such as get or post
         * @return  AjaxACWidgetXMLHttpRequest      The created request object
         */
        function XmlHttpRequest($name, $method)
        {
            require_once('Widgets/AjaxACWidgetXMLHttpRequest.class.php');
            return new AjaxACWidgetXMLHttpRequest($this, $name, $method);
        }

        /**
         * getParam
         *
         * Retrieve a parameter from the user request. You can choose to return
         * some default value if the parameter is null or not set
         *
         * @access  protected
         * @param   string  $key        The name of the param to return
         * @param   mixed   $default    A default value to return if the key doesn't exist or is null
         * @return  mixed               The fetched or default value
         */
        function getParam($key, $default = null)
        {
            if (!array_key_exists($key, $this->_params) || is_null($this->_params[$key]))
                return $default;
            return $this->_params[$key];
        }

        /**
         * getRequestValue
         *
         * Retrieve a parameter from the user request. You can choose to return
         * some default value if the parameter is null or not set
         *
         * @access  protected
         * @param   string  $key        The name of the request value to return
         * @param   mixed   $default    A default value to return if the key doesn't exist or is null
         * @return  mixed               The fetched or default value
         */
        function getRequestValue($key, $default = null)
        {
            if (!array_key_exists($key, $this->_requestData) || is_null($this->_requestData[$key]))
                return $default;
            return $this->_requestData[$key];
        }

        /**
         * getConfigValue
         *
         * Retrieve a parameter from the application config. You can choose to return
         * some default value if the parameter is null or not set
         *
         * @access  protected
         * @param   string  $key        The name of the config value to return
         * @param   mixed   $default    A default value to return if the key doesn't exist or is null
         * @return  mixed               The fetched or default value
         */
        function getConfigValue($key, $default = null)
        {
            if (!array_key_exists($key, $this->_config) || is_null($this->_config[$key]))
                return $default;
            return $this->_config[$key];
        }


        /**
         * sendResponseData
         *
         * Send some data back as a response to a HTTP subrequest. Can be in various
         * data formats, each of which treat the data different and send headers
         * accordingly. The script exits after this method is called.
         *
         * @todo    Cache control functionality
         * @todo    Perhaps create separate callbacks for each different data type instead of switch
         * @todo    Move actual data output / headers into separate section so can be used elsewhere
         * @param   string  $type       The type of data being sent
         * @param   mixed   $data       The data to return
         */
        function sendResponseData($type, $data)
        {
            $type = strtolower($type);
            if (!array_key_exists($type, $this->_responseTypes))
                $type = 'text';
            $mime = $this->_responseTypes[$type];

            switch ($type) {
                case 'jsarray':
                    // expects an array
                    if (is_array($data) && count($data) > 0) {
                        $ret = $this->_phpArrayToJs($data);
                        //$ret = sprintf("[ '%s' ]", trim(join("','", $data)));
                    }
                    else
                        $ret = '[]';
                    break;
                case 'text':
                default:
                    $ret = $data;
            }

            header('Content-type: ' . $mime);
            header('Content-length: ' . strlen($ret));
            echo $ret;

            exit;
        }

        function _phpArrayToJs($arr)
        {
            $items = array();

            foreach ($arr as $k => $v) {
                if (is_array($v))
                    $items[] = $this->_phpArrayToJs($v);
                else if (is_int($v))
                    $items[] = $v;
                else
                    $items[] = "'" . $this->escapeJs($v) . "'";
            }

            return '[' . join(',', $items) . ']';
        }


        /**
         * escapeJs
         *
         * Make a string JavaScript-safe so errors are not generated. This code was
         * shamelessly borrowed from Smarty
         *
         * @access  protected
         * @param   string  $str    The string to escape
         * @return  string          The escaped string
         */
        function escapeJs($str)
        {
            // borrowed from smarty
            return strtr($str, array('\\'=>'\\\\',"'"=>"\\'",'"'=>'\\"',"\r"=>'\\r',"\n"=>'\\n','</'=>'<\/'));
        }

        // core action handlers

        /**
         * action_jscore
         *
         * Handles the jscore action - returns the full core JavaScript code plus HTTP headers
         *
         * @access private
         */
        function action_jscore()
        {
            $this->loadJsCore(false, true);
        }

        /**
         * action_jsapp
         *
         * Handles the jsapp action - returns the full lib JavaScript code plus HTTP headers
         *
         * @access private
         */
        function action_jsapp()
        {
            $this->loadJsApp(false, true);
        }

    }
?>