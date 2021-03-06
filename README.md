The CliMAS website allows visitors to view potential impact from climate change on teh biodiversity of Australian terrestrial vertebrates. It consists of three (3) tools:
 * [CliMAS Suitability](http://tropicaldatahub.org/goto/climas/suitability) - allows visitors to select a particular species and view its current range with suitable climate conditions. Visitors can then choose to view a future project range under possible greenhouse gas emission scenarios.
 * [CliMAS Biodiversity](http://tropicaldatahub.org/goto/climas/biodiversity) - allows visitors to select a groups of species (at the genus, family or taxa level) and inspect both the current climatically suitable range and projected future ranges under different emission scenarios.
 * [CliMAS Reports](http://tropicaldatahub.org/goto/climas/reports) - allows visitors to generate a report showing change in temperature, rainfall, and species composition for a selected region and year. It summarises mapped biodiversity of all birds, reptiles, frogs and mammals within suitable climate space projected by 18 different Global Climate Models (GCMs) and 2 potential emissions scenarios (RCPs) from 2015 to 2085. Detailed lists of climate space losses and gains for each species are given. This enables visitors to visualise climate change in their region and to determine priority species.

This repository contains the code base for the CliMAS Suitability and CliMAS Biodiversity tools. The code for the CliMAS Report tool can be found at http://github.com/jcu-eresearch/CliMAS-Reports.

The CliMAS website can be found at: http://tropicaldatahub.org/goto/climas. 
The code for running CliMAS is [available from github](https://github.com/jcu-eresearch/TDH-Tools) and the instructions for installing the code are in the [github wiki](https://github.com/jcu-eresearch/TDH-Tools/wiki/Installation-and-setup-guide).

Structure
---------

CliMAS has two main parts:
 * a back-end server that performs the modelling calculations - the code here consists of bash. R and Java.
 * a front-end server that gives access to the information generated by the back-end server - the code here consists of  PHP and uses
     * a PostgreSQL database for holding the species, genus, family, and taxa information, 
     * a MapServer instance for serving the maps generated by the modelling in the back-end server
     * the JavaScript Library, Leaflet, for the interactive maps.

Credits
-------

CliMAS is being developed by the [eResearch Centre](http://eresearch.jcu.edu.au/) at [JCU](http://www.jcu.edu.au).

CliMAS is supported by [the Australian National Data Service (ANDS)](http://www.ands.org.au/) through the National Collaborative Research Infrastructure Strategy Program and the Education Investment Fund (EIF) Super Science Initiative, as well as through the [Queensland Cyber Infrastructure Foundation (QCIF)](http://www.qcif.edu.au/).

License
-------
See `license.txt`
