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

    /**
     * CountryRegionCityJax
     *
     * A sample AjaxAC application used to populate Country/Region/City dropdown
     * select boxes. All data is dynamically loaded in the background. When a
     * country is selected, all the regions for that country are fetched and placed
     * into the region dropdown, then likewise with cities when a region is selected.
     * It also deals with fancy stuff like disabling the select boxes when
     * applicable, as well as putting 'loading' style text.
     *
     * There are several limitations at this point, such as no non-javascript
     * fallback. Also, this code is bloatware, however in the near future there will
     * hopefully be PHP functions to deal with generating much of this code. Another
     * drawback is that the text file method we're using is fairly inefficient,
     * however the whole thing could easily be hooked into a database, but the point
     * of this sample is to demonstrate the framework stuff, not to deal with
     * databases. Another issue is that at this point we have no standard data
     * exchange format, so we're just returning JavaScript code for creating arrays
     */
    class CountryRegionCityJax extends AjaxACApplication
    {
        // the file location data is stored in. Each city should be stored on
        // a separate line, in the form: CountryName,RegionName,CityName
        var $data = 'locations.txt';


        function CountryRegionCityJax($config = null)
        {
            parent::AjaxACApplication($config);
            $this->registerActions('getcountries', 'getregions', 'getcities');

            // This application contains 3 actions:
            //  1. action_getcountries(), for fetching a country list
            //  2. action_getregions(), for fetching a region list for a given country
            //  3. action_getcities(), for fetching a city list for a given country/region
            // See below for the implementation of each of these methods

            $this->setup();
        }

        function setup()
        {
            $this->addJsLib('formtools.js');
            // there's a bunch of utility functions that never change in this file


            // Next we need to create the HTML select elements. There is one
            // to list countries, one to list regions and one to list cities.
            // We want to perform certain actions on each when they are loaded,
            // so the onload event is added to each of them, with their own
            // callback (event_countryinit(), event_regioninit(), event_cityinit()).
            // Additionally, when a country is changed, we need to update the
            // region list, so we add the onchange element to the country.
            // Likewise we add onchange to regions so the cities dropdown
            // can be updated

            // And finally, each widget is added to the application using addWidget()

            $country = $this->createWidget('country');
            $country->addEvent(AJAXAC_EV_ONLOAD, 'countryinit');
            $country->addEvent(AJAXAC_EV_ONCHANGE, 'countrychange');
            $this->addWidget($country);

            $region = $this->createWidget('region');
            $region->addEvent(AJAXAC_EV_ONLOAD, 'regioninit');
            $region->addEvent(AJAXAC_EV_ONCHANGE, 'regionchange');
            $this->addWidget($region);

            $city = $this->createWidget('city');
            $city->addEvent(AJAXAC_EV_ONLOAD, 'cityinit');
            $this->addWidget($city);

            // after having all done this, a number of things must now be done:
            //  1. Create a HTML page (see index.php) containing 3 dropdowns,
            //     as well as attaching them to each of these widgets
            //  2. Implement event_countryinit()
            //  3. Implement event_countrychange()
            //  4. Implement event_regioninit()
            //  5. Implement event_regionchange()
            //  6. Implement event_cityinit()
        }

        /**
         * Handle the getcountries action, by returning a JavaScript array
         * of all the countries, or an empty array if none are found
         */
        function action_getcountries()
        {
            $countries = $this->getCountries();
            $this->sendResponseData('jsarray', $countries);
        }

        /**
         * Handle the getregions action, by returning a JavaScript array
         * of all the regions for the request country, or an empty array
         * if none are found
         */
        function action_getregions()
        {
            $regions = $this->getRegions($this->getRequestValue('c'));
            $this->sendResponseData('jsarray', $regions);
        }

        /**
         * Handle the getcities action, by returning a JavaScript array
         * of all the cities for the request region/country, or an empty array
         * if none are found
         */
        function action_getcities()
        {
            $cities = $this->getCities($this->getRequestValue('c'), $this->getRequestValue('r'));
            $this->sendResponseData('jsarray', $cities);
        }

        /**
         * Initialises the country dropdown selector
         */
        function event_countryinit(&$widget, $event)
        {
            // create a new xmlhttp widget so we can fetch the initial list
            // of countries. the action to do this is getcountries.
            // once the country list is ready, we process this data with
            // the handlecountries event callback
            $xmlhttp = $this->XMLHttpRequest('r1', AJAXAC_METH_GET);
            $xmlhttp->setFilenameFromString($this->getApplicationUrl('getcountries'));
            $xmlhttp->addEvent(AJAXAC_EV_ONXMLHTTPSUCCESS, 'handlecountries');

            // callback description:
            //  1. Initialize various text values for the element such as default text
            //  2. Set the 3 dropdowns to their required state while the country list is loading
            //  3. Submit the HTTP subrequest to fetch the country data

            $callback = "
                            function()
                            {
                                this.emptyDefault = '%s';
                                this.fullDefault = '%s';
                                this.loadingText = '%s';

                                loadingCountry(this, %s, %s);

                                try {
                                    %s
                                }
                                catch (e) { }


                                return false;
                            }
                        ";

            $callback = sprintf($callback,
                                $this->escapeJs($this->getConfigValue('countryEmptyDefault')),
                                $this->escapeJs($this->getConfigValue('countryFullDefault')),
                                $this->escapeJs($this->getConfigValue('countryLoadingText')),
                                $this->getHookName('region'),
                                $this->getHookName('city'),
                                $xmlhttp->getJsCode());

            return $callback;
        }

        /**
         * Initialises the country dropdown selector. Basically just sets up text strings
         * for various states
         */
        function event_regioninit(&$widget, $event)
        {
            $callback = "
                            function()
                            {
                                this.emptyDefault = '%s';
                                this.fullDefault = '%s';
                                this.loadingText = '%s';
                            }
                        ";
            $callback = sprintf($callback,
                                $this->escapeJs($this->getConfigValue('regionEmptyDefault')),
                                $this->escapeJs($this->getConfigValue('regionFullDefault')),
                                $this->escapeJs($this->getConfigValue('regionLoadingText')));

            return $callback;
        }

        /**
         * Initialises the country dropdown selector. Basically just sets up text strings
         * for various states. It has an extra one to regions, as there is text to indicate
         * to select a country when no country has been selected, or to select a region when
         * a country has been selected but no region has.
         */
        function event_cityinit(&$widget, $event)
        {
            $callback = "
                            function()
                            {
                                this.emptyDefault = '%s';
                                this.emptyDefaultRegion = '%s';
                                this.fullDefault = '%s';
                                this.loadingText = '%s';
                            }
                        ";
            $callback = sprintf($callback,
                                $this->escapeJs($this->getConfigValue('cityEmptyNoCountryDefault')),
                                $this->escapeJs($this->getConfigValue('cityEmptyNoRegionDefault')),
                                $this->escapeJs($this->getConfigValue('cityFullDefault')),
                                $this->escapeJs($this->getConfigValue('cityLoadingText')));

            return $callback;
        }

        /**
         * Deals with the returned country data once the XMLHttp request is completed
         */
        function event_handlecountries(&$widget, $event)
        {
            // callback description:
            //  1. Firstly checks the the HTTP subrequest is complete
            //  2. Next checks that it was a valid 200 response header
            //  3. Assigns the returned JavaScript array from action_getcountries() to _data
            //  4. Populates the country dropdown with what is in _data
            //  5. Set the 3 dropdowns to their required state for country data just being loaded
            //     (this is basically, tell user to pick a country, and disable region/city until this is done
            $callback = "
                            function()
                            {
                                _data = ajaxac_receivejsarray(%1\$s.responseText);
                                populateSelect(%2\$s, _data);
                                enableCountry(%2\$s, %3\$s, %4\$s);
                            }
                        ";
            $callback = sprintf($callback,
                                $widget->getHookName(),
                                $this->getHookName('country'),
                                $this->getHookName('region'),
                                $this->getHookName('city'));
            return $callback;
        }

        /**
         * Handles the value in the country dropdown being changed
         */
        function event_countrychange(&$widget, $event)
        {
            // when a country is changed, we need to fetch the associated
            // regions, so we create a HTTP request to do so. The action to
            // do this is getregions, and we also need to pass to it the
            // selected country in the 'c' request parameter. Because this
            // country value is stored in a JavaScript variable, we pass the name of
            // the variable. And finally, once the request is complete, we
            // need to insert the region data, which is done in event_handleregions
            $xmlhttp = $this->XMLHttpRequest('r2', AJAXAC_METH_GET);
            $xmlhttp->setFilenameFromString($this->getApplicationUrl('getregions'));
            $xmlhttp->addParamFromHookValue('c', $this->getHookName('country'));
            $xmlhttp->addEvent(AJAXAC_EV_ONXMLHTTPSUCCESS, 'handleregions');

            // callback description:
            //  1. Check if a country has been selected
            //  2. If not, set the state back to needing to select a country as when countries were initially loaded
            //  3. If so, set the state of region/city to indicate that a region is being loaded, and perform the subrequest
            //
            $callback = "
                            function()
                            {
                                try {
                                    if (this.selectedIndex == 0)
                                        enableCountry(this, %2\$s, %3\$s);
                                    else {
                                        loadingRegion(%2\$s, %3\$s);
                                        %4\$s
                                    }

                                }
                                catch (e) { }


                                return false;
                            }
                        ";

            $callback = sprintf($callback,
                                $this->getHookName('country'),
                                $this->getHookName('region'),
                                $this->getHookName('city'),
                                $xmlhttp->getJsCode());

            return $callback;
        }

        /**
         * Deals with the returned region data once the XMLHttp request is completed
         */
        function event_handleregions(&$widget, $event)
        {
            // callback description:
            //  1. Firstly checks the the HTTP subrequest is complete
            //  2. Next checks that it was a valid 200 response header
            //  3. Assigns the returned JavaScript array from action_getregions() to _data
            //  4. Populates the region dropdown with what is in _data
            //  5. Set the region/city dropdowns to their required state for region data just being loaded
            //     (this is basically, tell user to pick a region, and disable city until this is done
            $callback = "
                            function()
                            {
                                _data = ajaxac_receivejsarray(%1\$s.responseText);
                                populateSelect(%2\$s, _data);
                                enableRegion(%2\$s, %3\$s);
                            }
                        ";
            $callback = sprintf($callback,
                                $widget->getHookName(),
                                $this->getHookName('region'),
                                $this->getHookName('city'));
            return $callback;
        }

        /**
         * Handles the value in the region dropdown being changed
         */
        function event_regionchange(&$widget, $event)
        {
            // when a region is changed, we need to fetch the associated
            // cities, so we create a HTTP request to do so. The action to
            // do this is getcities, and we also need to pass to it the
            // selected country in the 'c' request parameter and the selected
            // region in the 'r' request parameter. Because the country and
            // region values are stored in JavaScript variables, we pass the name of
            // the variables. And finally, once the request is complete, we
            // need to insert the city data, which is done in event_handlecities
            $xmlhttp = $this->XMLHttpRequest('r3', AJAXAC_METH_GET);
            $xmlhttp->setFilenameFromString($this->getApplicationUrl('getcities'));
            $xmlhttp->addParamFromHookValue('c', $this->getHookName('country'));
            $xmlhttp->addParamFromHookValue('r', $this->getHookName('region'));
            $xmlhttp->addEvent(AJAXAC_EV_ONXMLHTTPSUCCESS, 'handlecities');

            // callback description:
            //  1. Check if a region has been selected
            //  2. If not, set the state back to needing to select a region as when regions were initially loaded
            //  3. If so, set the state of city to indicate that a city is being loaded, and perform the subrequest
            //
            $callback = "
                            function()
                            {
                                try {
                                    if (this.selectedIndex == 0)
                                        enableRegion(%2\$s, %3\$s);
                                    else {
                                        loadingCity(%3\$s);
                                        %1\$s
                                    }
                                }
                                catch (e) { }

                                return false;
                            }
                        ";

            $callback = sprintf($callback,
                                $xmlhttp->getJsCode(),
                                $this->getHookName('region'),
                                $this->getHookName('city'));

            return $callback;
        }

        /**
         * Deals with the returned city data once the XMLHttp request is completed
         */
        function event_handlecities(&$widget, $event)
        {
            // callback description:
            //  1. Firstly checks the the HTTP subrequest is complete
            //  2. Next checks that it was a valid 200 response header
            //  3. Assigns the returned JavaScript array from action_getcities() to _data
            //  4. Populates the city dropdown with what is in _data
            //  5. Set the city dropdowns to its required state for city data just being loaded
            //     (this is basically, tell user to pick a city
            $callback = "
                            function()
                            {
                                _data = ajaxac_receivejsarray(%1\$s.responseText);
                                populateSelect(%2\$s, _data);
                                enableCity(%2\$s);
                            }
                        ";
            $callback = sprintf($callback,
                                $widget->getHookName(),
                                $this->getHookName('city'));
            return $callback;
        }

        /**
         * Retrieve the list of countries and return them in an array
         */
        function getCountries()
        {
            // uncomment this to notice the disabling/loading status update
            usleep(300000);
            return array_keys($this->getNestedData());
        }

        /**
         * Retrieve the list of regions for the given country, or null if
         * the country was not found
         */
        function getRegions($country)
        {
            // uncomment this to notice the disabling/loading status update
            usleep(300000);
            $data = $this->getNestedData();
            if (isset($data[$country]))
                return array_keys($data[$country]);
            return null;
        }

        /**
         * Retrieve the list of cities for the given country and region, or
         * null if the country or region were not found
         */
        function getCities($country, $region)
        {
            // uncomment this to notice the disabling/loading status update
            usleep(300000);
            $data = $this->getNestedData();
            if (isset($data[$country][$region]))
                return $data[$country][$region];
            return null;
        }

        /**
         * A utility function to parse our location data into a usable format
         */
        function getNestedData()
        {
            $ret = array();
            $lines = file($this->data);

            foreach ($lines as $line) {
                $line = trim($line);
                if (strlen($line) == 0)
                    continue;
                $parts = explode(',', $line, 3);
                $country = $parts[0];
                $region = $parts[1];
                $city = $parts[2];
                $ret[$country][$region][] = $city;
            }
            return $ret;
        }
    }
?>