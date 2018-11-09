<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html><head><title>Untitled</title>



	
	
	<style>
	<!-- 

.anylinkcss{
position:absolute;
font:normal 12px Arial;
line-height: 18px;
background-color: #FE6700;
width: 250px;
text-align: left;
border-bottom: 1px solid #fff;
}

.anylinkcss a{
width: 100%;
display: block;
text-indent: 3px;
border-bottom: 1px solid #DDD;
padding: 5px;
text-decoration: none;
text-indent: 5px;
color: white;
}

.anylinkcss a:hover{ /*hover background color*/
background-color: #FFA200;
text-decoration: none;
color: white;
}

.anylinkcss a.navlink:link, a.navlink:visited{ /*hover background color*/
text-decoration: none;
color: white;
}
	
	
	
	 -->
	</style></head>
	<cfif global.userbrowser is "IE"> 
	 <cfset css = "margin: 0px;">
	<cfelseif global.userbrowser is "Mozilla"> 
	 <cfset css = "margin-top: 0px;">
	<cfelse>
	 <cfset css = "margin: 0px;">
	</cfif>
	<cfoutput>
	<body style="#css#">
	</cfoutput>
<div class="anylinkcss">
<a href="../training.cfm" class="navlink" target="_parent"><img src="tn_more.gif" alt="" border="0" height="12" width="10"> Media Training</a>
<a href="../salaries.cfm" class="navlink" target="_parent"><img src="tn_more.gif" alt="" border="0" height="12" width="10"> Media Salaries</a>
<a href="../ethics.cfm" class="navlink" target="_parent"><img src="tn_more.gif" alt="" border="0" height="12" width="10"> Media Ethics</a>
<a href="../research.cfm" class="navlink" target="_parent"><img src="tn_more.gif" alt="" border="0" height="12" width="10"> Research Tools</a>
<a href="../career_advice.cfm" class="navlink" target="_parent"><img src="tn_more.gif" alt="" border="0" height="12" width="10"> Career Articles</a>
<a href="../resources.cfm" class="navlink" target="_parent"><img src="tn_more.gif" alt="" border="0" height="12" width="10"> General Media Links</a>
<a href="../journalism_schools.cfm" class="navlink" target="_parent"><img src="tn_more.gif" alt="" border="0" height="12" width="10"> Journalism Schools</a>
</div>


</body></html>