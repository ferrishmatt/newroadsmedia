<html>
<cfoutput>
<head>
<title>Employer Folder Action</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>
</cfoutput>
<body bgcolor="#FFFFFF" text="#000000" link="#333366" vlink="#006699" alink="#660033">
<CFINCLUDE template="Header.cfm">
<cfoutput>
<table width="98%" border="0" align="center">
  <tr> 
    <td colspan="8"> 
      <table width="100%" border="0">
        <tr align="right"> 
          <td align="left">
            <div align="left"><font face="Arial, Helvetica, sans-serif" size="2"><b><font color="##000000">| <a href="index.cfm">Home</a> | Employer Folder Action</font></b></font></div>
          </td>
          <td align="right"> 
            <div align="right"><font face="Arial, Helvetica, sans-serif" size="2"><b><font color="##333366">#DateFormat(Now(), "MMMM D, YYYY")# </font></b></font></div>
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>
</cfoutput>
<!--- check if employer has logged on --->
<cfif NOT IsDefined("Session.EmployerID")> 
    <br><font face="Arial, Helvetica, sans-serif" size="2"><b>
    Please enter your e-mail address and Password on the <a href="Employer_Folder_Home_Page.cfm">Employer Folder Home Page.</a></b></font><br>
    <CFINCLUDE template="Footer.cfm">
    <cfabort>
</cfif>
<form method="post" action="Employer_Folder_Delete.cfm">
<cfif Action IS "Delete">
    <cfoutput>
    <!--- if JobID has been passed, prompt user and delete job --->
    <cfif IsDefined("url.JobID")>
        <br><center><font face="Arial, Helvetica, sans-serif" size="2"><b>
        Click the Delete Button to delete the #Position# position, Job Number #JobID#.  You will not be able to recover this position after you delete it.</b></font></center>
        <div align="center"> 
            <BR><BR><input type="submit" name="Delete" value="Delete Job">
        </div>
    </cfif>
    <input type="hidden"  name="hJobID" value=#JobID#>
    </cfoutput>
<cfelseif Action IS "Renew">
    <!--- renew job for 4 weeks, bill employer? --->
    <cfset ExpirationDate = #DateAdd("WW",4,url.ExpirationDate)#>
    <cfquery name="RenewJob" datasource="#JobsDataSource#">
        Update Job
        SET ExpirationDate=#ExpirationDate#
        WHERE JobID=#JobID#
    </cfquery>
    <cfoutput><br><font face="Arial, Helvetica, sans-serif" size="2"><b>
    New Job Listing expiration date is #DateFormat(ExpirationDate,"MMMM D, YYYY")#.</b></font>
    </cfoutput>
    <font face="Arial, Helvetica, sans-serif" size="2">
    Click here to return to <a href="Employer_Folder.cfm">Employer Folder</a>.
    </Font>
</cfif>
</form>
<CFINCLUDE template="Footer.cfm">
</body>
</html>