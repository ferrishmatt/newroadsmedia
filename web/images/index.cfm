<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" 
"http://www.w3.org/TR/html4/strict.dtd">


<html><head><meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"><title>JournalismJobs.com -- The Job Board for Media Professionals</title>

<META NAME="description" CONTENT="JournalismJobs.com">
<META NAME="keywords" CONTENT="journalism jobs media jobs media news writing jobs reporter jobs editor jobs producer jobs editing jobs">


<!-- ><link rel="stylesheet" type="text/css" href="/images/style.css"> -->
<link rel="stylesheet" type="text/css" href="style.css">
<link rel="stylesheet" type="text/css" href="navbar/anylink.css">
<script type="text/javascript" src="navbar/anylink.js"></script>
<script language="JavaScript">
<!--
function preloadImages() { //v3.0
  var d=document; if(d.images){ if(!d.p) d.p=new Array();
    var i,j=d.p.length,a=preloadImages.arguments; for(i=0; i<a.length; i++)
    if (a[i].indexOf("#")!=0){ d.p[j]=new Image; d.p[j++].src=a[i];}}
}

function swapImgRestore() { //v3.0
  var i,x,a=document.sr; for(i=0;a&&i<a.length&&(x=a[i])&&x.oSrc;i++) x.src=x.oSrc;
}

function findObj(n, d) { //v4.0
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=findObj(n,d.layers[i].document);
  if(!x && document.getElementById) x=document.getElementById(n); return x;
}

function swapImage() { //v3.0
  var i,j=0,x,a=swapImage.arguments; document.sr=new Array; for(i=0;i<(a.length-2);i+=3)
   if ((x=findObj(a[i]))!=null){document.sr[j++]=x; if(!x.oSrc) x.oSrc=x.src; x.src=a[i+2];}
}


//-->
</script>
</head>
<cfinclude template="qry_getjoboftheday.cfm">
<cfinclude template="qry_getnews.cfm">
<cfinclude template="qry_getindustryevents.cfm">
<!---<cfinclude template="qry_getpollquestion.cfm">--->
<!---<cfdump var="#JobOfTheDayDetails#">--->



<body>
<!--1st anchor link and menu -->

                                                    
<div id="anylinkmenu1" class="anylinkcss">

<cfif global.userbrowser is "IE"> 
 <cfset iframeHeight = "265">
<cfelseif global.userbrowser is "Mozilla"> 
 <cfset iframeHeight = "285">
<cfelse>
 <cfset iframeHeight = "265">
</cfif>    
<cfoutput>
<iframe src="navbar/jobnav.cfm" marginwidth="0" marginheight="0" frameborder="0" height="#iframeHeight#" scrolling="no" width="250"></iframe>
</cfoutput>
</div>

<!--2nd anchor link and menu -->

<cfif global.userbrowser is "IE"> 
 <cfset iframeHeight = "182">
<cfelseif global.userbrowser is "Mozilla"> 
 <cfset iframeHeight = "200">
 <cfelse>

 <cfset iframeHeight = "185">
</cfif>                                                    
<div id="anylinkmenu2" class="anylinkcss">
<cfoutput>
<iframe src="navbar/resourcesnav.cfm" marginwidth="0" marginheight="0" frameborder="0" height="#iframeHeight#" scrolling="no" width="250"></iframe></cfoutput>
</div>
<div id="container">





&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="http://www.renewandretool.com" TARGET="newwindow"><img src="/images/medill_banner_winter_2010.png" border="0" ALT="Click here to visit our sponsor"></a>


&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="http://www.american.edu/soc/news/washington-post-soc-fellowship.cfm" TARGET="newwindow"><img src="/images/american_university_button_ad_june_2011.gif" border="0" ALT="Click here to visit our sponsor"></a>



</center>



	


	<div id="logotag">
		<div id="logo">	
			<a href="/index.cfm"><img src="/images/JJ_logo_small2.gif" alt="JournalismJobs.com"></a>			
		</div>
			<br><br>
		<ul id="utilnav">
			<li class="noBd first">Employers:</li>
			<li><a href="post_job_new.cfm">Post a Job</a></li>
			<li><a href="search_resumes_home_page.cfm">Search Resumes</a></li>
			<li class="noBd"><a href="employer_folder_home_page.cfm">Employer Login</a></li>
		</ul><!-- /utilnav -->
		<div style="clear:both;"></div>
	</div><!-- /logotag -->
	
	<div id="headernav">
		<div id="nav"><img src="images/nav_left.gif" class="leftend"><a href="index.cfm" onMouseOut="swapImgRestore();" onMouseOver="swapImage('nav_home','','images/nav_home_on.gif',1)"><img src="images/nav_home_off.gif" width="130" height="27" name="nav_home" border="0"></a><a href="Search_Jobs_all.cfm" onMouseOut="hideMenu();" onMouseOver="dropdownmenu(this, event, 'anylinkmenu1');"><img src="images/nav_find_off.gif" width="130" height="27" name="nav_find" border="0" onMouseOver="swapImage('nav_find','','images/nav_find_on.gif',1);" onMouseOut="swapImgRestore()"></a><a href="/" onMouseOut="hideMenu();" onMouseOver="dropdownmenu(this, event, 'anylinkmenu2');"><img src="images/nav_resources_off.gif" width="130" height="27" name="nav_resources" border="0" onMouseOver="swapImage('nav_resources','','images/nav_resources_on.gif',1);" onMouseOut="swapImgRestore()"></a><a href="/advertising.cfm" onMouseOut="swapImgRestore()" onMouseOver="swapImage('nav_advertising','','images/nav_advertising_on.gif',1)"><img src="images/nav_advertising_off.gif" width="130" height="27" name="nav_advertising" border="0"></a><a href="password.cfm" onMouseOut="swapImgRestore()" onMouseOver="swapImage('nav_password','','images/nav_password_on.gif',1)"><img src="images/nav_password_off.gif" width="130" height="27" name="nav_password" border="0"></a><a href="about.cfm" onMouseOut="swapImgRestore()" onMouseOver="swapImage('nav_about','','images/nav_about_on.gif',1)"><img src="images/nav_about_off.gif" width="130" height="27" name="nav_about" border="0"></a><a href="contact_us.cfm" onMouseOut="swapImgRestore()" onMouseOver="swapImage('nav_contact','','images/nav_contact_on.gif',1)"><img src="images/nav_contact_off.gif" width="130" height="27" name="nav_contact" border="0"></a><img src="images/nav_right.gif" class="rightend"></div>	
	</div>

	<div id="search"> 
	  <div id="bluetop"> 
		<div id="blueleft"> 
		  <h1>Looking for a Job?</h1>
		  <form method="post" action="search_jobs_all.cfm" name="SearchForm" class="formmargin">
			<div id="searchform"> 
			  <p> <b><label for="keyword">Enter Keyword(s)</label></b> 
				<input name="keyword_search" class="input" type="text" id="keyword">
			  </p>
			</div>
			<div id="searchsubmit"> 
			  <p class="submitbtn"> 
				<input src="images/search_submit.gif" value="submit" type="image">
			  </p>
			</div>
		  </form>
		</div>
		<div id="blueright"> 
		  <div id="left"> 
			<ul>
			  <li class="highlight"><a href="/Search_Jobs_all.cfm">View All Job Listings</a></li>
			  <li><a href="/search_results.cfm?JobTypeID=4">Freelance Jobs</a></li>
			  <li><a href="/search_results_internship.cfm">Internships</a></li>
			</ul>
		  </div>
		  <div id="right"> 
			<p><a href="/insert_resume.cfm"><img src="images/search_post.png" height="32" width="147"></a></p>
			<p><a href="/Job_Seeker_File_Home_Page.cfm"><img src="images/search_login.png" width="147" height="26"></a></p>
			<p><a href="/job_notification.cfm"><img src="images/search_alerts.png" width="147" height="23"></a></p>
		  </div>
		</div>
		<div id="bluebottom"> 
						<!-- Job of the Day -->
			<cfoutput query="JobOfTheDayDetails"><cfset TitleOfPositionOpen = checktext(TitleOfPositionOpen)> <cfset CompanyOrOrganization = checktext(CompanyOrOrganization)>
			<p class="bottomhead">Featured Job: &nbsp;<a href="Job_Listing.cfm?JobID=#JobID#&jotd=1">#ReplaceList(HTMLEditFormat(TitleOfPositionOpen), "``, `", "', '")#, #CompanyOrOrganization#</a></p>
			</cfoutput>

		</div>
	  </div>
	  <div id="empcorner">
	
	
	
	
	
	<cfset RotateBannerNum = RandRange(1,4)>
	
	
	
    <cfif RotateBannerNum eq 1>

	
	
  <a href="http://www.demandstudios.com/LandingPage.mvc/Tyra?utm_source=TBjjobs&utm_medium=banner&utm_campaign=fashionweek260" TARGET="newwindow"><img src="/images/demand_media_button_fashion.jpg" border="0" ALT="Click here to visit our sponsor"></a>
     
	 
     

    <cfelseif RotateBannerNum eq 2>

  
  <a href="http://www.demandstudios.com/health-writing-jobs.html?utm_source=LSjjobs&utm_medium=banner&utm_campaign=banana260" TARGET="newwindow"><img src="/images/demand_media_button_livestrong.jpg" border="0" ALT="Click here to visit our sponsor"></a>

  
     
     
     
    <cfelseif RotateBannerNum eq 3>


<a href="http://www.demandstudios.com/freelance-writing-jobs-business-finance.html?utm_source=BIZjjobs&utm_medium=banner&utm_campaign=brain260" TARGET="newwindow"><img src="/images/demand_media_button_money.jpg" border="0" ALT="Click here to visit our sponsor"></a>
	
     
	 
	 <cfelseif RotateBannerNum eq 4>


<a href="http://www.demandstudios.com/freelance-writing-jobs-home-garden.html?utm_source=HGjjobs&utm_medium=banner&utm_campaign=plants260" TARGET="newwindow"><img src="/images/demand_media_button_home.jpg" border="0" ALT="Click here to visit our sponsor"></a>
	 
	 
	 
	 </cfif>
	 
	  </div>
	
	</div>
	

			
		
	
	<div id="content">
		<div id="contentleft">
		<!-- three-column Opportunities/Resources/Media Links area -->
			
				<div id="threecol">
				<div id="col1">
				<h2>Opportunities</h2>
				<!-- Opportunities bullet list -->
				<ul>		
				<li><a href="fellowship_listings.cfm">Fellowships</a></li>
				<li><a href="awards.cfm">Awards/Contests</a></li>
				<li><a href="training.cfm">Online Classes</a>
			
			
				</ul>
				</div>

				<div id="col2">
				<h2>Resources</h2>
				<!-- Resources bullet list -->
				<ul>
				<!--<li><a href="training.cfm">Media Training</a></li>--->
				<li><a href="career_advice.cfm">Career Articles</a></li>
				<li><a href="ethics.cfm">Media Ethics</a></li>
				<li><a href="ownership.cfm">Media Ownership</a></li>
				<li><a href="salaries.cfm">Media Salaries</a></li>
				<li><a href="journalism_classes.cfm">Media Training</a></li>
				<li><a href="research.cfm">Research Tools</a></li>
				<li><a href="resources.cfm">General Links</a></li>
				<li><a href="journalism_schools.cfm">Journalism Schools</a></li>
				</ul>
				</div>

				<div id="col3">
				<h2>Media Links</h2>
				<!-- Media Links bullet list -->
				<ul>
				<li><a href="newspaper_links.cfm">Newspapers</a></li>
				<li><a href="altweeklies.cfm">Alt-Newspapers</a></li>
				<li><a href="collegepapers.cfm">College Papers</a></li>
				<li><a href="magazine_links.cfm">Magazines</a></li>
				<li><a href="tv_links.cfm">TV Stations</a></li>
				<li><a href="radio_links.cfm">Radio Stations</a></li>
				
				</ul>
				</div>
				
				
			<br><br>	

				
				
		<!--- bottom left hand tall and narrow banner ad --->
				

<!--<div style="padding-top:15px">
<a href="http://www.capitolbeat.org/"><img src="/images/capbeat_ad.gif" border="0"></a>
</div> -->				
					
				<br><Br>
			</div>
			
			<div id="medianews">
   <h2>Media News / Commentary</h2>
   <cfset newsdate = 0>
   <cfset newsdate1 = 1>
   <dl>
	<cfoutput query="news" maxrows=3 group="entrydate">
	<cfset newsdate = #DateFormat(entrydate, "DD/MM/YYYY")#>
	<cfoutput>
	
	<!-- start news item -->
	<cfif newsdate is not newsdate1>
  		<dd class="newsDate">Posted #dateformat(entrydate, "mmm dd, yyyy")#</dd>
  	</cfif>
	<dt>
  		<a href="#link#" target="new">#title#</a>
	</dt>
   
	<cfif imagename is not "">
		<dd class="img#imagealign#">
		<a href="#link#" target="new"><img src="/images/#imagename#" border="0" alt="" /></a>
	<cfelse>
		<dd>
	</cfif>

	#linkbody#
	</dd>
	<!-- end news item -->
   
	</cfoutput>
 	<cfset newsdate1 = #DateFormat(entrydate, "DD/MM/YYYY")#> 
   	</cfoutput>
   		</dl>
		
		<p class="moreNews">
		
		<a href="http://www.journalismgossip.com"> &nbsp;&nbsp;&nbsp;<a href="http://www.journalismgossip.com"><img src="/images/journalism_gossip_index_page.jpg" alt="JournalismGossip.com"></a>
		
		
		</p>
			
</div><!-- end media news -->	
		
			<div id="bannerads">
	

				<!--- <a href="#"><img src="/images/banner_ad.gif" alt="advertisement" height="60" width="468" border="0" class="bannerimage"></a> --->

<br><br>


	



	
	
	<!--
	
	<a href=”http://www.kiplingerprogram.org”>
<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" 
   codebase= "http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0"
   width="468" 
   height="60" >
 
 <param name=movie value="/images/kip_banner_2008.swf"> 
 <param name=loop value=true> 
 <param name=quality value=high> 
 <embed src="/images/kip_banner_2008.swf" loop=true quality=high bgcolor=#ffffff width="468" 
 height="60" align=""
 type="application/x-shockwave-flash" 
 pluginspage="http://www.macromedia.com/go/getflashplayer">
 </embed>
 </object> 
	
	-->


	
	
	<!---
	<cfif isDefined("AdTest")>
	<div id="kipbanner" onClick="window.location.href='http://www.microsoft.com'">
	<a href="http://www.kiplingerprogram.org/" TARGET="newwindow">
		<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" 
			codebase= "http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0"
			width="468" 
			height="60" onClick="alert('hi');" id="Kip2">
	
	<param name=movie value="//images/2007KipBannerFINAL1.swf"> 
	<param name=loop value=true> 
	<param name=quality value=high> 
	<embed src="//images/2007KipBannerFINAL1.swf" loop=true quality=high bgcolor=#ffffff width="468" 
	height="60" name="Kip2" align=""
	type="application/x-shockwave-flash" 
	pluginspage="http://www.macromedia.com/go/getflashplayer" onclick='window.location=http://www.microsoft.com'>
	</embed>
	</object> 

</a>
	</div>
	
	</cfif> --->
			
			
			
			
			</div>



		</div>


		
     
		<div id="contentright">
			<div id="spotlight">
			<h2>Spotlight</h2>
			<!-- Spotlight subhead -->
			<cfoutput query="spotlight">
			<table align="#imagealign#" width="65" cellpadding="6" cellspacing="0" border="0"><tr><td valign="top">
			
			<a href="#link#" target="new"><img src="/images/#imagename#" border="0" alt="Spotlight Image" /></a></td></tr></table> <!--press_clip_art -->
			<h3><a href="#link#" target="new">#title#</a></h3>
			<P>#linkbody#
			<a href="#link#" target="new">Full story</a>.</P>
			</cfoutput>
			</div>
			
			<!-- Training Section Code Goes Here -->
			
			
			<div id="events">
			<h2>Industry Events</h2>
			<!-- Events bullet list -->
			<ul>
			
			<li><b>Diversity</b> <a href="diversity.cfm">Events</a></li>
			<!--- <cfdump var="#qry_getindustryevents#"> --->
			<cfoutput query="qry_getIndustryEvents" maxrows="20">
			
			<cfset theBeginDate = beginDate>
			<cfset theEndDate = endDate>
			<!--- default format is three letter abbreviation and a period --->
			<cfset beginDateFormat = "MMM. D">
			<cfset endDateFormat = "MMM. D">			
			<!--- Only March, April, May, June and July should be spelled out. --->
			<cfset SpelledOutMonths = "3,4,5,6,7">
			<cfif ListFind(SpelledOutMonths,month(theBeginDate))>
				<cfset beginDateFormat = "MMMM D">
			</cfif>
			<cfif ListFind(SpelledOutMonths,month(theEndDate))>
				<cfset endDateFormat = "MMMM D">
			</cfif>


			<!--- no end date---->
			<cfif theEndDate is "" or theBeginDate eq theEndDate>
				 <cfset dateline = Dateformat(theBeginDate, beginDateFormat)>
			<!--- begin & end date in the same month, so display e.g Jan 10 - 12. --->
			<cfelseif month(theBeginDate) eq month(theEndDate)>
				<cfset dateline = Dateformat(theBeginDate, beginDateFormat) & "-" & Dateformat(theEndDate, "D")>
			<!--- begin & end date are different months, so display e.g Jan. 30 - Feb 2. --->
			<cfelseif month(theBeginDate) neq month(theEndDate)>
				<cfset dateline = Dateformat(theBeginDate, beginDateFormat) & " to " & Dateformat(theEndDate, endDateFormat)>
			</cfif>
			<!--- for september only, format should be 4 letters and a period --->
			<cfset dateline = replacenocase(dateline, "Sep.","Sept.","ALL")>
			<li>#dateline#, <cfif len(location)>#location#, #state#</cfif> -- <b>#company#</b> <a href="#eventurl#" target="new">#eventbody#</a></li>
			</cfoutput>			
	

			
			<li> <b><a href="mailto:info@journalismjobs.com">Click here</a></b> to e-mail us your event listing. Listings are free.  						
			</ul>									
			</div>
			
			<br>


			
			<a href=”http://www.kiplingerprogram.org”>
<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" 
   codebase= "http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0"
   width="468" 
   height="60" >
 
 <param name=movie value="/images/kip_banner_2008.swf"> 
 <param name=loop value=true> 
 <param name=quality value=high> 
 <embed src="/images/kip_banner_2008.swf" loop=true quality=high bgcolor=#ffffff width="468" 
 height="60" align=""
 type="application/x-shockwave-flash" 
 pluginspage="http://www.macromedia.com/go/getflashplayer">
 </embed>
 </object> 
			
			
			
		

			
		
		<br>	
		
			</div>
			
            </div>

            </div>


	
		<!-- banner ad -->
		<div align="left" id="bannerads">
	


		</div>		
</div>

<div align="center" style="font: 11px/11px verdana, arial, helvetica, sans-serif;">

	Copyright © 1998-2011, JournalismJobs.com LLC. All Rights Reserved. Site usage subject to <a href="terms.cfm">Terms and Conditions</a> 

		<br> Read our <a href="privacy_policy.cfm">Privacy Policy</a>. E-mail: <a href="mailto:info@journalismjobs.com">info@journalismjobs.com</a> Tel. 510-653-1521.<br>
		
</div>
<br>

</body></html>

