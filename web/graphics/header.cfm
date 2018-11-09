<cfoutput>
	<table>
		<TR>
			<TD>
			<!--- <a href="http://www.journalismjobs.com"><img src="http://www.journalismjobs.com/images/header_logo.gif" border="0"></a> ---><a href="http://www.journalismjobs.com"><img src="#rootdir#images/img.small.logo.gif" width="100" height="60" align="left" alt="JournalismJobs.com" border="0"></a> 
			</TD>
			<TD>
				<a href="http://www.insidesessions.com/products/prwr_e.asp?rid=1493" target="_blank"><img src="graphics/banner_insidesessions1.gif" width=468 height=60 border=0 ALT="Click here to visit our sponsor"></a>
			</TD>
		</TR>
	</table>
</cfoutput>

<cfparam name="jotd" default="0">

<!--- == Header.cfm - JournalismJobs.com Header File =============================== --->


<!--- Set this also on index.cfm --->
<!--- ============================================================================== --->


	<CFIF NOT isdefined("header")>
		<CFSET header="1">
	</cfif>
	<CFIF NOT isdefined("SMTPServer")>
		<!--- <CFSET SMTPServer="mail.journalismjobs.com"> --->
		<!--- <CFSET SMTPServer="mail3.registeredsite.com"> --->
		<CFSET SMTPServer="mailhub.registeredsite.com">
	</cfif>

	
	
<cfif ParameterExists(securepage)>
	<cfset unsecurerootdir=rootdir>
	<cfset rootdir=securerootdir>
<cfelse>
	<cfset unsecurerootdir=rootdir>
</cfif>
<CFIF header IS "1"><CFOUTPUT><table border="0" cellpadding="0" cellspacing="0" width="98%" align="center">
  <!--- <tr bgcolor="##CCCC99"> ---> 
<!---
<tr bgcolor="##333399">
    <td colspan="8"> 
	
      <table width="600" border="0" cellpadding="0" cellspacing="0" align="center">
	<tr> 
	  <td> 
	    <p><!--- <a href="index.cfm"><img src="#rootdir#images/img.small.logo.gif" width="100" height="60" vspace="12" hspace="8" align="left" alt="JournalismJobs.com" border="0"></a> --->

<img src="/graphics/logo_header3.gif" width=135 height=40 vspace=6 hspace=8 align="left" alt="JournalismJobs.com" border=0>

<cfif jotd eq 1>
	<!--- <a href="http://www.mjfellows.org"><img src="graphics/banner_mjfellows.gif" width=234 height=60 border=0 vspace="6" hspace="8" ALT="Click here to visit our sponsor"></a> --->
	
	<A HREF="http://kansas.valueclick.com/redirect?host=h0102406&b=header&v=0" TARGET="newwindow"><IMG BORDER="0" WIDTH="468" HEIGHT="60" vspace="6" hspace="8" ALT="Click here to visit our sponsor" SRC="http://kansas.valueclick.com/cycle?host=h0102406&b=header&noscript=1"></A>
	<cfelseif IsDefined('xJobTypeID')>
	<A HREF="http://www.proudmagazine.com/" TARGET="newwindow"><IMG BORDER="0" WIDTH="468" HEIGHT="60" vspace="6" hspace="8" ALT="Click here to visit our sponsor" SRC="#unsecurerootdir#graphics/banner_proud.gif"></A>
<cfelseif IsDefined('city_search')>
	<cfif city_search eq "DC">
		<A HREF="http://npc.press.org/who/mbrshp.htm" TARGET="newwindow"><IMG BORDER="0" WIDTH="468" HEIGHT="60" vspace="6" hspace="8" ALT="Click here to visit National Press Club" SRC="graphics/banner_npc.jpg"></A>
	<cfelse>
		<A HREF="http://kansas.valueclick.com/redirect?host=h0102406&b=header&v=0" TARGET="newwindow"><IMG BORDER="0" WIDTH="468" HEIGHT="60" vspace="6" hspace="8" ALT="Click here to visit our sponsor" SRC="http://kansas.valueclick.com/cycle?host=h0102406&b=header&noscript=1"></A>
	
		</cfif>
<cfelse>
	
	</cfif>
<!--- <A HREF="http://barnesandnoble.bfast.com/booklink/click?sourceid=510101&categoryid=homepage" target="newwindow"><img src="#rootdir#pics/banners/barnesnoble2.gif" width="468" height="60" vspace="12" hspace="8" align="right" border=0> --->
	  </td>
	</tr>
      </table>
    </td>
  </tr>
  --->
  <tr> 
    <td colspan="8"> 
      <table width="100%" border="0">
	<tr> 
	  <td colspan="2"><font face="Arial, Helvetica, sans-serif" size="2"><b><font color="##660000">Job Categories:</font></b></font> <font size="1" face="Arial, Helvetica, sans-serif"><b><a href="/Search_Jobs.cfm?Media=Newspapers&IndustryID=1">Newspapers / Wire Services</a> 
      | <a href="/Search_Jobs.cfm?Media=TV&IndustryID=2,3">TV / Radio</a> | <a href="/Search_Jobs.cfm?Media=Magazines&IndustryID=4">Magazines / Publishing</a> | <a href="/Search_Jobs.cfm?Media=Online+Media&IndustryID=5,6,7,8,9,10,11,12,13">Online 
      Media / Other Industries </b></font>
	  </td>
<!--- 	  <td colspan="2"><font face="Arial, Helvetica, sans-serif" size="2"><b><font color="##660000">Search 
      Jobs:</font></b></font> <font size="1" face="Arial, Helvetica, sans-serif"><b><a href="/search_results.cfm?fIndustryID=1&hMedia=Newspapers&fLocationID=&fMarketID=&fPositionID=&fExperienceID=&fSalaryID=&fEducationID=&fJobTypeID=&fSpecialtyID=&startrow=1">Newspapers</a> 
      | <a href="/search_results.cfm?fIndustryID=2&hMedia=TV&fLocationID=&fMarketID=&fPositionID=&fExperienceID=&fSalaryID=&fEducationID=&fJobTypeID=&fSpecialtyID=&startrow=1">TV</a> | <a href="/search_results.cfm?fIndustryID=3&hMedia=Radio&fLocationID=&fMarketID=&fPositionID=&fExperienceID=&fSalaryID=&fEducationID=&fJobTypeID=&fSpecialtyID=&startrow=1">Radio</a> 
      | <a href="/search_results.cfm?fIndustryID=4&hMedia=Magazines&fLocationID=&fMarketID=&fPositionID=&fExperienceID=&fSalaryID=&fEducationID=&fJobTypeID=&fSpecialtyID=&startrow=1">Magazines</a> | <a href="/search_results.cfm?fIndustryID=5&hMedia=Online+Media&fLocationID=&fMarketID=&fPositionID=&fExperienceID=&fSalaryID=&fEducationID=&fJobTypeID=&fSpecialtyID=&startrow=1">Online 
      Media</a> | </b><a href="/search_results.cfm?fIndustryID=6&hMedia=Public+Relations&fLocationID=&fMarketID=&fPositionID=&fExperienceID=&fSalaryID=&fEducationID=&fJobTypeID=&fSpecialtyID=&startrow=1"><b>Public Relations</b></a> | <b><a href="/search_results.cfm?fIndustryID=7,8,9,10,11,12,13&hMedia=Related+Jobs&fLocationID=&fMarketID=&fPositionID=&fExperienceID=&fSalaryID=&fEducationID=&fJobTypeID=&fSpecialtyID=&startrow=1&RelatedJobs=1">Related 
      Jobs</a></b></font>
	  </td> --->
	  
	</tr>
      </table>
    </td>
  </tr>
</table>
</CFOUTPUT>
<CFELSE>
<CFOUTPUT>
<p align="center"><img src="#rootdir#pics/banners/travelscape.gif" width="234" height="60" hspace="4" border="1"><img src="#rootdir#pics/banners/toyota.gif" width="234" height="60" hspace="4" border="1"></p>
<p align="center"><font face="Arial, Helvetica, sans-serif" size="1"><b><font size="2" color="##660033">Job 
  Seekers:</font><font size="2"> <a href="/Post_Resume.cfm">Post Your Resume</a> 
  | <a href="/Job_Notification.cfm">Job Notification</a> | <a href="/Job_Seeker_File_Home_Page.cfm">Job 
  Seeker File</a><br>
  <font color="##660033">Employers:</font> <a href="#securerootdir#Post_Job.cfm">Post Your Job</a> 
  | <a href="#securerootdir#Search_Resumes_Home_Page.cfm">Search Resumes</a> | <a href="/Employer_Folder.cfm">Employer 
  Folder</a></font></b></font></p>
<p align="center"><b><font face="Arial, Helvetica, sans-serif" size="2"><a href="/Search_for_Internship.cfm">Internships</a> 
  | <a href="/Fellowships_Home_Page.cfm">Fellowships</a> | <a href="/Calendar_Home_Page.cfm">Calendar</a> 
  | <a href="/Resources.cfm">Resources</a> | <a href="/Password.cfm">Password</a> 
  | <a href="/About_us.cfm"></a><br>
  <a href="/About_us.cfm">About Us</a> | <a href="/Advertising.cfm">Advertising</a> 
  | <a href="/Contact_us.cfm">Contact Us</a> | <a href="/Bookstore.cfm">Bookstore</a> 
  | <a href="/index.cfm">Home</a></font></b></p>
<p align="center"><font face="Arial, Helvetica, sans-serif" size="1"><b>Copyright 
  &##169; 1999 by JournalismJobs.com. All Rights Reserved.<br>
  Site usage subject to <a href="/terms.cfm">Terms and Conditions</a>.</b></font></p>
</CFOUTPUT>
</CFIF>
