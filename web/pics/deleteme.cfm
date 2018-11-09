<CFINCLUDE template="Header.cfm">
<cfquery name="GetLocations"
  datasource="#JobsDataSource#">
  INSERT INTO Location
  (LocationAbbreviation, LocationDescription, DisplayOrder, LocationID)
  VALUES('US', 'United States', 67, 70) 
</cfquery>

<cfquery name="GetLocations"
  datasource="#JobsDataSource#">
  Update Location
  Set DisplayOrder=68
  WHERE LocationID = 0
</cfquery>

<cfquery name="GetLocations"
  datasource="#JobsDataSource#">
  Update Location
  Set DisplayOrder=69
  WHERE LocationID = 64
</cfquery>