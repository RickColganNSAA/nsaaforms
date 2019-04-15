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

    require_once('ArithmeJax.class.php');

    $ajax = new ArithmeJax();
    $ajax->handleRequest();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "DTD/xhtml1-strict.dtd">
<html>
    <head>
        <title>AjaxAC Sample Application: ArithmeJax</title>
        <?= $ajax->loadJsCore(true) ?>

        <style type="text/css">
            #a, #b { width : 40px; }
            #ans { width : 100px; }
        </style>
    </head>
    <body>
        <h1>ArithmeJax</h1>

        <p>
            ArithmeJax is simple Ajax application used to perform server-side arithmetic.
        </p>

        <ul>
            <li><a href="index.phps">Source code for this file</a></li>
            <li><a href="ArithmeJax.class.phps">Source code for ArithMeJax application</a></li>
            <li><a href="index.php/jsapp">Generated application JS code</a></li>
        </ul>

        <form method="get" id="f">
            <input type="text" name="a" id="a" />
            <select name="op" id="op">
                <?php foreach ($ajax->validOperators as $op) { ?>
                    <option value="<?= $op ?>"><?= $op ?></option>
                <?php } ?>
            </select>
            <input type="text" name="b" id="b" />
            =
            <input type="text" name="ans" id="ans" readonly="readonly" />
            <input type="submit" value="Calculate" />
        </form>


        <?= $ajax->attachWidgets(array('f'        => 'f',
                                       'loperand' => 'a',
                                       'roperand' => 'b',
                                       'operator' => 'op',
                                       'answer'   => 'ans')) ?>

        <?= $ajax->loadJsApp(true) ?>

    </body>
</html>
