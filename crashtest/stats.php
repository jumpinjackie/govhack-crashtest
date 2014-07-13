<?php

require_once dirname(__FILE__)."/../mapadmin/constants.php";
require_once "utils.php";
$params = $_GET;
if (!array_key_exists("mapname", $params))
{
	echo("Missing required parameter: mapname");
	die;
}
if (!array_key_exists("session", $params))
{
	echo("Missing required parameter: session");
	die;
}
$mapname = $params["mapname"];
$session = $params["session"];

$webConfigIni = dirname(__FILE__)."/../webconfig.ini";
MgInitializeWebTier($webConfigIni);

$user = new MgUserInformation($session);
$site = new MgSiteConnection();
$site->Open($user);
$featSvc = $site->CreateService(MgServiceType::FeatureService);

$map = new MgMap($site);
$map->Open($mapname);

$wktRw = new MgWktReaderWriter();
$env = $map->GetMapExtent();
$ll = $env->GetLowerLeftCoordinate();
$ur = $env->GetUpperRightCoordinate();
$x1 = $ll->GetX();
$x2 = $ur->GetX();
$y1 = $ll->GetY();
$y2 = $ur->GetY();
$queryGeom = $wktRw->Read("POLYGON(($x1 $y1, $x2 $y1, $x2 $y2, $x1 $y2, $x1 $y1))");

if (array_key_exists("bbox", $_REQUEST))
{
	$tokens = explode(",", $_REQUEST["bbox"], 4);
	$x1 = floatval($tokens[0]);
	$y1 = floatval($tokens[1]);
	$x2 = floatval($tokens[2]);
	$y2 = floatval($tokens[3]);
	//bbox sanity check
	if ($x1 > $x2) {
		throw new Exception("Bad bbox. x1 > x2. Expected x1,y1,x2,y2");
	} else if ($y1 > $y2) {
		throw new Exception("Bad bbox. y1 > y2. Expected x1,y1,x2,y2");
	} else if ($x2 < $x1) {
		throw new Exception("Bad bbox. x2 < x1. Expected x1,y1,x2,y2");
	} else if ($y2 < $y1) {
		throw new Exception("Bad bbox. y2 < y1. Expected x1,y1,x2,y2");
	}
	$queryGeom = $wktRw->Read("POLYGON(($x1 $y1, $x2 $y1, $x2 $y2, $x1 $y2, $x1 $y1))");
}

$sources = array(
	"ACCIDENT_EVENT" => "Library://GovHack/Data/ACCIDENT_EVENT_v2.FeatureSource",
	"SPEED_ZONE" => "Library://GovHack/Data/Final_Speed_Zone.FeatureSource",
	"ATMOSPHERIC_CONDITION" => "Library://GovHack/Data/ATMOSPHERIC_COND_v2.FeatureSource",
	"SPEED_SIGNS" => "Library://GovHack/Data/Speed_Signs.FeatureSource",
	"ROAD_SURFACE_CONDITION" => "Library://GovHack/Data/ROAD_SURFACE_COND_v2.FeatureSource",
	"PERSON" => "Library://GovHack/Data/PERSON_v2.FeatureSource"
);

$classNames = array(
	"ACCIDENT_EVENT" => "Default:ACCIDENT_EVENT_v2",
	"SPEED_ZONE" => "Default:Final_Speed_Zones",
	"ATMOSPHERIC_CONDITION" => "Default:ATMOSPHERIC_COND_v2",
	"SPEED_SIGNS" => "Default:speed_signs",
	"ROAD_SURFACE_CONDITION" => "Default:ROAD_SURFACE_COND_v2",
	"PERSON" => "Default:PERSON_v2"
);

$labels = array(
	"ACCIDENT_EVENT" => "Accident Events",
	"SPEED_ZONE" => "Speed Zones",
	"ATMOSPHERIC_CONDITION" => "Atmospheric Conditions",
	"SPEED_SIGNS" => "Speed Signs",
	"ROAD_SURFACE_CONDITION" => "Road Surface Conditions",
	"PERSON" => "Affected People"
);

?>
<!DOCTYPE html>
<html>
<head>Statistics of current area</head>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
	<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" />
<body>
<?php
foreach ($sources as $name => $fsId)
{
	$className = $classNames[$name];
	$label = $labels[$name];
	
	$classParts = explode(":", $className);
	
	$resId = new MgResourceIdentifier($fsId);
	$classDef = $featSvc->GetClassDefinition($resId, $classParts[0], $classParts[1]);
	$tx = Utils::GetMapToLayerTransform($featSvc, $map, $resId, $classParts[0], $classParts[1]);
	
	/*
	$query = new MgFeatureQueryOptions();
	if ($tx != null)
	{
		$txGeom = $queryGeom->Transform($tx);
		$query->SetSpatialFilter($classDef->GetDefaultGeometryPropertyName(), $txGeom, MgFeatureSpatialOperations::Intersects);
	}
	else
	{
		$query->SetSpatialFilter($classDef->GetDefaultGeometryPropertyName(), $queryGeom, MgFeatureSpatialOperations::Intersects);
	}
	*/
	if ($tx != null)
	{
		$txGeom = $queryGeom->Transform($tx);
		$env = $txGeom->Envelope();
		$ll = $env->GetLowerLeftCoordinate();
		$ur = $env->GetUpperRightCoordinate();
		echo $ll->GetX()." ".$ll->GetY()." ".$ur->GetX()." ".$ur->GetY();
	}
	else
	{
		$env = $queryGeom->Envelope();
		$ll = $env->GetLowerLeftCoordinate();
		$ur = $env->GetUpperRightCoordinate();
		echo $ll->GetX()." ".$ll->GetY()." ".$ur->GetX()." ".$ur->GetY();
	}
	die;
	/*
	$reader = $featSvc->SelectFeatures($resId, $className, $query);
	$recordCount = 0;
	while ($reader->ReadNext())
	{
		$recordCount++;
	}
	$reader->Close();
	*/
	echo "<h3>$label</h3>";
	//echo "<strong>$recordCount</strong>";
	echo "<br/>";
}

?>
</body>
</html>