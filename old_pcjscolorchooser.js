<!--
/* -------------------------------------------------------
 *
 * PhpConcept Javascript Color Chooser
 * Created by Vincent Blavet (vincent@blavet.net)
 * All right reserved by PhpConcept (http://www.phpconcept.net)
 * GNU GPL Licence
 *
 * For more informations : http://www.phpconcept.net
 * -------------------------------------------------------
 * Demonstration available at http://www.phpconcept.net
 *
 * User Guide :
 *   1. Insert the script pcjscolorchooser.js in your
 *      page, AFTER the BODY tag.
 *
 *   2. Call the Color Chooser by the JavaScript function
 *      PcjsColorChooser(object, property)
 *      Where : 'object' is a valid JavaScript object
 *              'property' a valid property for the object
 *      The selected value will be set like this :
 *      object.property = color
 *
 *   3. Example 1 : Selection as a input/text value :
 *      <form name="form2">
 *        Color :<input type="text" name="colortext">
 *        <input type="button "value="Choose" onClick="PcjsColorChooser(document.forms.form2.colortext,'value')">
 *      </form>
 *
 *   4. Example 2 : Selection as a background table cell :
 *      <form name="form2">
 *        <table><tr><td>Color : </td>
 *        <td width=20 id=testcell>&nbsp;</td>
 *        <td>
 *          <input type="button" name="Submit2" value="Choisir" onClick="PcjsColorChooser(document.all.testcell,'bgColor')">
 *        </td>
 *        </tr></table>
 *      </form>
 * -------------------------------------------------------
 */

// ----- Specific global values
var PcjsDestObject;
var PcjsDestProperty;

// ----- Start color chooser
function PcjsColorChooser(destobject, destproperty)
{
  // ----- Store the destination object
  PcjsDestObject = destobject;
  if (destproperty == "")
    PcjsDestProperty = "value";
  else
    PcjsDestProperty = destproperty;

  // ----- Get the initial value
  eval("PcjsInternalSelectColor(PcjsDestObject."+PcjsDestProperty+")");

  // ----- Select the ilayer
  var obj = document.all['PcjsPopup'];

  // ----- Set the popup position
  obj.style.left = document.body.scrollLeft+event.clientX;
  obj.style.top  = document.body.scrollTop+event.clientY;

  // ----- Close the ilayer
  obj.style.visibility = "visible";
}

// ----- Close function for color chooser without selection
function PcjsInternalClosePopup()
{
  // ----- Select the ilayer
  var obj = document.all['PcjsPopup'];

  // ----- Close the ilayer
  obj.style.visibility = "hidden";
}

// ----- Close function for color chooser with selection
function PcjsInternalSelectClose()
{
  // ----- Select the ilayer
  var obj = document.all['PcjsPopup'];

  // ----- Get the value and paste it to destination object
  PcjsDestObject.value = document.forms.pcjsform.color.value;

  // ----- Look for object type
  eval("PcjsDestObject."+PcjsDestProperty+" = document.forms.pcjsform.color.value");

  // ----- Close the ilayer
  obj.style.visibility = "hidden";
}

// ----- Internal color selection
function PcjsInternalSelectColor(color)
{
  // ----- Paste the color value
  document.forms.pcjsform.color.value = color;

  // ----- Change the color viewer
  document.all.pcjscell.bgColor = color;
}

// ----- Popup window creator
function PcjsGeneratePopup()
{
  // ----- Generate the div tag
  document.write("<div id=PcjsPopup style='position:absolute; left:118px; top:214px; width:212px; height:112px; z-index:1; visibility:hidden; background-color: #FFFFFF; layer-background-color: #FFFFFF; border: 1px none #000000'> ");
  document.write("<table width=100% border=0 cellspacing=0 bgcolor=#0000CC>");
  document.write("<tr><td height=13 width=5></td><td height=13> ");
  document.write("<div align=right><font face='Verdana, Arial, Helvetica, sans-serif' color=#FFFFFF size=2><b><a onClick='PcjsInternalClosePopup()'>x</a></b></font></div>");
  document.write("</td><td height=13 width=5> </td></tr>");
  document.write("<tr> <td height=71 width=5></td><td height=71 bgcolor=#FFFFFF> ");
  document.write("<form name=pcjsform method=post>");
  document.write("<table border=1 cellspacing=0 align=center bordercolor=#FFFFFF>");
  document.write("<tr><td align=center colspan=18><font face='Verdana, Arial, Helvetica, sans-serif' size=2><b><font color=#0000CC size=3>");
  document.write("Color Chooser</font><font color=#0000CC> </font></b></font><br>");
  document.write("</td></tr>");

  for (i=0;i<6;i++)
  {
    document.write("<tr>");
    if (i==0) v_color_i="00";
    if (i==1) v_color_i="33";
    if (i==2) v_color_i="66";
    if (i==3) v_color_i="99";
    if (i==4) v_color_i="CC";
    if (i==5) v_color_i="FF";
    for (j=0;j<6;j++)
    {
      if (j==0) v_color_j="00";
      if (j==1) v_color_j="33";
      if (j==2) v_color_j="66";
      if (j==3) {v_color_j="99"; document.write("<tr>");}
      if (j==4) v_color_j="CC";
      if (j==5) v_color_j="FF";
      for (k=0;k<6;k++)
      {
        if (k==0) v_color_k="00";
        if (k==1) v_color_k="33";
        if (k==2) v_color_k="66";
        if (k==3) v_color_k="99";
        if (k==4) v_color_k="CC";
        if (k==5) v_color_k="FF";
        document.write("<td bgcolor=#"+v_color_i+v_color_j+v_color_k+" onClick=PcjsInternalSelectColor('#"+v_color_i+v_color_j+v_color_k+"') width=10 height=10></td>");
      }
      if (j==5) {v_color_j="99"; document.write("<tr>");}
    }
    document.write("</tr>");
  }

  // ----- Basic color selection
  document.write("<tr><td colspan=18></td><td>");
  document.write("<tr><td colspan=3></td>");
  document.write("<td bgcolor=#000000 onClick=PcjsInternalSelectColor('#000000') width=10 height=10></td>");
  document.write("<td bgcolor=#333333 onClick=PcjsInternalSelectColor('#333333') width=10 height=10></td>");
  document.write("<td bgcolor=#666666 onClick=PcjsInternalSelectColor('#666666') width=10 height=10></td>");
  document.write("<td bgcolor=#999999 onClick=PcjsInternalSelectColor('#999999') width=10 height=10></td>");
  document.write("<td bgcolor=#CCCCCC onClick=PcjsInternalSelectColor('#CCCCCC') width=10 height=10></td>");
  document.write("<td bgcolor=#FFFFFF onClick=PcjsInternalSelectColor('#FFFFFF') width=10 height=10></td>");
  document.write("<td bgcolor=#FF0000 onClick=PcjsInternalSelectColor('#FF0000') width=10 height=10></td>");
  document.write("<td bgcolor=#00FF00 onClick=PcjsInternalSelectColor('#00FF00') width=10 height=10></td>");
  document.write("<td bgcolor=#0000FF onClick=PcjsInternalSelectColor('#0000FF') width=10 height=10></td>");
  document.write("<td bgcolor=#FFFF00 onClick=PcjsInternalSelectColor('#FFFF00') width=10 height=10></td>");
  document.write("<td bgcolor=#00FFFF onClick=PcjsInternalSelectColor('#00FFFF') width=10 height=10></td>");
  document.write("<td bgcolor=#FF00FF onClick=PcjsInternalSelectColor('#FF00FF') width=10 height=10></td>");
  document.write("<td colspan=3></td></tr>");

  document.write("<tr><td colspan=12 align=center>");
  document.write("<font face='Verdana, Arial, Helvetica, sans-serif' size=2 color=#0000CC>Color : </font>");
  document.write("<input type=text name=color size=8 maxlength=8></td>");
  document.write("<td colspan=6 align=center valign=middle> ");
  document.write("<table name=tableau border=0 cellspacing=0 align=center >");
  document.write("<tr>");
  document.write("<td id=pcjscell width=20 height=20 align=center valign=middle>&nbsp;</td>");
  document.write("</tr>");
  document.write("</table>");
  document.write("</td></tr>");
  document.write("<tr><td colspan=18 align=center>");
  document.write("<input type=button name=select value=Select onClick='PcjsInternalSelectClose()'>");
  document.write("</td></tr></table>");


  document.write("</form></td><td height=71 width=5></td></tr>");
  document.write("<tr height=5><td height=5 width=5></td><td height=5></td><td height=5 width=5></td></tr>");
  document.write("</table></div>");
}

// ----- Call the Color Chooser Popup Window generator function
PcjsGeneratePopup();

-->
