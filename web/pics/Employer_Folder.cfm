<html>
<cfoutput>
<head>
<title>Employer Folder</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>
</cfoutput>

<body bgcolor="#FFFFFF">
<CFINCLUDE template="Header.cfm">
<cfinclude template="NavigationHeader.cfm">
<!--- check to see if the eMail addr. and Password have been passed to this form as parameters. --->
<!--- or session variable has been set --->
<cfif IsDefined("fEmail") AND IsDefined("fPassword") OR IsDefined("Session.EmployerID")>
    <!--- check to see if the eMail and Password combination are in our Employer table. --->
    <cfquery name="GetCompany" datasource="#JobsDataSource#">
        SELECT EmployerID,Company,MembershipExpirationDate
        FROM Employer
        <cfif IsDefined("fEmail") AND IsDefined("fPassword")>
            WHERE Email='#fEmail#'
            AND Password='#fPassword#'
        <cfelse>
           WHERE EmployerID=#Session.EmployerID#
        </cfif>
    </cfquery>
    <cfif #GetCompany.Recordcount# LT 1>
    <br><font face="Arial, Helvetica, sans-serif" size="2"><b>
    No match found for e-mail address and/or Password.</b></font><br>
        <CFINCLUDE template="Footer.cfm">
        <cfabort>
    <cfelseif #GetCompany.MembershipExpirationDate# LT Now()>
        <br><font face="Arial, Helvetica, sans-serif" size="2"><b>
        Your Membership has expired.</b></font><br>
        <CFINCLUDE template="Footer.cfm">
        <cfabort>
    <cfelse>
      <cfset Session.EmployerID = GetCompany.EmployerID>
    </cfif>
<cfelse>
    <br><font face="Arial, Helvetica, sans-serif" size="2"><b>
    Please enter your e-mail address and Password on the <a href="Employer_Folder_Home_Page.cfm">Employer Folder Home Page.</a></b></font><br>
    <CFINCLUDE template="Footer.cfm">
    <cfabort>
</cfif>
<cfquery name="GetListings" datasource="#JobsDataSource#">
    SELECT Position,ExpirationDate,ApplicantCount,JobID
    FROM JobLookup
    WHERE EmployerID=#Session.EmployerID#
    ORDER BY JobID
</cfquery>
<cfoutput>
<table border="0" cellpadding="0" cellspacing="0" width="98%" align="center">
  <tr> 
    <td colspan="4"> 
      <font face="Arial, Helvetica, sans-serif" size="2"><b><font color="##000000">|
      <a href="index.cfm">Home</a> | <a href="FAQ_About_Employer_Folder.cfm">FAQ About the Employer Folder</a> | Employer Folder</font></b></font>
    </td>
    <td colspan="4" align="right"> 
      <font face="Arial, Helvetica, sans-serif" size="2"><b><font color="##333366">#DateFormat(Now(), "MMMM D, YYYY")#
       </font></b></font>
      <BR>
    </td>
  </tr>
</table>
<p><font face="Arial, Helvetica, sans-serif" size="2"><b><font size="4">
    #GetCompany.Company#</font></b>, welcome to your Employer Folder!<br>
    </cfoutput>
    
    <cfif GetListings.RecordCount>
        <cfquery name="GetExpirationDate" datasource="#JobsDataSource#" maxrows=1>
            SELECT Max(ExpirationDate) AS MaxExpirationDate
            FROM JobLookup
        </cfquery>
        <cfoutput query="GetExpirationDate">
            Your folder will remain in our database until: <b>#DateFormat(DateAdd("WW",4,GetExpirationDate.MaxExpirationDate), "MMMM D, YYYY")#</b>, which 
            is <b>four weeks</b> after your last job ad expires.  <a href="Post_Job.cfm?DisplayEmployerInfo=YES">Post a new job.</a>
        </cfoutput>
        <!--- write newly calculated expiration date to employer folder --->
        <cfquery name="UpdateExpirationDate" datasource="#JobsDataSource#">
            UPDATE Employer
            SET MembershipExpirationDate=#CreateODBCDate(DateAdd("WW",4,GetExpirationDate.MaxExpirationDate))#
            WHERE EmployerID=#Session.EmployerID#
        </cfquery>
     <cfelse>
        <br><b><font face="Arial, Helvetica, sans-serif" size="2" color="##000000">
        No Job Listings found for current employer.</font></b>
     </cfif>
    <cfoutput>
</font></p>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
    </cfoutput>
    <cfoutput query="GetListings" startrow = "#StartAt#" maxrows = "#StepSize#">
    <tr bgcolor="##CCCC99"> 
      <td height="26" width="230"><b><font face="Arial, Helvetica, sans-serif" size="2" color="##003366">| 
        Position Open: </font></b></td>
      <td height="26" width="311"><font color="##003366"><b><font face="Arial, Helvetica, sans-serif" size="2" color="##003366">| 
        </font><font face="Arial, Helvetica, sans-serif" size="2">Job Ad Expires: 
        </font></b></font></td>
      <td height="26" width="164"><font color="##003366"><b><font face="Arial, Helvetica, sans-serif" size="2" color="##003366">| 
        </font><font face="Arial, Helvetica, sans-serif" size="2">You have:</font></b></font></td>
    </tr>
    <tr> 
      <td width="230">
        <p>| <font face="Arial, Helvetica, sans-serif" size="2"><a href="employer_folder_applicants.cfm?JobID=#JobID#&View=position">
        #GetListings.Position#</a></font></p>
      </td>
      <td width="311"><font face="Arial, Helvetica, sans-serif" size="2">
      | #DateFormat(GetListings.ExpirationDate,'MMMM D, YYYY')#</font>
      </td>
      <td width="164"> 
        <p>| <font face="Arial, Helvetica, sans-serif" size="2"></font><font face="Arial, Helvetica, sans-serif" size="2"><a href="employer_folder_applicants.cfm?JobID=#JobID#&View=applicants">
        #GetListings.ApplicantCount# Applicants</a></font></p>
        </td>
    </tr>
    <tr> 
      <td width="230"><font face="Arial, Helvetica, sans-serif" size="2">| <font face="Arial, Helvetica, sans-serif" size="2"><a href="Post_Job.cfm?JobID=#GetListings.JobID#&Mode=Edit">
      Edit Job</a></font></td>
      <td width="311"><font face="Arial, Helvetica, sans-serif" size="2">|</font><font face="Arial, Helvetica, sans-serif" size="2"><a href="Post_Job.cfm?JobID=#GetListings.JobID#&Mode=Renew">
       Renew Job</a></font></td>
      <td width="164"><font face="Arial, Helvetica, sans-serif" size="2">|</font><font face="Arial, Helvetica, sans-serif" size="2"><a href="employer_folder_action.cfm?JobID=#GetListings.JobID#&Action=Delete&Position=#GetListings.Position#">
       Delete Job</a></font></td>
    </tr>
<!--- add a blue line between listings --->
<!---     <tr><td colspan="3" height="1" align="center"><img src="#rootdir#pics/dot_navy.gif" border="0" height="1" width="100%"></td></tr> --->
    </cfoutput>
</table>

<form method="post" action="Employer_Folder.cfm">
<cfset FooterDisplayEmployerInfo = "YES">
<cfinclude template="NavigationFooter.cfm">
</form>
<!--- Debug navigation: #GetJobs.RecordCount# #StartAt# #StepSize# #navigate# --->
<p align="center">&nbsp;</p>
<cfinclude template="Footer.cfm">
</body>
</html>
