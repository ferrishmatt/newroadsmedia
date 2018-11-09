<!--- this form requires JobID  and View to be passed as  parameters --->
<!--- view=position displays just the job, while view=applicants displays the job and the applicants --->
<html>
<cfoutput>
<head>
<title>Employer Folder Applicants</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>
</cfoutput>

<body bgcolor="#FFFFFF" text="#000000" link="#333366" vlink="#006699" alink="#660033">
<CFINCLUDE template="Header.cfm">
<!--- check if employer has logged on --->
<cfif NOT IsDefined("Session.EmployerID")>
    <br><font face="Arial, Helvetica, sans-serif" size="2"><b>
    Please enter your e-mail address and Password on the <a href="Employer_Folder_Home_Page.cfm">Employer Folder Home Page.</a></b></font><br>
    <CFINCLUDE template="Footer.cfm">
    <cfabort>
</cfif>
<!--- if JobID not passed as a parameter, set to "0" --->
<cfif NOT IsDefined("JobID")>
    <cfset #JobID# = "0">
</cfif>
<cfinclude template="NavigationHeader.cfm">
<!--- query to retrieve  Listing from db --->
<cfquery name="GetJob" datasource="#JobsDataSource#">
    SELECT *
    FROM JobDisplay
    WHERE JobID = #JobID#
</cfquery>

<CFIF GetJob.Company IS "">
    <CFSET Company ="Not Specified">
<CFELSE>
    <CFSET Company = "#ReplaceList(HTMLEditFormat(GetJob.Company), "``, `", "', '")#">
</cfif>

<CFIF GetJob.Industry IS "">
    <CFSET Industry ="Not Specified">
<CFELSE>
    <CFSET Industry = "#ReplaceList(HTMLEditFormat(GetJob.Industry), "``, `", "', '")#">
</cfif>

<CFIF GetJob.Position IS "">
    <CFSET Position ="Not Specified">
<CFELSE>
    <CFSET Position = "#ReplaceList(HTMLEditFormat(GetJob.Position), "``, `", "', '")#">
</cfif>

<CFIF GetJob.Specialty IS "">
    <CFSET Specialty ="Not Specified">
<CFELSE>
    <CFSET Specialty = "#ReplaceList(HTMLEditFormat(GetJob.Specialty), "``, `", "', '")#">
</cfif>

<CFIF GetJob.Market IS "">
    <CFSET Market ="Not Specified">
<CFELSE>
    <CFSET Market = "#ReplaceList(HTMLEditFormat(GetJob.Market), "``, `", "', '")#">
</cfif>

<CFIF GetJob.Experience IS "">
    <CFSET Experience ="Not Specified">
<CFELSE>
    <CFSET Experience = "#ReplaceList(HTMLEditFormat(GetJob.Experience), "``, `", "', '")#">
</cfif>

<CFIF GetJob.LocationDescription IS "">
    <CFSET LocationDescription ="Not Specified">
<CFELSE>
    <CFSET LocationDescription = "#ReplaceList(HTMLEditFormat(GetJob.LocationDescription), "``, `", "', '")#">
</cfif>

<CFIF GetJob.JobType IS "">
    <CFSET JobType ="Not Specified">
<CFELSE>
    <CFSET JobType = "#ReplaceList(HTMLEditFormat(GetJob.JobType), "``, `", "', '")#">
</cfif>

<CFIF GetJob.Salary IS "">
    <CFSET Salary ="Not Specified">
<CFELSE>
    <CFSET Salary = "#ReplaceList(HTMLEditFormat(GetJob.Salary), "``, `", "', '")#">
</cfif>

<cfoutput>
<table border="0" cellpadding="0" cellspacing="0" width="98%" align="center">
  <tr> 
    <td colspan="8"> 
      <table width="100%" border="0">
        <tr> 
          <td><font face="Arial, Helvetica, sans-serif" size="2"><b><font color="##000000">|
        <a href="index.cfm">Home</a> | Employer Folder, Applicants</font></b></font></td>
          <td> 
            <div align="right"><font face="Arial, Helvetica, sans-serif" size="2"><b><font color="##333366">#DateFormat(Now(), "MMMM D, YYYY")# </font></b></font></div>
          </td>
        </tr>
      </table>
      <BR>
    </td>
  </tr>
</table>
<form method="post" action="Employer_Folder_Applicants.cfm">
  <p>
  <table border="0" cellspacing="0" cellpadding="4">
    <tr> 
      <td> 
        <div align="right"><font face="Arial, Helvetica, sans-serif" size="2">Company 
          :</font></div>
      </td>
      <td> 
        <div align="left"><font color="##660033"><b><font face="Arial, Helvetica, sans-serif" size="2">
        #Company#</font></b></font></div>
      </td>
    </tr>
    <tr> 
      <td> 
        <div align="right"><font face="Arial, Helvetica, sans-serif" size="2">Job 
          I.D. Number:</font></div>
      </td>
      <td> 
        <div align="left"><font color="##660033"><b><font face="Arial, Helvetica, sans-serif" size="2">
        #GetJob.JobID#<a name="job_id"></a></font></b></font></div>
      </td>
    </tr>
    <tr> 
      <td> 
        <div align="right"><font face="Arial, Helvetica, sans-serif" size="2">Ad 
          Expires:</font></div>
      </td>
      <td> 
        <div align="left"><font face="Arial, Helvetica, sans-serif" size="2" color="##660033"><b>
        #DateFormat(GetJob.ExpirationDate,"MMMM D, YYYY")#</b></font></div>
      </td>
    </tr>
    <tr> 
      <td> 
        <div align="right"><font face="Arial, Helvetica, sans-serif" size="2">Number 
          of <br>
          Applicants:</font></div>
      </td>
      <td valign="top"> 
        <div align="left"><font face="Arial, Helvetica, sans-serif" size="2" color="##660033"><b>
        #GetJob.ApplicantCount#
        </b></font><font face="Arial, Helvetica, sans-serif" size="2"><a name="number_applicants"></a></font></div>
      </td>
    </tr>
    <tr> 
      <td> 
        <div align="right"><font face="Arial, Helvetica, sans-serif" size="2">Industry:</font></div>
      </td>
      <td> 
        <div align="left"><font face="Arial, Helvetica, sans-serif" size="2" color="##660033"><b>
        #Industry#
        </b></font></div>
      </td>
    </tr>
    <tr> 
      <td> 
        <div align="right"><font face="Arial, Helvetica, sans-serif" size="2">Position:</font></div>
      </td>
      <td> 
        <div align="left"><font face="Arial, Helvetica, sans-serif" size="2" color="##660033"><b>
        #Position#</b></font></div>
      </td>
    </tr>
    <tr> 
      <td> 
        <div align="right"><font face="Arial, Helvetica, sans-serif" size="2">Specialty: 
          </font></div>
      </td>
      <td> 
        <div align="left"><font face="Arial, Helvetica, sans-serif" size="2" color="##660033"><b> 
        #Specialty#</b></font></div>
      </td>
    </tr>
    <tr> 
      <td> 
        <div align="right"><font face="Arial, Helvetica, sans-serif" size="2">Market:</font></div>
      </td>
      <td> 
        <div align="left"><font face="Arial, Helvetica, sans-serif" size="2" color="##660033"><b> 
        #Market#</b></font></div>
      </td>
    </tr>
    <tr> 
      <td> 
        <div align="right"><font face="Arial, Helvetica, sans-serif" size="2">Experience:</font></div>
      </td>
      <td> 
        <div align="left"><font face="Arial, Helvetica, sans-serif" size="2" color="##660033"><b>
        #Experience#</b></font></div>
      </td>
    </tr>
    <tr> 
      <td> 
        <div align="right"><font face="Arial, Helvetica, sans-serif" size="2">Location: 
          </font></div>
      </td>
      <td> 
        <div align="left"><font face="Arial, Helvetica, sans-serif" size="2" color="##660033"><b>
        #LocationDescription#</b></font></div>
      </td>
    </tr>
    <tr> 
      <td> 
        <div align="right"><font face="Arial, Helvetica, sans-serif" size="2">Job 
          Type:</font></div>
      </td>
      <td> 
        <div align="left"><font face="Arial, Helvetica, sans-serif" size="2" color="##660033"><b>
        #JobType#</b></font></div>
      </td>
    </tr>
    <tr> 
      <td> 
        <div align="right"><font face="Arial, Helvetica, sans-serif" size="2">Salary:</font></div>
      </td>
      <td> 
        <div align="left"><font face="Arial, Helvetica, sans-serif" size="2" color="##660033"><b>
        #Salary#</b></font></div>
      </td>
    </tr>
    <tr> 
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
  </table>
         <!--- Display the body of the ad in Paragraph Format. Do not process any embedded HTML tags. --->
        <!---<p><font face="Arial, Helvetica, sans-serif" size="2">
          #ReplaceList(ParagraphFormat(HTMLEditFormat(GetJob.AdBody)), "``, `", "', '")#
        </font></p>--->
        <p><font face="Arial, Helvetica, sans-serif" size="2"><B>Description</b><BR>
          #ReplaceList(ParagraphFormat(HTMLEditFormat(GetJob.AdDescription)), "``, `", "', '")#
        </font></p>
        <p><font face="Arial, Helvetica, sans-serif" size="2"><B>Qualifications</b><BR>
          #ReplaceList(ParagraphFormat(HTMLEditFormat(GetJob.AdQualifications)), "``, `", "', '")#
        </font></p>
  </cfoutput>
    <!--- if someone gets to this page by typing in the url, etc. --->
    <cfif NOT IsDefined("url.View") >
      <cfset #View# = "position">
    </cfif>
    <cfif #View# IS "applicants">
      <!--- begin display of applicant info here --->
      <cfquery name="GetListings" datasource="#JobsDataSource#">
        SELECT Email,ApplicationDate,Rating,HomePhone,ResumeID,ApplicationID
        FROM Application
        WHERE JobID=#JobID#
        ORDER BY DisplayOrder
      </cfquery>
      <cfoutput>  
        <table border="0" width="100%" cellpadding="4" cellspacing="0">
          <tr bgcolor="##CCCC99"> 
            <td width="34%" bgcolor="##CCCC99"> 
              <div align="left"><font color="##003366"><b><font face="Arial, Helvetica, sans-serif" size="2" color="##003366">| 
                </font><font face="Arial, Helvetica, sans-serif" size="2">Applicant</font></b></font></div>
            </td>
            <td width="30%"> 
              <div align="left"><font color="##003366"><b><font face="Arial, Helvetica, sans-serif" size="2" color="##003366">| 
                </font><font face="Arial, Helvetica, sans-serif" size="2">Date Applied</font></b></font></div>
            </td>
            <td width="15%"> 
              <div align="left"><font color="##003366"><b><font face="Arial, Helvetica, sans-serif" size="2" color="##003366">| 
                </font><font face="Arial, Helvetica, sans-serif" size="2">Rating</font></b></font></div>
            </td>
            <td width="21%"> 
              <div align="left"><font color="##003366"><b><font face="Arial, Helvetica, sans-serif" size="2" color="##003366">| 
                </font><font face="Arial, Helvetica, sans-serif" size="2">Phone</font></b></font></div>
            </td>
          </tr>
          </cfoutput>
          <a name="Applicants"></a>
          <cfoutput query="GetListings" startrow="#StartAt#" maxrows="#StepSize#">
          <tr> 
            <td width="34%"> 
              <div align="left"><p>| <font face="Arial, Helvetica, sans-serif" size="2"><b><a href="Search_Resume_Individual_Listing.cfm?ResumeID=#GetListings.ResumeID#&ApplicationID=#GetListings.ApplicationID#&JobID=#JobID#">
                #GetListings.eMail#
                </a></b></font></p>
              </div>
            </td>
            <td width="30%"> 
              <div align="left"><font face="Arial, Helvetica, sans-serif" size="2">| 
                #DateFormat(GetListings.ApplicationDate,"MMMM D, YYYY")#</font></div>
            </td>
            <td width="15%"> 
              <div align="left"><font face="Arial, Helvetica, sans-serif" size="2">| 
                #GetListings.Rating#</font></div>
            </td>
            <td width="21%"> 
              <div align="left"><font face="Arial, Helvetica, sans-serif" size="2">| 
                #GetListings.HomePhone#</font></div>
            </td>
          </tr>
        </cfoutput>
        </table>
        <cfinclude template="NavigationFooter.cfm">
        <cfoutput>
        <input type="hidden" name="JobID" value="#JobID#">
        </cfoutput>
    </cfif>
    <br>
</form>
<CFINCLUDE template="Footer.cfm">
</body>
</html>
