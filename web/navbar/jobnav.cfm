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
	
	
	 --> <!--- <body style="margin: 0px;" bgcolor="#6699cc"> --->
	</style>
	<cfif global.userbrowser is "IE"> 
 <cfset css = "margin: 0px;">
<cfelseif global.userbrowser is "Mozilla"> 
 <cfset css = "margin-top: 0px;">
<cfelse>
 <cfset css = "margin: 0px;">
</cfif>
<cfoutput>
	</head><body style="#css#">
	<cfset theurl = "../search_jobs.cfm">
<div class="anylinkcss">
<a href="../Search_Jobs_all.cfm" class="navlink" target="_parent" target="_parent"><img src="tn_more.gif" alt="" border="0" height="12" width="10"> View All Job Listings</a>
<a href="#theurl#?Media=Newspapers&IndustryID=1" class="navlink" target="_parent"><img src="tn_more.gif" alt="" border="0" height="12" width="10"> Newspapers / Wire Services</a>
<a href="#theurl#?Media=TV&IndustryID=2,3" class="navlink" target="_parent"><img src="tn_more.gif" alt="" border="0" height="12" width="10"> TV / Radio</a>
<a href="#theurl#?Media=Magazines&IndustryID=4" class="navlink" target="_parent"><img src="tn_more.gif" alt="" border="0" height="12" width="10"> Magazines / Publishing</a>
<a href="#theurl#?Media=Online+Media&IndustryID=5" class="navlink" target="_parent"><img src="tn_more.gif" alt="" border="0" height="12" width="10"> Online Media</a>
<a href="#theurl#?Media=Public+Relations&IndustryID=10,12" class="navlink" target="_parent"><img src="tn_more.gif" alt="" border="0" height="12" width="10"> Trade Publications / Newsletters</a>
<a href="#theurl#?Media=Public+Relations&IndustryID=6&FPositionID=85,26,44" class="navlink" target="_parent"><img src="tn_more.gif" alt="" border="0" height="12" width="10"> PR / Media Relations / Communications</a>
<a href="#theurl#?Media=Nonprofit&IndustryID=13,7,8" class="navlink" target="_parent"><img src="tn_more.gif" alt="" border="0" height="12" width="10"> Nonprofit / Academia / Government</a>
<a href="#theurl#?Media=Finance&IndustryID=14,11" class="navlink" target="_parent"><img src="tn_more.gif" alt="" border="0" height="12" width="10"> Financial / Technology / Misc. Jobs</a>
<a href="/search_results.cfm?Diversity=show" class="navlink" target="_parent"><img src="tn_more.gif" alt="" border="0" height="12" width="10"> Diversity Jobs</a>

</div>
</cfoutput>

</body></html>

