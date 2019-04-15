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
     */

    require_once('AjaxACApplication.class.php');

    class ArithmeJax extends AjaxACApplication
    {
        var $validOperators = array('+', '-', '*', '/');

        function ArithmeJax()
        {
            parent::AjaxACApplication();
            $this->registerActions('calculate');
            $this->setup();
        }

        function setup()
        {
            $form = $this->createWidget('f');
            $form->addEvent(AJAXAC_EV_ONSUBMIT, 'formsubmit');
            $this->addWidget($form);

            $loperand = $this->createWidget('loperand');
            $this->addWidget($loperand);

            $roperand = $this->createWidget('roperand');
            $this->addWidget($roperand);

            $operator = $this->createWidget('operator');
            $this->addWidget($operator);

            $answer = $this->createWidget('answer');
            $this->addWidget($answer);
        }

        function action_calculate()
        {
            $loperand = $this->getRequestValue('a');
            $roperand = $this->getRequestValue('b');
            $operator = $this->getRequestValue('op');

            if (!in_array($operator, $this->validOperators) || strlen($loperand) == 0 || strlen($roperand) == 0)
                $ret = 'Error';
            else {
                $loperand = (int) $loperand;
                $roperand = (int) $roperand;

                switch ($operator) {
                    case '+':
                        $ret = $loperand + $roperand;
                        break;
                    case '-':
                        $ret = $loperand - $roperand;
                        break;
                    case '*':
                        $ret = $loperand * $roperand;
                        break;
                    case '/':
                        $ret = $roperand == 0 ? 'NaN' : $loperand / $roperand;
                        break;
                }
            }
            $this->sendResponseData('text', $ret);
        }

        function event_formsubmit(&$widget, $event)
        {
            require_once('Widgets/AjaxACWidgetXMLHttpRequest.class.php');
            $xmlhttp = new AjaxACWidgetXMLHttpRequest($this, 'calcreq', AJAXAC_METH_GET);
            $xmlhttp->setFilenameFromString($this->getApplicationUrl('calculate'));
            $xmlhttp->addParamFromHookValue('a', $this->getHookName('loperand'), 'value');
            $xmlhttp->addParamFromHookValue('b', $this->getHookName('roperand'), 'value');
            $xmlhttp->addParamFromHookValue('op', $this->getHookName('operator'), 'value');
            $xmlhttp->addEvent(AJAXAC_EV_ONXMLHTTPSUCCESS, 'handleanswer');

            $callback = "
                            function()
                            {
                                try {
                                    %s
                                }
                                catch (e) { alert(e.message); }

                                return false;
                            }
                        ";

            $callback = sprintf($callback,
                                $xmlhttp->getJsCode());

            return $callback;
        }

        function event_handleanswer(&$widget, $event)
        {
            $callback = "
                            function()
                            {
                                %s.value = %s.responseText;
                            }
                        ";

            $callback = sprintf($callback,
                                $this->getHookName('answer'),
                                $widget->getHookName());

            return $callback;
        }
    }
?>