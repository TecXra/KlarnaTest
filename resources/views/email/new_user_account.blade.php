<div class="row">
    <div class="col-sm-12 ">
        <div class="row userInfo">

            <div class="thanxContent">

                <h4>Hej.</h4>
                <p>
                	Ett nytt {{ env('APP_NAME') }} konto har skapats med följande inloggnignsuppgifter:
               	</p>
	            <p>
					Användarnamn: {{ $user->email }} <br>
					Lösenord: {{$password}}
				</p>
				<p>
					Inloggnigssidan hittas via länk: <a href="{{url('/login')}}">{{url('/login')}}</a>. <br>
				</p>
				<hr>
                <p>
                    Med Vänliga Hälsningar
                    <br>
                    {{env('APP_NAME')}}<br>
                </p>

                <table cellpadding="0" cellspacing="0" border="0" style="background: none; border-width: 0px; border: 0px; margin: 0; padding: 0;">
                <tr><td valign="top" style="padding-top: 0; padding-bottom: 0; padding-left: 0; padding-right: 7px; border-top: 0; border-bottom: 0: border-left: 0; border-right: solid 3px #F7751F"><img id="preview-image-url" src="https://www.hjulonline.se/images/hjulonline_logo2.png"></td>
                <td style="padding-top: 0; padding-bottom: 0; padding-left: 12px; padding-right: 0;">
                <table cellpadding="0" cellspacing="0" border="0" style="background: none; border-width: 0px; border: 0px; margin: 0; padding: 0;">
                <tr><td colspan="2" style="padding-bottom: 5px; color: #F7751F; font-size: 18px; font-family: Arial, Helvetica, sans-serif;">Hjulonline Kundtjänst</td></tr>
                <tr><td colspan="2" style="color: #333333; font-size: 14px; font-family: Arial, Helvetica, sans-serif;"><strong>{{env('APP_NAME')}}</strong></td></tr>
                <tr><td width="20" valign="top" style="vertical-align: top; width: 20px; color: #F7751F; font-size: 14px; font-family: Arial, Helvetica, sans-serif;">m:</td><td style="color: #333333; font-size: 14px; font-family: Arial, Helvetica, sans-serif;">{{App\Setting::getPhone()}}</td></tr>
                <tr><td width="20" valign="top" style="vertical-align: top; width: 20px; color: #F7751F; font-size: 14px; font-family: Arial, Helvetica, sans-serif;">a:</td><td valign="top" style="vertical-align: top; color: #333333; font-size: 14px; font-family: Arial, Helvetica, sans-serif;">{{App\Setting::getStreetAddress()}}, {{App\Setting::getPostalCode()}} {{App\Setting::getCity()}}</td></tr>
                <tr><td width="20" valign="top" style="vertical-align: top; width: 20px; color: #F7751F; font-size: 14px; font-family: Arial, Helvetica, sans-serif;">w:</td><td valign="top" style="vertical-align: top; color: #333333; font-size: 14px; font-family: Arial, Helvetica, sans-serif;"><a href="http://www.hjulonline.se" style=" color: #1da1db; text-decoration: none; font-weight: normal; font-size: 14px;">{{env('APP_URL')}}</a>&nbsp;&nbsp;<span style="color: #F7751F;">e:&nbsp;</span><a href="mailto:{{ App\Setting::getOrderMail() }}" style="color: #1da1db; text-decoration: none; font-weight: normal; font-size: 14px;">{{ App\Setting::getOrderMail() }}</a></td></tr>
                </table>
                </td></tr></table>

            </div>
        <!--/row end-->

    	</div>

    <!--/rightSidebar-->
	</div>
</div>
