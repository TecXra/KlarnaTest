@extends('layout')

@section('content')


<div class="wrapper whitebg" style="margin-top:90px; margin-bottom: 100px";>
    <div class="container main-container">
        <div class="row innerPage">
            <div class="col-lg-10 col-md-10 col-sm-10 col-xs-offset-1">
        		<h1 class="title-big text-left section-title-style2">
                    <span>Villkor </span>
                </h1>

                <style>
                    h3 {
                        font-weight: 600;
                        margin-bottom: -10px;
                    }
                </style>


                <div style="padding-left:15px">
                    <h3><span>Prisinformation</span></h3>
                    <p>
                    Alla priser anges i svenska kronor inklusive moms.
                    <b>Alla priser är styckpris per fälg eller däck och gäller vid köp av 4 fälgar.</b>
                    </p> 
                    <br>

                    <h3>Montering</h3>
                    <p>Prova fälgen på bilen innan däcket monteras. Om detta inte är möjligt, kontrollera att det är rätt ET och BULTCIRKEL – <b>ingen retur på avmonterade fälgar.</b>
                    </p>
                    <br>
                    <br>

                    <h3>Leverans </h3>
                    <p>Leverans sker vanligtvis med DHL/budbil som kör hem varorna till dig. Normalt levereras varorna inom 2-5 arbetsdagar.
                    </p>
                    <br>
                    <br>

                    <h3>Fraktkostnad</h3>
                    <p>Fraktkostnad tillkommer på dina beställda varor. Storleken på dina beställda fälgar/däck samt fraktsätt avgör hur hög fraktkostnaden blir.
                    <br>
                    <br>
                    (OBS! Vi lägger inte på något eget tillägg på frakten! Du betalar vad vi betalar: ca. 75kr/st.)
                    </p>
                    <br>

                    <h3>Fraktskador</h3>
                    <p>
                    Det är av största vikt att du undersöker dina fälgar/däck direkt vid leverans eftersom eventuella fraktskador måste anmälas till Däckline/Gåsebäcks däck omedelbart efter att ett fraktskadat paket levererats.
                    </p>
                    <br>

                    <h3>Outlösta varor</h3>
                    <p>Hämtar du inte ut dina varor returneras de till oss. Vid outlösta varor debiteras du en fraktkostnad + 395:- i expeditionsavgift.
                    </p>
                    <br>

                    <h3>Retur och ångerrätt</h3>
                    <p>Enligt Konsumentköplagen samt Distanshandelslagen har du som privatperson 14 dagars ångerrätt på alla varor du handlar hos oss. För företag gäller Köplagen. Köplagen är, till skillnad från Konsumentköplagen, inte en tvingande lag vilket innebär att företag kan avtala annat än vad lagen föreskriver.
                    <br>
                    <br>
                    Ångerrätten börjar gälla från den dagen du har tagit emot dina varor. Kravet för att ångerrätten skall gälla är att varan är i sitt ursprungliga skick. Varan skall alltså vara oanvänd och utan eventuella skador som kan ha uppkommit (medvetet eller omedvetet) under den tid som du har haft varan hos dig. Varor som returneras ska vara förpackade i sitt originalemballage. Fälgar returneras alltid på kundens risk, eventuella skador som kan uppkomma under returen ansvarar inte Däckline/Gåsebäcks däck för.
                    <br>
                    <br>
                    Ångerrätten gäller inte vid köp av kompletta hjul som Däckline/Gåsebäcks däck har monterat och balanserat enligt kundens önskemål. I enlighet med bestämmelsen i 2 kap 5 § av Konsumentköplagen är kompletta hjul en vara som skall tillverkas eller väsentligt ändras efter konsumentens särskilda önskemål. Returrätt gäller inte för specialbeställda fälgar/däck.
                    <br>
                    <br>
                    <b>Vi erbjuder INGEN RETURRÄTT eller öppet köp på utförsäljningsfälgar.</b>
                    <br>
                    <br>
                    Kund som vill utnyttja sin ångerrätt måste meddela detta till Däckline/Gåsebäcks däck inom ångerfristen. Vid samtliga returer sker en produktkontroll. Är den returnerade varan skadad eller använd på något sätt ersätter inte Däckline/Gåsebäcks däck denna. Kunden står för ev. returkostnader (frakt tur-retur) samt en administrationsavgift på 295 kr.
                    <br>
                    <br>
                    Observera att ingen retur är godkänd förrän varorna har kommit till Däckline/Gåsebäcks däck och en produktkontroll har skett. Att du har fått retursedlar och skickat tillbaka dina varor innebär alltså inte att returen per automatik är godkänd.
                    <br>
                    <br>
                    Vi godkänner inte oanmälda returer. Kontakt och överenskommelse mellan dig och Däckline/Gåsebäcks däck ska alltid ha ägt rum innan retur kan ske. Vid samtliga returer måste Däcklines/Gåsebäcks däcks fraktavtal användas.
                    <br>
                    <br>
                    Mer om distansavtalslagen hittar du hos <a href="http://www.konsumentverket.se/">Konsumentverket.</a>
                    <br>
                    <br>
                    Däckline/Gåsebäcks däck följer <a href="http://www.arn.se/">Allmänna reklamationsnämndens</a> rekommendationer (ARN) i en eventuell tvist. Tvist mellan två eller flera företag avgörs som regel i domstol.
                    </p>
                    <br>

                    <h3>Reklamationer</h3>
                    <p>
                    Kontakta Däckline/Gåsebäcks däck för instruktioner. ALLA REKLAMATIONER SKALL ANMÄLAS INNAN DE FRAKTAS TILL OSS. RETURER UTAN REKLAMATIONSNUMMER/ÄRENDENUMMER TAS EJ EMOT.
                    </p>
                    <br>

                    <h3>Monterings- och balanseringsreklamationer</h3>
                    <p>
                    Kontakta Däckline/Gåsebäcks däck för instruktioner. Vid en ombalansering/montering så ersätter vi dig med 50 kr per hjul (självkostnadspris).
                    </p>
                    <br>

                    <h3>Fälgtillbehör</h3>
                    <p>
                    Fälgarna levereras med centreringsringar. Bult, mutter och/eller kromad ventil får du köpa till.
                    </p>
                    <br>

                    <h3>Garantier</h3>
                    <p>
                    Vi lämnar 1 års garanti på kromfälgar (avser fabrikationsfel samt kromet på ytan av fälgen). Ingen garanti lämnas på skador orsakade av vägsalt, bromsdamm, stenskott eller yttre påverkan (tydliga tecken på stenskott är tex. skador endast på ena sidan om fälgens ekrar eller endast på bakfälgarna).
                    <br>
                    <br>
                    INGEN kromgaranti på "custom color" fälgar.
                    <br>
                    <br>
                    Vid garanti så står kunden för ev. avmontering/montering samt fraktkostnader. Däckline/Gåsebäcks däck ansvarar inte för eventuella problem som uppstått gentemot det s.k. helbilsgodkännandet.
                    </p>
                    <br>

                    <h3>Bilder/Image</h3>
                    <p>
                    Vi ansvarar inte för att fälgens design ser något annorlunda ut i verkligheten jämfört med bilden på webbsidan. Ex: beroende på tum/bredd och finish kan bilden se annorlunda ut än verkligheten.
                    <br>
                    <br>
                    <b>Images are displayed to show style or design only. Actual appearance may vary with finish and size.</b>
                    <br>
                    <br>
                    ABS360 är ett patenterat multidimensionellt pcd-system för fälgar.
                    </p>
                    <br>

                    <h3>Skötsel</h3>
                    <p>
                    Rengöring av kromfälgar bör ske med vatten och milt bilschampo.
                    Användning av starkare medel eller vissa typer av fälgrengöringsmedel kan orsaka skador på finishen. Skador av denna art omfattas EJ av fabriksgarantin.
                    </p>
		            
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