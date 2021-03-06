#
# CoffeeScript - this file compiles to JavaScript.
# http://coffeescript.org/#installation
#
#
$ ->

    # monkey-patch a function into String to capitalise a word. We'll use this later.
    String::capped = -> @.charAt(0).toUpperCase() + @.substring(1).toLowerCase()

    $clazzRadios = $ '#selectionpanel .clazz_selection'
    $taxaSelectors = $ '#selectionpanel .taxa_selector'

    # hide all the taxa selectors
    $taxaSelectors.hide()

    #
    # show taxa selection when a class is selected
    #
    $clazzRadios.each (index, clazzRadio)->

        $taxaSelector = $taxaSelectors.filter('.' + clazzRadio.value)

        #
        # update radio buttons that choose a class
        #
        $(clazzRadio).change (event)->
            $taxaSelectors.hide()

            selectedClazz = $clazzRadios.filter(':checked').prop 'value'

            if selectedClazz != 'all'
                $taxaSelectors.filter('.' + selectedClazz).show 'blind'

        #
        # show family / genus dropdowns when appropriate
        #
        if clazzRadio.value != 'all'

            $taxaRadios = $taxaSelector.find '.taxa'
            $taxaDDs = $taxaSelector.find '.taxa_dd'
            $familyDD = $taxaSelector.find '.taxa_dd.family'
            $genusDD = $taxaSelector.find '.taxa_dd.genus'

            $taxaRadios.change (event)->
                $taxaDDs.css 'visibility', 'hidden'

                switch event.srcElement.value
                    when 'family' then $familyDD.css 'visibility', 'visible'
                    when 'genus'  then $genusDD.css 'visibility', 'visible'

    #
    # disable the emission scenario thingy when they choose "current"
    #
    $('#prebakeform .year').change (event)->
        if $('#prebakeform .year:checked').prop('value') == 'current'
            $('#prebakeform input:radio[name="scenario"]').attr 'disabled', true
            $('#prebakeform .scenario').addClass 'disabled'
        else
            $('#prebakeform input:radio[name="scenario"]').attr 'disabled', false
            $('#prebakeform .scenario').removeClass 'disabled'

    # now trigger that event
    $('#prebakeform .year').first().change()


    #
    # when they click the generate button..
    #
    $generate = $ '#prebakeform .generate'

    #
    # when any form fields change, update the submittable status
    #
    $('#prebakeform input').add('#prebakeform select').change (event)->
        # the only thing that can stop the form from being submittable is if the
        # user wants to see a single family or genus, but hasn't selected the
        # family or genus yet.  So:
        formIncomplete = false

        clazz = $('#prebakeform input:radio[name="clazztype"]:checked').val()
        if clazz? and clazz != 'all'
            taxaLevel = $("#prebakeform input:radio[name='#{clazz}_taxatype']:checked").val()
            if taxaLevel != 'all'
                groupName = $("#prebakeform select[name='chosen_#{taxaLevel}_#{clazz}']").val()
                if groupName == 'invalid'
                    formIncomplete = true

        $('#prebakeform .generate').attr 'disabled', formIncomplete


    $generate.click (e)->
        # collect our request details

        year = $('#prebakeform input:radio[name="year"]:checked').val()
        scenario = $('#prebakeform input:radio[name="scenario"]:checked').val()
        output = $('#prebakeform input:radio[name="output"]:checked').val()

        clazz = $('#prebakeform input:radio[name="clazztype"]:checked').val()
        groupLevel = 'clazz'
        groupName = clazz

        if clazz? and clazz != 'all'
            taxaLevel = $("#prebakeform input:radio[name='#{clazz}_taxatype']:checked").val()
            if taxaLevel == 'all'
                # if the taxa level is 'all', the group can stay 'clazz'
            else
                groupLevel = taxaLevel
                groupName = $("#prebakeform select[name='chosen_#{taxaLevel}_#{clazz}']").val()


        if output == 'download'
            #
            # they want ascii grid and metadata
            #

            # figure out the file name

            prefix = "#{scenario}_#{year}"        # normal filename prefix
            prefix = '1990' if year is 'current'  # special case for "current" year

            # all paths start with this..
            path = window.SourceDataUrl

            if groupLevel is 'clazz' and clazz is 'all'
                # special case for "all vertebrates"
                path += "biodiversity/#{prefix}_vertebrates.zip"

            else if groupLevel is 'clazz'
                # class names need to be translated from Sindarin (eg AVES) to Common Tongue (eg birds)
                path += "By#{groupLevel.capped()}/#{groupName}/biodiversity/"
                path += "#{prefix}_#{window.clazzinfo[groupName].plural}.zip"

            else
                # other group names just need capitalisation fixed.
                path += "By#{groupLevel.capped()}/#{groupName}/biodiversity/"
                path += "#{prefix}_#{groupName.toLowerCase().capped()}.zip"

            # finally we have the path to the downloadable file.
            window.location.href = path


        else if output == 'view'
            #
            # they want to see the map
            #

            # hit the prep url to unzip the asciigrid
            $.ajax 'BiodiversityPrep.php', {
                cache: false
                dataType: 'json'
                data: {
                    class: clazz
                    taxon: groupName
                    settings: "#{scenario}_#{year}"
                }
                success: (data, testStatus, jqx) ->

                    if not data.map_path
                        alert "Sorry, data for that selection is not available."

                    else
                        maptitle = 'Biodiversity of terrestrial '
                        if groupLevel is 'clazz' and clazz is 'all'
                            maptitle += 'vertebrates'
                        else if groupLevel is 'clazz'
                            maptitle += clazz.capped()
                        else
                            maptitle += "#{clazz.capped()} #{groupLevel} '#{groupName.capped()}'"

                        if year isnt 'current'
                            maptitle += " in #{year} at emission level #{scenario}"

                        $("""
                            <div class="popupwrapper" style="display: none">
                                <div class="toolbar north">
                                <button class="close">close &times;</button>
                                <div id="maptitle">#{maptitle}</div>
                                </div>
                                <div id="popupmap" class="popupmap"></div>
                                <div class="toolbar south"><button class="close">close &times;</button><div id="legend"></div></div>
                        """).appendTo('body').show('fade', 1000)

                        # pre-figure the layer name - it's the filename portion of the
                        # map_path, without the .map extension.
                        layer_name = data.map_path.replace(/.*\//, '').replace(/\.map$/, '')

                        # fetch the legend as a html template from MapServer
                        $('#legend').load('/cgi-bin/mapserv?mode=browse&layer=' + layer_name + '&map=' + data.map_path);

                        # add close behaviour to the close buttons
                        $('.popupwrapper button.close').click (e)->
                            $('.popupwrapper').hide 'fade', ()->
                                $('.popupwrapper').remove()

                        # create the map
                        map = L.map('popupmap', {
                            minZoom: 3
                        }).setView([-27, 135], 4)

                        # 831e24daed21488e8205aa95e2a14787 is Daniel's CloudMade API key
                        # (april 2014: switched to mapquest because cloudmade are shuttering their free tiles)
                        L.tileLayer('http://otile1.mqcdn.com/tiles/1.0.0/map/{z}/{x}/{y}.png', {
                            attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="http://www.mapquest.com/" target="_blank">MapQuest</a>'
                            maxZoom: 18
                        }).addTo map

                        # add the selected layer to the map
                        data = new L.TileLayer.WMS("/cgi-bin/mapserv", {
                            layers: layer_name + '&map=' + data.map_path
                            format: 'image/png'
                            opacity: 0.75
                            transparent: true
                        }).addTo map
            }

        e.preventDefault();

