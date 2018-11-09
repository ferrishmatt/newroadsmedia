<html>
<cfoutput>
<head>
<title>Employer Folder Action</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head></cfoutput>

<body bgcolor="#FFFFFF" text="#000000" link="#333366" vlink="#006699" alink="#660033">
<CFINCLUDE template="Header.cfm">
    <cfquery name="DeleteJob" datasource="#JobsDataSource#">
        DELETE *
        FROM Job
        WHERE JobID = #hJobID#
    </cfquery>
    <cfoutput><br><font face="Arial, Helvetica, sans-serif" size="2"><b>
    Job Number #hJobID# deleted successfully.</b></font>
    </cfoutput>
    <br>
    <font face="Arial, Helvetica, sans-serif" size="2">Return to 
    <a href="Employer_Folder.cfm">Employer Folder</a></font>
<CFINCLUDE template="Footer.cfm">
</body>
</html>