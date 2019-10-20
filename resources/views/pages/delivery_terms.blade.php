@extends('layout')

@section('content')


<div class="wrapper whitebg" style="margin-bottom: 100px";>
    <div class="container main-container">
        <div class="row innerPage">
            <div class="col-lg-10 col-md-10 col-sm-10 col-xs-offset-1">
        		<h1 class="title-big text-left section-title-style2">
                    <span>Leverans & returer</span>
                </h1>

                <style>
                    h3 {
                        font-weight: 600;
                        margin-bottom: -10px;
                    }

                    a {
                        color: #B54035;
                    }
                </style>


                <div style="padding-left:15px">
                    {{-- <h3><span>Prisinformation</span></h3> --}}
                    <p class="lead">
                        <b>Prisinformation</b><br>
                        Alla priser anges i svenska kronor inklusive moms. Alla priser är styckpris per fälg eller däck och gäller vid köp av 4 fälgar.
                    </p> 

                    <br>
                    
                    <p class="lead">
                        <b>Montering</b><br>
                        Prova fälgen på bilen innan däcket monteras. Om detta inte är möjligt, kontrollera att det är rätt <a href="https://www.abswheels.se/blog/et-matt/" style="text-decoration: none; color: inherit;">ET Mått</a> och <a href="https://www.abswheels.se/blog/bultmonster/" style="text-decoration: none; color: inherit;">BULTCIRKEL</a> – ingen retur på avmonterade fälgar.
                    </p>

                    <br>
                    
                    <p class="lead">
                        <b>Leverans</b><br>
                        Leverans sker vanligtvis med DHL/budbil som kör hem varorna till dig. Normalt levereras varorna inom 2-5 arbetsdagar.
                    </p>

                    <br>

                    <p class="lead">
                        <b>Fraktkostnad</b><br>
                        Vi erbjuder fraktfritt på alla våra produkter om inte frakt kostnad står med.
                    </p>

                    <br>
                    
                    <p class="lead">
                        <b>Fraktskador</b><br>
                        Det är av största vikt att du undersöker dina fälgar/däck direkt vid leverans eftersom eventuella fraktskador måste anmälas till Citydäck omedelbart efter att ett fraktskadat paket levererats. Transportskador skall reklameras direkt till transportföretaget, ej till Citydäck. Sådan reklamation måste ske omgående vid mottagandet av Produkten. Reklamation måste göras direkt på plats till transportören av dig som kund.
                    </p>

                    <br>
                    
                    <p class="lead">
                        <b>Outlösta varor</b><br>
                        Hämtar du inte ut dina varor returneras de till oss. Vid outlösta varor debiteras du en fraktkostnad + 800:- i expeditionsavgift.
                    </p>

                    <br>

                    <p class="lead">
                        <b>Retur och ångerrätt</b><br>
                        Enligt Konsumentköplagen samt Distanshandelslagen har du som privatperson 14 dagars ångerrätt på alla varor du handlar hos oss. För företag gäller Köplagen. Köplagen är, till skillnad från Konsumentköplagen, inte en tvingande lag vilket innebär att företag kan avtala annat än vad lagen föreskriver.
                        <br>
                        <br>

                        Ångerrätten börjar gälla från den dagen du har tagit emot dina varor. Kravet för att ångerrätten skall gälla är att varan är i sitt ursprungliga skick. Varan skall alltså vara oanvänd och utan eventuella skador som kan ha uppkommit (medvetet eller omedvetet) under den tid som du har haft varan hos dig. Varor som returneras ska vara förpackade i sitt originalemballage. Fälgar returneras alltid på kundens risk, eventuella skador som kan uppkomma under returen ansvarar inte Citydäck för.
                        <br>
                        <br>

                        Ångerrätten gäller inte vid köp av kompletta hjul som Citydäck har monterat och balanserat enligt kundens önskemål. I enlighet med bestämmelsen i 2 kap 5 § av Konsumentköplagen är kompletta hjul en vara som skall tillverkas eller väsentligt ändras efter konsumentens särskilda önskemål. Returrätt gäller inte för specialbeställda fälgar/däck.
                        <br>
                        <br>

                        Vi erbjuder INGEN RETURRÄTT eller öppet köp på utförsäljningsfälgar.
                        <br>
                        <br>

                        Kund som vill utnyttja sin ångerrätt måste meddela detta till Citydäck inom ångerfristen. Vid samtliga returer sker en produktkontroll. Är den returnerade varan skadad eller använd på något sätt ersätter inte Citydäck denna. Kunden står för ev. returkostnader (frakt tur-retur) samt en administrationsavgift på 400 kr.
                        <br>
                        <br>

                        Observera att ingen retur är godkänd förrän varorna har kommit till Citydäck och en produktkontroll har skett. Att du har fått retursedlar och skickat tillbaka dina varor innebär alltså inte att returen per automatik är godkänd.
                        <br>
                        <br>

                        Vi godkänner inte oanmälda returer. Kontakt och överenskommelse mellan dig och Citydäck ska alltid ha ägt rum innan retur kan ske. Vid samtliga returer måste Citydäck fraktavtal användas.
                        <br>
                        <br>

                        Mer om distansavtalslagen hittar du hos <a href="http://www.konsumentverket.se">Konsumentverket</a>. 
                        <br>
                        <br>

                        Citydäck följer <a href="http://www.arn.se/">Allmänna reklamationsnämndens rekommendationer (ARN)</a> i en eventuell tvist. Tvist mellan två eller flera företag avgörs som regel i domstol.
                    </p>

                    <br>
                    
                    <p class="lead">
                        <b>Reklamationer</b><br>
                        Kontakta Citydäck för instruktioner. ALLA REKLAMATIONER SKALL ANMÄLAS INNAN DE FRAKTAS TILL OSS. RETURER UTAN REKLAMATIONSNUMMER/ÄRENDENUMMER TAS EJ EMOT.
                    </p>

                    <br>

                    <p class="lead">
                        <b>Monterings- och balanseringsreklamationer</b><br>
                        Kontakta Citydäck för instruktioner. Vid en ombalansering/montering så ersätter vi dig med 50 kr per hjul (självkostnadspris).
                    </p>

                    <br>

                    <p class="lead">
                        <b>Garantier</b><br>
                        Vi lämnar 1 års garanti på kromfälgar (avser fabrikationsfel samt kromet på ytan av fälgen). Ingen garanti lämnas på skador orsakade av vägsalt, bromsdamm, stenskott eller yttre påverkan (tydliga tecken på stenskott är tex. skador endast på ena sidan om fälgens ekrar eller endast på bakfälgarna).
                        <br>
                        <br>

                        INGEN kromgaranti på "custom color" fälgar.
                        <br>
                        <br>

                        Vid garanti så står kunden för ev. avmontering/montering samt fraktkostnader. Citydäck ansvarar inte för eventuella problem som uppstått gentemot det s.k. helbilsgodkännandet.
                    </p>   

                    <br>

                    <p class="lead">
                        <b>Bilder/Image</b><br>
                        Vi ansvarar inte för att fälgens design ser något annorlunda ut i verkligheten jämfört med bilden på webbsidan. Ex: beroende på tum/bredd och finish kan bilden se annorlunda ut än verkligheten.
                    </p>                       
                    

                    {{-- <p class="lead">
                        Som privatperson har Du enligt distansavtalslagen rätt att ångra ett köp inom 14 kalenderdagar från mottagandet. För att begära ångerrätt enligt distansavtalslagen måste Du först kontakta oss. Returfrakten sker alltid på din bekostnad enligt distansavtalslagen § 18 och på ditt ansvar (vad gäller, förpackning, emballage och eventuell ersättning från transportbolaget). Detta gäller självklart inte om vi skickat en vara som ej var den som kunden beställt. Tänk på att du som kund bär ansvaret för återsändningen av produkten. 
                        <br>
                        <br>
                        Varor tas endast i retur efter överenskommelse. 
                        Vid retur av levererade varor debiteras 10 % i returavgift. 
                        Vid kompletta uppsättningar däck och fälgar debiteras 15%. 
                        Returfrakten betalar du + våra fraktkostnader då varan skickades till dig.
                        Varorna skall vara felfria och i originalförpackning. 
                        Oemballerade varor skickas på avsändarens egen risk.
                        Avbeställning eller ändring efter att hjulen monterats debiteras med 150,00 kr per hjul.
                        Ändring, retur eller avbeställning debiteras med en fraktkostnad på 150 kr per vara. På varor som redan expedierats.
                        OBS om varan är skadad vid returtillfället så debiteras 30% av totalsumman.
                        Varorna ska levereras i det skick som du mottagit dem av oss.
                    </p> 

                    <br>

                    <h3><b>OBS: ÖPPET KÖP GÄLLER EJ VID KÖP AV VAROR SOM HAR MINST 50% NEDSATT PRIS!</b></h3>

                    <br>

                    <p class="lead">
                        Vill du ändra eller avbeställa din order? Ring oss så fort som möjligt. 
                        Du kan helt ändra eller avbeställa en order som avser däck eller fälgar sålänge din order ej har expedierats. 
                        Du kan helt ändra eller avbeställa en order som avser kompletta hjul innan hjulen har monterats.
                    </p> --}}
                    
            	</div>
            </div>
        </div>
        <!--/row || innerPage end-->
        <div style="clear:both"></div>
    </div>
    <!-- ./main-container -->
    {{-- <div class="gap"></div> --}}
</div>
<!-- /main-container -->

@endsection