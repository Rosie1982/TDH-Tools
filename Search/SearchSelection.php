<?php
session_start();
include_once 'includes.php';

$variableNames = FinderFactory::Descriptions(FinderFactory::Result("SearcherNames"));

$snapshotTemplate = <<<CT
<INPUT class="SnapshotButton" ID="Snapshot{#key#}" onclick="Set('Snapshot','{#key#}');" TYPE=BUTTON NAME="MapVariables[]" VALUE="{#value#}">
CT;
$showTemplate = <<<CT
<INPUT class="ShowButton" ID="Show{#key#}" onclick="Set('Show','{#key#}');" TYPE=BUTTON NAME="MapVariables[]" VALUE="{#value#}" disabled>
CT;
$restrictionsTemplate = <<<CT
<INPUT class="RestrictionButton" ID="Restriction{#key#}" onclick="Set('Restriction','{#key#}');" TYPE=BUTTON NAME="MapVariables[]" VALUE="{#value#}" >
CT;


?>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <style>

            body {
                font-family: sans-serif;
                font-size: 12pt;
            }

            .subsetter 
            {
                border: 1px solid blue;
                height: 300px;
                width: 400px;
                overflow: hidden;
                display: none;

                z-index: 200;
                position: fixed;
                top: 20px;
                left:20px;
                background-color: white;
                

            }

            .selected {
                background-color: yellow;
            }

            .selector{
                height: 160px;
                width: 99%;
                float: none;
                margin: 2px;
                
                overflow: hidden;
                font-weight: lighter;
            }

            .selector h2
            {
                margin: 0px;
                display: block;
                border-bottom: 1px solid black;
                font-size: 10pt;
            }


            .selector table {
                border: 0px transparent none;
                width: 96%;
                text-align: center;
                margin: 2%;
            }

            .selector input {
                display: block;
                width: 90%;
                height: 30px;
                text-align: left;
                vertical-align: top;
                border-radius: 5px;
                float: left;
                font-weight: bold;

            }

            .selector input:hover {
                background-color: pink;
            }


            .SnapshotButton
            {

            }

            .ShowButton
            {

            }

            .RestrictionButton
            {
                
            }

            #Process{
                height: 30px;
                width: 100%;
                clear: both;
                float: none;
                margin: 2px;

            }
            #Messages{
                height: 50px;
                width: 100%;
                clear: both;
                float: none;
                margin: 2px;
                background-color: lightyellow;
            }

        </style>
        <script>
            function Set(type,src)
            {

                switch(type) {
                    case 'Snapshot':  SetSnapshot(type,src); break;
                    case 'Show': SetShow(type,src);break;
                    case 'Restriction': SetRestriction(type,src); break;
                }

            }

            function SetSnapshot(type,src)
            {
                // can only have one snap shot so reset the others and set this one
                var snaps = elementsThatContain(type);
                for (var i=0; i < snaps.length; i++)
                    snaps[i].className = snaps[i].className.replace(" selected","");

                ToggleSelected(type +  src);

                // find Show version of src and make it Disabled
                var shows = elementsThatContain('Show');
                for (var i=0; i < shows.length; i++)
                    shows[i].disabled = false;

                var showToDisable = 'Show' + src;
                // if a show had been select and they have changed the "snapshot" then deselect the show
                document.getElementById(showToDisable).className = document.getElementById(showToDisable).className.replace(" selected","");
                document.getElementById(showToDisable).disabled = true;

            }


            function elementsThatContain(str)
            {
                var all = document.getElementsByTagName("*");

                var result = new Array();

                count = 0;
                for (var i=0; i < all.length; i++)
                {
                    if (all[i].id.indexOf(str) != -1)
                    {
                        result[count] = all[i];
                        count++;
                    }
                }
                return result;

            }


            function SetShow(type,src)
            {
                var shows = elementsThatContain(type);
                for (var i=0; i < shows.length; i++)
                    shows[i].className = shows[i].className.replace(" selected","");

                ToggleSelected(type +  src);

            }

            function SetRestriction(type,src)
            {
                window.top.openPopup(src);
            }

            function ToggleDisplay(id)
            {
                if (document.getElementById(id).style.display == "block")
                    document.getElementById(id).style.display = "none";
                else
                    document.getElementById(id).style.display = "block";
            }


            function ToggleSelected(src)
            {
                var classes = document.getElementById(src).className;

                if (classes.indexOf("selected") == -1)
                    document.getElementById(src).className += " selected";
                else
                    document.getElementById(src).className = document.getElementById(src).className.replace(" selected","");
                
            }

            function run()
            {
                
                window.parent.run('SpeciesMaxent');
            }


        </script>
        <title>Search Selector</title>
    </head>
    <body >

        <div id="snapshot" class="selector">
            <h2>Snapshot</h2>
            <?php echo htmlutil::TableRowTemplate($variableNames,$snapshotTemplate);?>
            <!-- <dfn>A single map for a subset of values in this variable  (a Subset could be ALL)</dfn> -->
            
        </div>

        <div id="Show" class="selector">
            <h2>Show</h2>
            <?php
                echo htmlutil::TableRowTemplate($variableNames,$showTemplate);
            ?>
            <!-- <dfn>On each map show this variable (Mutually Exclusive to the "Snapshot")</dfn> -->

        </div>

        <div id="Restrictions" class="selector">
            <h2>subset</h2>
            <?php
                echo htmlutil::TableRowTemplate($variableNames,$restrictionsTemplate);
            ?>id
            <!-- <dfn>Restrict Data on each map to this subset</dfn> -->
            
        </div>

        <div id="Process">
            <INPUT  ID="RUN" TYPE=BUTTON NAME="RunProcess" VALUE="RUN"  onclick="run();" style="width: 90%; padding: 1%;">
        </div>


    </body>
</html>
