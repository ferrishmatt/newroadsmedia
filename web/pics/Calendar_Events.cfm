<CFIF not isdefined("EventID")>
  <CFSET uEventID=1>
<CFELSE>
  <CFSET uEventID=#EventID#>
</cfif>

<CFIF not isdefined("Iteration")>
  <CFSET Iteration=1>
<CFELSE>
  <CFIF not isdefined("Back")>
    <CFSET Iteration=#Iteration#+1>
  <CFELSE>
    <CFSET Iteration=#Iteration#-1>
  </cfif>
</cfif>

<html>
<cfoutput>
<head>
<title>Calendar Events</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>
</cfoutput>

<body bgcolor="#FFFFFF" text="#000000" link="#333366" vlink="#006699" alink="#660033">
<CFINCLUDE template="Header.cfm">
<CFQUERY Name="EventName" datasource="jobs">
SELECT Event, EventID, DisplayOrder
FROM Event
WHERE EventID = #uEventID#
</cfquery>
<CFQUERY Name="Calendar" datasource="jobs">
SELECT Calendar.JobID, Calendar.Name, Calendar.Title, Calendar.Company, Calendar.Address,
Calendar.City, Calendar.Country, Calendar.Zip, Calendar.ZipPlus, Calendar.PhoneArea,
Calendar.Phone, Calendar.EventBody, Calendar.EventID, Calendar.Email, Calendar.PostDate,
Calendar.ExpirationDate, Calendar.BeginDate, Calendar.EndDate, Calendar.EventID
FROM Calendar
WHERE Calendar.EventID = #uEventID#
AND ExpirationDate >= #CreateODBCDate(Now())#
ORDER BY Calendar.BeginDate
</cfquery>
<CFIF Calendar.RecordCount IS "0">
<CFOUTPUT>
<table width="100%" border="0" align="center">
  <tr> 
    <td colspan="8"> 
      <table width="100%" border="0">
        <tr> 
          <td><font face="Arial, Helvetica, sans-serif" size="2"><b><font color="##000000">|
          <a href="index.cfm">Home</a> | Calendar, </cfoutput><cfoutput query="EventName" Maxrows=1>#ReplaceList(HTMLEditFormat(Event), "``, `", "', '")#</cfoutput><cfoutput></font></b></font></td>
          <td> 
            <div align="right"><font face="Arial, Helvetica, sans-serif" size="2"><b><font color="##333366">#DateFormat(Now(), "MMMM D, YYYY")# </font></b></font></div>
          </td>
        </tr>
      </table>
      <BR>
    </td>
  </tr>
  <tr> 
    <td> 
      <form method="post" action="Calendar_Events.cfm?uEventID=#uEventID#">
        
        <p><font face="Arial, Helvetica, sans-serif" size="2"><u><br>
          </u></font></p>
        <div align="center"> 
          <table width="100%" border="0" cellpadding="4" cellspacing="0">
            <tr> 
              <td width="62%" bgcolor="##CCCC99"><font face="Arial, Helvetica, sans-serif" size="3" color="##333366"><b></cfoutput><cfoutput query="EventName" Maxrows=1>#ReplaceList(HTMLEditFormat(Event), "``, `", "', '")#</cfoutput><cfoutput></b></font></td>
              <td width="19%" bgcolor="##CCCC99">&nbsp;</td>
              <td width="19%" bgcolor="##CCCC99">&nbsp;</td>
            </tr>
            </cfoutput><cfoutput query="EventName" MaxRows="1">
            <tr> 
              <td width="62%" valign="top"><font face="Arial, Helvetica, sans-serif" size="2"><BR>Sorry, there are no events for #ReplaceList(HTMLEditFormat(Event), "``, `", "', '")#.</font></td>
              <td width="19%" valign="top"><font face="Arial, Helvetica, sans-serif" size="2"></font></td>
              <td width="19%" valign="top"><font face="Arial, Helvetica, sans-serif" size="2"></font></td>
            </tr>
            </cfoutput><cfoutput>
          </table>
          <p>&nbsp;</p>
        </div>
      </form>
    </td>
  </tr>
</table>
</cfoutput>
<CFELSE>
<CFOUTPUT>
<table width="100%" border="0" align="center">
  <tr> 
    <td colspan="8"> 
      <table width="100%" border="0">
        <tr> 
          <td><font face="Arial, Helvetica, sans-serif" size="2"><b><font color="##000000">|
          <a href="index.cfm">Home</a> | Calendar, </cfoutput><cfoutput query="EventName" Maxrows=1>#ReplaceList(HTMLEditFormat(Event), "``, `", "', '")#</cfoutput><cfoutput></font></b></font></td>
          <td> 
            <div align="right"><font face="Arial, Helvetica, sans-serif" size="2"><b><font color="##333366">#DateFormat(Now(), "MMMM D, YYYY")# </font></b></font></div>
          </td>
        </tr>
      </table>
      <BR>
    </td>
  </tr>
  <tr> 
    <td> 
      <form method="post" action="Calendar_Events.cfm?uEventID=#uEventID#">
        
        <p><font face="Arial, Helvetica, sans-serif" size="2"><u><br>
          </u></font></p>
        <div align="center"> 
          <table width="100%" border="0" cellpadding="4" cellspacing="0">
            <tr> 
              <td width="62%" bgcolor="##CCCC99"><font face="Arial, Helvetica, sans-serif" size="3" color="##333366"><b></cfoutput><cfoutput query="EventName" Maxrows=1>#ReplaceList(HTMLEditFormat(Event), "``, `", "', '")#</cfoutput><cfoutput>: </b></font>
              <font face="Arial, Helvetica, sans-serif" size="3" color="##000000"><b>#Calendar.RecordCount# Event<CFIF #Calendar.RecordCount# GT 1>s</CFIF></b></font></td>
              <td width="19%" bgcolor="##CCCC99"><b><font size="3" face="Arial, Helvetica, sans-serif" color="##333366">| 
                Begins</font></b></td>
              <td width="19%" bgcolor="##CCCC99"><font face="Arial, Helvetica, sans-serif" size="3" color="##333366"><b>| 
                Ends </b></font></td>
            </tr>
            <cfset StartAt = #Iteration#*20-19></cfoutput>
            <cfoutput query="Calendar" startrow="#StartAt#" maxrows="20">
            <tr> 
              <td width="62%" valign="top"><font face="Arial, Helvetica, sans-serif" size="2">#ReplaceList(ParagraphFormat(HTMLEditFormat(EventBody)), "``, `", "', '")#</font></td>
              <td width="19%" valign="top"><font face="Arial, Helvetica, sans-serif" size="2">| #DateFormat(BeginDate,"MMMM D, YYYY")#</font></td>
              <td width="19%" valign="top"><font face="Arial, Helvetica, sans-serif" size="2">| <CFIF "#DateFormat(EndDate, "MMMM D, YYYY")#" IS "">Not Specified<CFELSE>#DateFormat(EndDate, "MMMM D, YYYY")#</CFIF></font></div></td>
            </tr>
            </cfoutput><cfoutput>
          </table>
    </td>
  </tr>
</table>
<div align="center">
<table valign="top">
<tr valign="top" align="center">
  <td valign="top" align="center">
  <CFIF #Iteration# GT 1>
    <p><INPUT type="hidden" value="#Iteration#" name="Iteration"></INPUT>
    <INPUT type="hidden" value="1" name="Back"></INPUT>
    <INPUT type="submit" value="See Previous Listings"></INPUT></p></FORM>
  </CFIF>
  </td>
  <td valign="top" align="center">
  <CFIF Calendar.RecordCount GT #Iteration#*20>
    <p><FORM method="post" action="Calendar_Events.cfm?uEventID=#uEventID#">
    <INPUT type="hidden" value="#Iteration#" name="Iteration"></INPUT>
    <INPUT type="submit" value="See More Listings"></INPUT></FORM></p>
  </CFIF>
  </td>
</tr>
</table>
</div>
</cfoutput>
</cfif>
<CFINCLUDE template="Footer.cfm">
</body>
</html>