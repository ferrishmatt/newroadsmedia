<!--- TeraTech application.cfm
Session State Management --->

<CFAPPLICATION
	 clientmanagement="no"
	 sessionmanagement="yes"
	 name="JournalismJobs"
	 sessiontimeout="#CreateTimespan(0,6,0,0)#"
	 applicationtimeout="#CreateTimespan(0,6,0,0)#">
	
	<!---<cfif ParameterExists(test)>
		<cfset client.test1='yes'>
	</cfif>
	<cfif ParameterExists(client.test1) is 'no'>
	 This site is down for maintenance.  Please check back in a few minutes.
	 <cfabort>
	 </cfif>--->
	 
<!---
<CFIF NOT #ParameterExists(Session.Auth)#>
	<CFSET #Session.Auth#="FALSE">
	<CFINCLUDE TEMPLATE="/index.cfm">
	<CFABORT>
</CFIF>
--->
