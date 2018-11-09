<html>
<cfoutput>
<head>
<title>Calendar_Sign-Up</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>
</cfoutput>

<body bgcolor="#FFFFFF" text="#000000" link="#333366" vlink="#006699" alink="#660033">
<CFINCLUDE template="Header.cfm">
<CFQUERY Name="Events" datasource="jobs">
SELECT Event, EventID, DisplayOrder
FROM Event
ORDER BY Displayorder
</cfquery>
<CFQUERY Name="Duration" datasource="jobs">
SELECT AdID, AdDuration, DisplayOrder
FROM AdDurationCalendar
ORDER BY Displayorder
</cfquery>
<CFQUERY Name="SelectEntry" datasource="jobs">
SELECT Calendar.JobID, Calendar.Name, Calendar.Title, Calendar.Company, Calendar.Address,
Calendar.City, Calendar.Country, Calendar.Zip, Calendar.ZipPlus, Calendar.PhoneArea,
Calendar.Phone, Calendar.EventBody, Calendar.EventID, Calendar.Email
FROM Calendar;
</cfquery>
<cfoutput>
<table width="100%" border="0" align="center">
  <tr> 
    <td colspan="8"> 
      <table width="100%" border="0">
        <tr> 
          <td><font face="Arial, Helvetica, sans-serif" size="2"><b><font color="##000000">|
        <a href="index.cfm">Home</a> | Calendar Event Sign-Up</font></b></font></td>
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
      <form method="post" action="Calendar_Home_Page.cfm">
        <p><font face="Arial, Helvetica, sans-serif" size="2">Add your<b> free</b> 
          calendar listing for up two months. </font></p>
        <table width="100%" border="0">
          <tr bgcolor="##FFFFFF"> 
            <td colspan="2"><font face="Arial, Helvetica, sans-serif" size="2">* 
              denotes required field</font></td>
          </tr>
          <tr bgcolor="##CCCC99"> 
            <td colspan="2"><font face="Arial, Helvetica, sans-serif" size="3" color="##333366"><b>*Confidential 
              Contact Information</b></font></td>
          </tr>
          <tr> 
            <td width="178"> 
              <div align="right"><font face="Arial, Helvetica, sans-serif" size="2">Your 
                Name </font></div>
            </td>
            <td width="443"><font face="Arial, Helvetica, sans-serif" size="2"> 
              <font size="3"><input type="text" name="fName"></font><input type="hidden" name="fName_required" value="Sorry, you must specify your name.">
              </font></td>
          </tr>
          <tr> 
            <td width="178"> 
              <div align="right"><font face="Arial, Helvetica, sans-serif" size="2">Title 
                </font></div>
            </td>
            <td width="443"><font face="Arial, Helvetica, sans-serif" size="2"> 
              <font size="3"><input type="text" name="fTitle"></font><input type="hidden" name="fTitle_required" value="You must specify your Job Title.">
              </font></td>
          </tr>
          <tr> 
            <td width="178"> 
              <div align="right"><font face="Arial, Helvetica, sans-serif" size="2">Company 
                </font></div>
            </td>
            <td width="443"><font face="Arial, Helvetica, sans-serif" size="2"> 
              <font size="3"><input type="text" name="fCompany"></font><input type="hidden" name="fCompany_required" value="You must specify your Company Name.">
              </font></td>
          </tr>
          <tr> 
            <td width="178"> 
              <div align="right"><font face="Arial, Helvetica, sans-serif" size="2">Address 
                </font></div>
            </td>
            <td width="443"><font face="Arial, Helvetica, sans-serif" size="2"> 
              <font size="3"><input type="text" name="fAddress"></font><input type="hidden" name="fAddress_required" value="You must specify your Address.">
              </font></td>
          </tr>
          <tr> 
            <td width="178"> 
              <div align="right"><font face="Arial, Helvetica, sans-serif" size="2">City and State
                </font></div>
            </td>
            <td width="443"><font face="Arial, Helvetica, sans-serif" size="2"> 
              <font size="3"><input type="text" name="fCity"></font><input type="hidden" name="fCity_required" value="You must specify your City and State.">
              </font></td>
          </tr>
          <tr> 
            <td width="178"> 
              <div align="right"><font face="Arial, Helvetica, sans-serif" size="2">Country</font></div>
            </td>
            <td width="443"> <font face="Arial, Helvetica, sans-serif" size="2">
              <font size="3"><input type="text" name="fCountry"></font><input type="hidden" name="fCountry_required" value="You must specify your Country."></font>
            </td>
          </tr>
          <tr> 
            <td width="178"> 
              <div align="right"><font face="Arial, Helvetica, sans-serif" size="2">Postal 
                Code</font></div>
            </td>
            <td width="443"><font face="Arial, Helvetica, sans-serif" size="2"> 
              <font size="3"><input type="text" size="8" maxlength="5" name="fZip"></font><input type="hidden" name="fZip_required" value="You must specify your Zip Code.">
              <font size="3"><input type="text" size="5" maxlength="4" name="fZipPlus"></font>
              </font></td>
          </tr>
          <tr> 
            <td width="178"> 
              <div align="right"><font face="Arial, Helvetica, sans-serif" size="2">Phone</font></div>
            </td>
            <td width="443"><font face="Arial, Helvetica, sans-serif" size="2"> 
              <font size="3"><input type="text" size="4" maxlength="3" name="fPhoneArea"></font><input type="hidden" name="fPhoneArea_required" value="You must specify your Phone Area Code.">
              <font size="3"><input type="text" name="fPhone"></font><input type="hidden" name="fPhone_required" value="You must specify your Phone Number.">
              </font></td>
          </tr>
          <tr> 
            <td width="178"> 
              <div align="right"><font face="Arial, Helvetica, sans-serif" size="2">E-mail 
                </font></div>
            </td>
            <td width="443"><font face="Arial, Helvetica, sans-serif" size="2"> 
              <font size="3"><input type="text" name="fEmail"></font><input type="hidden" name="fEmail_required" value="You must specify your e-mail Address.">
              </font></td>
          </tr>
          <tr> 
            <td width="178"> 
              <div align="right"><font face="Arial, Helvetica, sans-serif" size="2">Event 
                Type</font></div>
            </td>
            <td width="443"><font face="Arial, Helvetica, sans-serif" size="2"> 
              <select name="fEventID">
              <option value=""></option>
              </cfoutput><cfoutput query="Events">
              <option value="#EventID#">#Event#</option>
              </cfoutput><cfoutput>
              </select><input type="hidden" name="fEventID_required" value="You must specify your Event Type.">
              </font></td>
          </tr>
          <tr> 
            <td valign="middle" colspan="2" bgcolor="##CCCC99"><font face="Arial, Helvetica, sans-serif" size="3"><b><font color="##333366">*Event</font></b></font></td>
          </tr>
          <tr align="left"> 
            <td colspan="2"> 
              <font size="3"><textarea name="fEventBody" cols="50" rows="8" wrap="PHYSICAL"></textarea></font><input type="hidden" name="fEventBody_required" value="You must enter an Event Description."><br>
              <font face="Arial, Helvetica, sans-serif" size="2">Write or paste 
              (no html, please) up to 10 lines (400 characters max.) </font> </td>
          </tr>
          <tr align="left"> 
            <td colspan="2" bgcolor="##CCCC99"><font face="Arial, Helvetica, sans-serif" size="3"><b><font color="##333366">Date</font></b></font></td>
          </tr>
          <tr align="left"> 
            <td colspan="2"> 
              <table border="0" cellpadding="4" cellspacing="0">
                <tr> 
                  <td valign="top" colspan="6"><font face="Arial, Helvetica, sans-serif" size="2">When 
                    will your event begin and end?</font></td>
                </tr>
                <tr> 
                  <td valign="top">&nbsp;</td>
                  <td valign="middle" bgcolor="##CCCC99"> 
                    <div align="right"><font face="Arial, Helvetica, sans-serif" size="2"><b>*Begin:</b></font></div>
                  </td>
                  <td valign="middle" bgcolor="##CCCC99" align="center"> 
                    <div align="center"><font face="Arial, Helvetica, sans-serif" size="2">month:</font> 
                      <select name="fMonthStart">
                      <option value=""></option>
                      <option value="1">January</option>
                      <option value="2">February</option>
                      <option value="3">March</option>
                      <option value="4">April</option>
                      <option value="5">May</option>
                      <option value="6">June</option>
                      <option value="7">July</option>
                      <option value="8">August</option>
                      <option value="9">September</option>
                      <option value="10">October</option>
                      <option value="11">November</option>
                      <option value="12">December</option>
                     </select><input type="hidden" name="fMonthStart_required" value="You must specify the Starting Month of the Event.">
                    </div>
                  </td>
                  <td valign="middle" bgcolor="##CCCC99" align="center"> <font face="Arial, Helvetica, sans-serif" size="2">day: 
                    </font> 
                    <select name="fDayStart">
                      <option value=""></option>
                      <option value="1">1</option>
                      <option value="2">2</option>
                      <option value="3">3</option>
                      <option value="4">4</option>
                      <option value="5">5</option>
                      <option value="6">6</option>
                      <option value="7">7</option>
                      <option value="8">8</option>
                      <option value="9">9</option>
                      <option value="10">10</option>
                      <option value="11">11</option>
                      <option value="12">12</option>
                      <option value="13">13</option>
                      <option value="14">14</option>
                      <option value="15">15</option>
                      <option value="16">16</option>
                      <option value="17">17</option>
                      <option value="18">18</option>
                      <option value="19">19</option>
                      <option value="20">20</option>
                      <option value="21">21</option>
                      <option value="22">22</option>
                      <option value="23">23</option>
                      <option value="24">24</option>
                      <option value="25">25</option>
                      <option value="26">26</option>
                      <option value="27">27</option>
                      <option value="28">28</option>
                      <option value="29">29</option>
                      <option value="30">30</option>
                      <option value="31">31</option>
                    </select><input type="hidden" name="fDayStart_required" value="You must specify the starting Day of the Event.">
                  </td>
                  <td valign="middle" bgcolor="##CCCC99" align="center"><font face="Arial, Helvetica, sans-serif" size="2">year:</font> 
                    <select name="fYearStart">
                      <option value=""></option>
                      <option value="1999">1999</option>
                      <option value="2000">2000</option>
                      <option value="2001">2001</option>
                      <option value="2002">2002</option>
                      <option value="2003">2003</option>
                      <option value="2004">2004</option>
                      <option value="2005">2005</option>
                      <option value="2006">2006</option>
                      <option value="2007">2007</option>
                      <option value="2008">2008</option>
                      <option value="2009">2009</option>
                      <option value="2010">2010</option>
                    </select><input type="hidden" name="fYearStart_required" value="You must specify the Starting Year of the Event.">
                  </td>
                  <td valign="middle" bgcolor="##FFFFFF" align="center"> 
                    <div align="center"> </div>
                  </td>
                </tr>
                <tr> 
                  <td>&nbsp;</td>
                  <td valign="middle" bgcolor="##CCCCFF"> 
                    <div align="right"><font face="Arial, Helvetica, sans-serif" size="2"><b>End:</b></font></div>
                  </td>
                  <td valign="middle" bgcolor="##CCCCFF" align="center"> 
                    <div align="center"><font face="Arial, Helvetica, sans-serif" size="2">month: 
                      </font> 
                      <select name="fMonthEnd">
                      <option value=""></option>
                      <option value="1">January</option>
                      <option value="2">February</option>
                      <option value="3">March</option>
                      <option value="4">April</option>
                      <option value="5">May</option>
                      <option value="6">June</option>
                      <option value="7">July</option>
                      <option value="8">August</option>
                      <option value="9">September</option>
                      <option value="10">October</option>
                      <option value="11">November</option>
                      <option value="12">December</option>
                      </select>
                    </div>
                  </td>
                  <td valign="middle" bgcolor="##CCCCFF" align="center"> <font face="Arial, Helvetica, sans-serif" size="2">day: 
                    </font> 
                    <select name="fDayEnd">
                      <option value=""></option>
                      <option value="1">1</option>
                      <option value="2">2</option>
                      <option value="3">3</option>
                      <option value="4">4</option>
                      <option value="5">5</option>
                      <option value="6">6</option>
                      <option value="7">7</option>
                      <option value="8">8</option>
                      <option value="9">9</option>
                      <option value="10">10</option>
                      <option value="11">11</option>
                      <option value="12">12</option>
                      <option value="13">13</option>
                      <option value="14">14</option>
                      <option value="15">15</option>
                      <option value="16">16</option>
                      <option value="17">17</option>
                      <option value="18">18</option>
                      <option value="19">19</option>
                      <option value="20">20</option>
                      <option value="21">21</option>
                      <option value="22">22</option>
                      <option value="23">23</option>
                      <option value="24">24</option>
                      <option value="25">25</option>
                      <option value="26">26</option>
                      <option value="27">27</option>
                      <option value="28">28</option>
                      <option value="29">29</option>
                      <option value="30">30</option>
                      <option value="31">31</option>
                    </select>
                  </td>
                  <td valign="middle" bgcolor="##CCCCFF" align="center"><font face="Arial, Helvetica, sans-serif" size="2">year:</font> 
                    <select name="fYearEnd">
                      <option value=""></option>
                      <option value="1999">1999</option>
                      <option value="2000">2000</option>
                      <option value="2001">2001</option>
                      <option value="2002">2002</option>
                      <option value="2003">2003</option>
                      <option value="2004">2004</option>
                      <option value="2005">2005</option>
                      <option value="2006">2006</option>
                      <option value="2007">2007</option>
                      <option value="2008">2008</option>
                      <option value="2009">2009</option>
                      <option value="2010">2010</option>
                    </select>
                  </td>
                  <td valign="middle" bgcolor="##FFFFFF" align="center"> 
                    <div align="center"><font face="Arial, Helvetica, sans-serif" size="2">(optional) 
                      </font></div>
                  </td>
                </tr>
              </table>
            </td>
          </tr>
          <tr align="left" bgcolor="##FFFFFF"> 
            <td colspan="2">&nbsp;</td>
          </tr>
          <tr align="left" bgcolor="##CCCC99"> 
            <td colspan="2"><font face="Arial, Helvetica, sans-serif" size="3" color="##333366"><b>Duration</b></font></td>
          </tr>
          <tr align="left"> 
            <td colspan="2"><font face="Arial, Helvetica, sans-serif" size="2">Your 
              ad will run for 2 months unless other wise specified: </font> 
              <select name="fDuration">
                </cfoutput><cfoutput query="Duration">
                <option value="#AdID#">#AdDuration#</option>
                </cfoutput><cfoutput>
              </select>
              <input type="hidden" name="NewEvent" value="1">
            </td>
          </tr>
        </table>
        <div align="left"> 
          <p> 
            <center>
              <p>&nbsp;</p>
              <p>
                <input type="submit" name="Submit" value="Post Event">
                <input type="reset" name="Clear_Form" value="Clear Form">
              </p>
            </center>
          <p></p>
        </div>
        </form>
    </td>
  </tr>
</table>
</cfoutput>
<CFINCLUDE template="Footer.cfm">
</body>
</html>