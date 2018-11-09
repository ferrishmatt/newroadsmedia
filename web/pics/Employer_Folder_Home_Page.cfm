<html>
<cfoutput>
<head>
<title>Employer Folder Login</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>
</cfoutput>

<body bgcolor="#FFFFFF" text="#000000" link="#333366" vlink="#006699" alink="#660033">
<CFINCLUDE template="Header.cfm">
<cfoutput>
  <table width="580" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr> 
    <td colspan="8"> 
      <table width="100%" border="0">
        <tr> 
          <td><font face="Arial, Helvetica, sans-serif" size="2"><b><font color="##000000">|
        <a href="index.cfm">Home</a> | Employer Folder Home Page</font></b></font></td>
          <td> 
            <div align="right"><font face="Arial, Helvetica, sans-serif" size="2"><b><font color="##333366">#DateFormat(Now(), "MMMM D, YYYY")# </font></b></font></div>
          </td>
        </tr>
      </table>
      <BR>
    </td>
  </tr>
    <tr> 
      <td> 
        <form method="post" action="Employer_Folder.cfm">
        <table width="580" border="0">
          <tr bgcolor="##CCCC99"> 
              <td><font face="Arial, Helvetica, sans-serif" size="3" color="##333366"><b>Existing 
                Members</b></font> </td>
            </tr>
          </table>
          
        <table border="0" width="580">
          <tr> 
              <td width="101"> 
                <div align="right"><font face="Arial, Helvetica, sans-serif" size="2"> 
                  Your e-mail</font></div>
              </td>
              <td width="150"> 
                <font size="3"><input type="text" name="fEmail"></font>
              </td>
              <td rowspan="2" width="323"> 
                <div align="center"> 
                  <input type="submit" name="Submit" value="Go to your Employer Folder">
                </div>
              </td>
            </tr>
            <tr> 
              <td width="101"> 
                <div align="right"><font face="Arial, Helvetica, sans-serif" size="2">Password</font></div>
              </td>
              <td width="150"> 
                <input type="password" name="fPassword">
              </td>
            </tr>
          </table>
          <div align="left"> 
            
          <table width="580" border="0" bgcolor="##CCCC99">
            <tr> 
                <td height="25"><font face="Arial, Helvetica, sans-serif" size="3" color="##333366"><b>Prospective 
                  Members</b></font></td>
              </tr>
            </table>
          </div>
          <p align="left"><font face="Arial, Helvetica, sans-serif" size="2">Welcome 
            to the Employer Folder! This <b>free</b> feature will organize your 
            candidate search and make filling a job a lot easier.</font></p>
          <p align="left"><font face="Arial, Helvetica, sans-serif" size="2"> 
            <b><font color="##660033" size="3">How do I get an Employer Folder? 
            </font></b></font></p>
          
        <p align="left"><font face="Arial, Helvetica, sans-serif" size="2">Just 
          <a href="Post_Job.cfm">Post a Job</a> in our database in one of the following categories:
          Newspapers, TV, Radio, Magazines, New Media, Public Relations or Related Jobs. The folder
          will stay active for eight weeks, and will expire four weeks after your job 
          listing expires--giving you extra time to review candidates. </font></p>
          <p align="left"><font face="Arial, Helvetica, sans-serif" size="3" color="##660033"><b>What 
            are the benefits of the Employer Folder? </b></font></p>
        <ul>
          <li> 
            <div align="left"><font face="Arial, Helvetica, sans-serif" size="2">The 
              Folder is a password-protected account that will keep a list of 
              every candidate who applies for your job, with a hyperlink to their 
              resume or personal website. <br>
              </font></div>
          </li>
          <li> 
            <div align="left"><font face="Arial, Helvetica, sans-serif" size="2">The 
              Folder will give you real-time statistics on the number of people 
              who apply for your job, and will give you the ability to rate a 
              candidate’s application. <br>
              </font></div>
          </li>
          <li> 
            <div align="left"><font face="Arial, Helvetica, sans-serif" size="2">You 
              can edit, renew or delete a job listing at any time. For instance, 
              you can raise the salary of a job to attract more candidates, or 
              delete a job early when you find who you’re looking for. <br>
              </font></div>
          </li>
          <li> 
            <div align="left"><font face="Arial, Helvetica, sans-serif" size="2">A 
              real-time clock will keep you informed when your job ad and Folder 
              expires, giving you the opportunity to renew a job listing.</font></div>
          </li>
          <li> 
            <div align="left"><font face="Arial, Helvetica, sans-serif" size="2">The 
              folder can manage the search process of more than one job.</font></div>
            <font face="Arial, Helvetica, sans-serif" size="2"> </font></li>
        </ul>
        <div align="left">
          <p><font face="Arial, Helvetica, sans-serif" size="3" color="##660033"><b>Below 
            is a sample Employer Folder home page: </b></font></p>
        </div>
        <p><font face="Arial, Helvetica, sans-serif" size="2"><b><font size="4">Your Company</font></b>, welcome to your Employer Folder!<br>
          Your folder will remain in our database until: <b>November 30, 1998</b>, 
          which is <b>four weeks</b> after your last job ad expires.</font></p>
        <table border="0" width="580" align="left" cellpadding="4" cellspacing="0">
          <tr bgcolor="##CCCC99"> 
            <td height="26" width="230"><b><font face="Arial, Helvetica, sans-serif" size="2" color="##003366">| 
              Position Open: </font></b></td>
            <td height="26" width="311"><font color="##003366"><b><font face="Arial, Helvetica, sans-serif" size="2" color="##003366">| 
              </font><font face="Arial, Helvetica, sans-serif" size="2">Job Ad 
              Expires: </font></b></font></td>
            <td height="26" width="164"><font color="##003366"><b><font face="Arial, Helvetica, sans-serif" size="2" color="##003366">| 
              </font><font face="Arial, Helvetica, sans-serif" size="2">You have:</font></b></font></td>
          </tr>
          <tr> 
            <td width="230"> 
              <p>| <font face="Arial, Helvetica, sans-serif" size="2"><u>Reporter, 
                Sports</u></font></p>
            </td>
            <td width="311"><font face="Arial, Helvetica, sans-serif" size="2">| 
              November 10, 1998</font></td>
            <td width="164"> 
              <p>| <font face="Arial, Helvetica, sans-serif" size="2"><u>32 
                Applicants</u></font></p>
            </td>
          </tr>
          <tr> 
            <td width="230"><font face="Arial, Helvetica, sans-serif" size="2">| 
              <u>Edit Job</u></font></td>
            <td width="311"><font face="Arial, Helvetica, sans-serif" size="2">| 
              <u>Renew Job</u></font></td>
            <td width="164"><font face="Arial, Helvetica, sans-serif" size="2">| 
              <u>Delete Job</u></font></td>
          </tr>
          <tr bgcolor="##CCCC99"> 
            <td width="230"><font color="##003366"><b><font face="Arial, Helvetica, sans-serif" size="2" color="##003366">| 
              </font><font face="Arial, Helvetica, sans-serif" size="2">Position 
              Open: </font></b></font></td>
            <td width="311"><font color="##003366"><b><font face="Arial, Helvetica, sans-serif" size="2" color="##003366">| 
              </font><font face="Arial, Helvetica, sans-serif" size="2">Job Ad 
              Expires:</font></b></font></td>
            <td width="164"><font color="##003366"><b><font face="Arial, Helvetica, sans-serif" size="2" color="##003366">| 
              </font><font face="Arial, Helvetica, sans-serif" size="2">You have:</font></b></font></td>
          </tr>
          <tr> 
            <td width="230" height="2"> 
              <p>| <font face="Arial, Helvetica, sans-serif" size="2"><u>Editor, 
                Style/Features</u></font></p>
            </td>
            <td width="311" height="2"><font face="Arial, Helvetica, sans-serif" size="2">| 
              November 17, 1998</font></td>
            <td width="164" height="2"> 
              <p>| <font face="Arial, Helvetica, sans-serif" size="2"><u>72 
                Applicants</u></font></p>
            </td>
          </tr>
          <tr> 
            <td width="230"><font face="Arial, Helvetica, sans-serif" size="2">| 
              <u>Edit Job</u></font></td>
            <td width="311"><font face="Arial, Helvetica, sans-serif" size="2">| 
              <u>Renew Job</u></font></td>
            <td width="164"><font face="Arial, Helvetica, sans-serif" size="2">| 
              <u>Delete Job</u></font></td>
          </tr>
        </table>
        <p align="center">&nbsp;</p>
        <p align="center">&nbsp;</p>
        <p align="center">&nbsp;</p>
        <p align="center">&nbsp;</p>
        <p align="center">&nbsp;</p>
        <p align="center">&nbsp;</p>
        <p align="center"><font face="Arial, Helvetica, sans-serif" size="2"><b>See 
          More Listings</b></font></p>
        <font face="Courier New" size=2></font></td>
    </tr>
  </table>
</form>
</cfoutput>
<CFINCLUDE template="Footer.cfm">
</body>
</html>