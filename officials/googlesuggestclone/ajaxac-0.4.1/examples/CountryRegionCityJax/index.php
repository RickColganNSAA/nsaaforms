<?php
    require_once('CountryRegionCityJax.class.php');

    $config = array('countryEmptyDefault' => null,
                    'countryFullDefault'  => 'Please select a country',
                    'countryLoadingText'  => 'Loading ...',

                    'regionEmptyDefault'  => 'Please select a country',
                    'regionFullDefault'   => 'Please select a region/state',
                    'regionLoadingText'   => 'Loading ...',

                    'cityEmptyNoCountryDefault' => 'Please select a country',
                    'cityEmptyNoRegionDefault'  => 'Please select a region/state',
                    'cityFullDefault'           => 'Please select a city',
                    'cityLoadingText'           => 'Loading ...'
                    );

    $ajax = new CountryRegionCityJax($config);
    $ajax->handleRequest();
?>
<html>
    <head>
        <title>AjaxAC Sample Application: CountryRegionCityJax</title>
        <?= $ajax->loadJsCore(true) ?>

        <style type="text/css">
            select { width : 200px; }
        </style>
    </head>
    <body>
        <h1>CountryRegionCityJax</h1>
        <p>
            The CountryRegionCityJax firstly populates a dropdown box with a list
            of countries. When a country is selected, the next dropdown box is
            populated with a list of regions from that country. When a region is
            selected, a list of cities in that region populates a third dropdown.
        </p>

        <p>
            All three elements are talkers, because data gets loaded into them.
            Country and region are listeners, because events that take place on
            them result in a subsequent action (country populates region, region
            populates city). City is not a listener, because changing the city
            doesn't have any effect.
        </p>

        <p>
            Note: there is an intentional delay on loading of data to demonstrate
            how the controls behave while data is being downloaded
        </p>

        <ul>
            <li><a href="index.phps">Source code for this file</a></li>
            <li><a href="CountryRegionCityJax.class.phps">Source code for CountryRegionCityJax application</a></li>
            <li><a href="locations.txt">Server-side location data</a></li>
            <li><a href="index.php/jsapp">Generated application JS code</a></li>
        </ul>

        <form method="get" id="f">
            <table>
                <tr>
                    <td>Country:</td>
                    <td><select name="country" id="country"></select></td>
                </tr>
                <tr>
                    <td>State/Region:</td>
                    <td><select name="region" id="region"></select></td>
                </tr>
                <tr>
                    <td>City:</td>
                    <td><select name="city" id="city"></select></td>
                </tr>
            </table>
        </form>

        <?= $ajax->attachWidgets(array('country' => 'country',
                                       'region'  => 'region',
                                       'city'    => 'city')) ?>

        <?= $ajax->loadJsApp(true) ?>

    </body>
</html>
