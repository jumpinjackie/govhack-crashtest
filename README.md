About
=====

Crash Test is an interactive map based on VicRoads CrashStats data. 

It is a powerful tool designed for the VicRoads team to analyze crash incident data with key contextual pieces of information (locations of liquor licensed venues and road transport data) in a visual and engaging way. The meaningful insights that the internal management team will be able to gather from the tool can inform important public service initiatives that can save lives.  

Problematic areas identified through the tool can lead to additional road signs in critical areas, the design and release of public awareness campaigns specific to drinking, seatbelt and helmet use or initiating work to improve road surfaces. 

The map is driven by all facets within the VicRoad CrashStats data along with liquor-licensed venues throughout Victoria and Road Transport data about bike paths, bus routes and tram routes. We firmly believe that because these datasets coexist with one another within the tool, VicRoads will be able to have a full picture of the circumstances which influence crash incidents, injuries and fatalities.

Crash Test is one entry in the [GovHack 2014](http://www.govhack.org) hackathon.

Solution Overview
=================

Crash Test is built on top of the [MapGuide Open Source](http://mapguide.osgeo.org) plaform.

Various open data sets have been massaged and prepared using best-of-breed open source tools:
 - [GDAL/OGR](http://www.gdal.org)
 - [QGIS](http://www.qgis.org)

Our solution is a mash-up of various Victorian Government open datasets using the powerful features of MapGuide to provide an integrated and powerful data viewing and analysis front-end.

As such, there isn't really much "source code". What is stored here is the various data and configuration files used to massage our datasets for use with MapGuide with some static web content to provide some information about this application.

Data Overview
=============

The following datasets have been used to produce this solution

 - VicRoads CrashStats data (CSV -> SHP -> SQLite)
 - VicRoads Transportation Network (KML/KMZ -> SHP)

See the included attributions file for more information
