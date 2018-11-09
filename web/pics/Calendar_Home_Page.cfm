<html>
<cfoutput>
<head>
<title>Calendar Home Page</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>
</cfoutput>

<body bgcolor="#FFFFFF" text="#000000" link="#333366" vlink="#006699" alink="#660033">
<CFIF not isdefined("NewEvent")>
<CFSET NewEvent=0>
</cfif>
<CFINCLUDE template="Header.cfm">
<CFIF "#NewEvent#" IS "1">
<cfoutput>
<table width="100%" border="0" align="center">
  <tr> 
    <td colspan="8"> 
      <table width="100%" border="0">
        <tr> 
          <td><font face="Arial, Helvetica, sans-serif" size="2"><b><font color="##000000">|
        <a href="index.cfm">Home</a> | Calendar | <a href="Calendar_Sign-Up.cfm">Submit Your Event</a></font></b></font></td>
          <td> 
            <div align="right">
            <font face="Arial, Helvetica, sans-serif" size="2"><b><font color="##333366">#DateFormat(Now(), "MMMM D, YYYY")# </font></b></font>
            </div>
          </td>
        </tr>
      </table>
      <BR>
    </td>
  </tr>
  <tr> 
    <td> 
        <table width="100%" border="0">
          <CFSET fYearStart=#NumberFormat(fYearStart, 9999)#>
          <CFSET fMonthStart=#NumberFormat(fMonthStart, 99)#>
          <CFSET fDayStart=#NumberFormat(fDayStart, 99)#>
          <CFSET InsertStopDate = "">
          <CFSET DoneDate = "">
          </cfoutput>
          <CFIF IsDate("#fMonthStart#/#fDayStart#/#FYearStart#") IS "Yes">
            <CFSET StartDate = CreateDate(fYearStart, fMonthStart, fDayStart)>
          <CFELSE>
            <cfset ErrorText="Valid Start Date not entered, please go back and enter a valid Start Date">
            <cfoutput>
              </table></table>
              <HR><H3>Form Entries Incomplete or Invalid</H3>One or more problems exist with the data you have entered.<UL><LI>#errortext#<P></UL>Use the <I>Back</I> button on your web browser to return to the previous page and correct the listed problems.<P><HR>
            </cfoutput>
            <CFABORT>
          </CFIF>
          <CFIF isDefined("fYearEnd") AND isDefined("fMonthEnd") AND isDefined("fDayEnd")>
            <CFSET YearEnd="#fYearEnd#">
            <CFSET MonthEnd="#fMonthEnd#">
            <CFSET DayEnd="#fDayEnd#">
            <CFIF YearEnd IS "" AND MonthEnd IS "" AND DayEnd IS "">
            <CFELSE>
              <CFIF IsDate("#fMonthEnd#/#fDayEnd#/#FYearEnd#") IS "Yes">
                <CFSET StopDate = CreateDate(fYearEnd, fMonthEnd, fDayEnd)>
                <CFSET InsertStopDate = " #CreateODBCDate(StopDate)#,">
                <CFSET DoneDate = "EndDate, ">
              <CFELSE>
                <cfset ErrorText="Valid End Date not entered, please go back and enter a valid End Date">
                <cfoutput>
                  </table></table>
                  <HR><H3>Form Entries Incomplete or Invalid</H3>One or more problems exist with the data you have entered.<UL><LI>#errortext#<P></UL>Use the <I>Back</I> button on your web browser to return to the previous page and correct the listed problems.<P><HR>
                </cfoutput>
                <CFABORT>
              </cfif>
            </CFIF>
          </CFIF>
          <cfif isdefined("StopDate")>
            <CFIF DateDiff("D", StartDate, StopDate) LT 0>
              <cfset ErrorText="End Date of Event is Before Start Date.">
              <cfoutput>
                </table></table>
                <HR><H3>Form Entries Incomplete or Invalid</H3>One or more problems exist with the data you have entered.<UL><LI>#errortext#<P></UL>Use the <I>Back</I> button on your web browser to return to the previous page and correct the listed problems.<P><HR>
              </cfoutput>
              <CFABORT>
            </cfif>
          </cfif>
          <CFIF DateDiff("D", Now(), StartDate) LT 0>
            <cfset ErrorText="Start Date of Event has already occured.">
            <cfoutput>
              </table></table>
              <HR><H3>Form Entries Incomplete or Invalid</H3>One or more problems exist with the data you have entered.<UL><LI>#errortext#<P></UL>Use the <I>Back</I> button on your web browser to return to the previous page and correct the listed problems.<P><HR>
            </cfoutput>
            <CFABORT>
          </cfif>
          <CFIF IsNumericDate(StartDate)>
              <CFSET ExpireDate = Now()>
              <CFIF fDuration IS 1>
                <CFSET ExpireDate = DateAdd('M',2,ExpireDate)>
              </cfif>
              <CFIF fDuration IS 2>
                <CFSET ExpireDate = DateAdd('M',1,ExpireDate)>
              </cfif>
              <CFIF fDuration IS 3>
                <CFSET ExpireDate = DateAdd('WW',2,ExpireDate)>
              </cfif>
              <CFIF fDuration IS 4>
                <CFSET ExpireDate = DateAdd('WW',1,ExpireDate)>
              </cfif>
              <CFINCLUDE template="GetID.cfm">
              <CFQUERY datasource="#JobsDataSource#" name="AddEvent">
                INSERT INTO Calendar(JobID, Name, Title, Company, Address, City, Country, Zip, ZipPlus, PhoneArea, Phone, Email, EventID, EventBody, AdID, BeginDate, #DoneDate#PostDate, ExpirationDate)
                VALUES('#NextNumber#','#ReplaceList(fName, """,',|", "`,`,`")#','#ReplaceList(fTitle, """,',|", "`,`,`")#','#ReplaceList(fCompany, """,',|", "`,`,`")#','#ReplaceList(fAddress, """,',|", "`,`,`")#','#ReplaceList(fCity, """,',|", "`,`,`")#','#ReplaceList(fCountry, """,',|", "`,`,`")#','#ReplaceList(fZip, """,',|", "`,`,`")#','#ReplaceList(fZipPlus, """,',|", "`,`,`")#','#ReplaceList(fPhoneArea, """,',|", "`,`,`")#','#ReplaceList(fPhone, """,',|", "`,`,`")#','#ReplaceList(fEmail, """,',|", "`,`,`")#','#fEventID#','#ReplaceList(fEventBody, """,',|", "`,`,`")#','#fDuration#', #CreateODBCDate(StartDate)#, #InsertStopDate##CreateODBCDate(Now())#, #CreateODBCDate(ExpireDate)#)
              </cfquery>
              <cfoutput>
              <tr>
                <td colspan="5"><font face="Arial, Helvetica, sans-serif" size="2" color="##660033">Thank you for submitting a Calendar event.</font></td>
              <tr>
                <td>&nbsp;</td>
              </tr>
              <tr bgcolor="##CCCC99"> 
                <td colspan="5"><font face="Arial, Helvetica, sans-serif" size="2" color="##660033"><b><font color="##333366">Select 
                  a category below to view the latest events:</font></b></font></td>
              </tr>
              <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
              </tr>
              <tr> 
                <td> 
                  <div align="center"><b><font face="Arial, Helvetica, sans-serif" size="2"><a href="Calendar_Events.cfm?EventID=1">Job Fairs</a></font></b></div>
                </td>
                <td> 
                  <div align="center"><b><font face="Arial, Helvetica, sans-serif" size="2"><a href="Calendar_Events.cfm?EventID=2">Conventions</a></font></b></div>
                </td>
                <td> 
                  <div align="center"><b><font face="Arial, Helvetica, sans-serif" size="2"><a href="Calendar_Events.cfm?EventID=3">Workshops</a></font></b></div>
                </td>
                <td> 
                  <div align="center"><b><font face="Arial, Helvetica, sans-serif" size="2"><a href="Calendar_Events.cfm?EventID=4">Symposiums</a></font></b></div>
                </td>
                <td> 
                  <div align="center"><b><font face="Arial, Helvetica, sans-serif" size="2"><a href="Calendar_Events.cfm?EventID=5">Other Events</a></font></b></div>
                </td>
              </tr>
              </cfoutput>
              <cfmail to="#femail#" server="#SMTPServer#" from="contact@journalismjobs.com" subject="JournalismJobs.com Calendar Event Posting">Thank you for posting your calendar event on JournalismJobs.com!
Your posting will expire on: #DateFormat(ExpireDate,"MMMM D, YYYY")#.
Please send an e-mail to contact@journalismjobs.com if you have a question.</cfmail>
          <cfelse>
            <cfoutput>
            <tr>
              <td colspan="5"><font face="Arial, Helvetica, sans-serif" size="2" color="##660033">Sorry, you did not enter a valid date for your event to start.  Please press the back button to go back and make corrections.</font></td>
            <tr>
            </cfoutput>
          </cfif>
        <cfoutput>
        </table>
        <p align="center">&nbsp;</p>
    </td>
  </tr>
</table>
</cfoutput>
<CFELSE>
<cfoutput>
  <table width="100%" border="0" align="center">
    <tr> 
      <td colspan="8"> 
        <table width="100%" border="0">
          <tr> 
            <td><font face="Arial, Helvetica, sans-serif" size="2"><b><font color="##000000">|
          <a href="index.cfm">Home</a> | Calendar | <a href="Calendar_Sign-Up.cfm">Submit Your Event</a></font></b></font></td>
            <td> 
            <div align="right">
              <font face="Arial, Helvetica, sans-serif" size="2"><b><font color="##333366">#DateFormat(Now(), "MMMM D, YYYY")# </font></b></font>
              </div>
            </td>
          </tr>
        </table>
        <BR>
      </td>
    </tr>
    <tr> 
      <td> 
        <table width="100%" border="0">
          <tr bgcolor="##CCCC99"> 
            <td colspan="5"><font face="Arial, Helvetica, sans-serif" size="2" color="##660033"><b><font color="##333366">Select 
              a category below to view the latest events:</font></b></font></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr> 
            <td> 
              <div align="center"><b><font face="Arial, Helvetica, sans-serif" size="2"><a href="Calendar_Events.cfm?EventID=1">Job Fairs</a></font></b></div>
            </td>
            <td> 
              <div align="center"><b><font face="Arial, Helvetica, sans-serif" size="2"><a href="Calendar_Events.cfm?EventID=2">Conventions</a></font></b></div>
            </td>
            <td> 
              <div align="center"><b><font face="Arial, Helvetica, sans-serif" size="2"><a href="Calendar_Events.cfm?EventID=3">Workshops</a></font></b></div>
            </td>
            <td> 
              <div align="center"><b><font face="Arial, Helvetica, sans-serif" size="2"><a href="Calendar_Events.cfm?EventID=4">Symposiums</a></font></b></div>
            </td>
            <td> 
              <div align="center"><b><font face="Arial, Helvetica, sans-serif" size="2"><a href="Calendar_Events.cfm?EventID=5">Other Events</a></font></b></div>
            </td>
          </tr>
        </table>
        <p align="center">&nbsp;</p>
    </td>
  </tr>
</table>
</cfoutput>
</CFIF>
<CFINCLUDE template="Footer.cfm">
</body>
</html>