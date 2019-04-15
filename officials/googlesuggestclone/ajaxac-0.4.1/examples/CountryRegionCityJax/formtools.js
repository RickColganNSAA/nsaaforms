
function populateSelect(elt, arr)
{
    elt.options.length = 0;
    elt.options[0] = new Option('', '');
    for (i = 0; i < arr.length; i++) {
        elt.options[i+1] = new Option(arr[i], arr[i]);
    }
    elt.selectedIndex = 0;
}

function clearSelect(elt)
{
    elt.options.length = 0;
    elt.selectedIndex = 0;
}

function enableCountry(co, r, c)
{
    co.options[0] = new Option(co.fullDefault, '');
    co.selectedIndex = 0;
    co.disabled = 0;

    r.options[0] = new Option(r.emptyDefault, '');
    r.selectedIndex = 0;
    r.disabled = 1;

    c.options.length = 0;
    c.options[0] = new Option(c.emptyDefault, '');
    c.disabled = 1;
}

function enableRegion(r, c)
{
    r.options[0] = new Option(r.fullDefault, '');
    r.selectedIndex = 0;
    r.disabled = 0;

    c.options.length = 0;
    c.options[0] = new Option(c.emptyDefaultRegion, '');
    c.disabled = 1;
}

function enableCity(c)
{
    c.options[0] = new Option(c.fullDefault, '');
    c.selectedIndex = 0;
    c.disabled = 0;
}

function loadingCountry(co, r, c)
{
    co.options.length = 0;
    co.options[0] = new Option(co.loadingText);
    co.disabled = 1;

    r.options.length = 0;
    r.disabled = 1;

    c.options.length = 0;
    c.disabled = 1;

}

function loadingRegion(r, c)
{
    r.options.length = 0;
    r.options[0] = new Option(r.loadingText);
    r.disabled = 1;

    c.options.length = 0;
    c.disabled = 1;

}

function loadingCity(c)
{
    c.options.length = 0;
    c.options[0] = new Option(c.loadingText);
    c.disabled = 1;
}
