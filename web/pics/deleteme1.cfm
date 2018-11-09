<CFINCLUDE template="Header.cfm">

<cfquery name="GetLocations"
  datasource="#JobsDataSource#">
  Update Location
  Set LocationID=101
  WHERE DisplayOrder = 68
</cfquery>
