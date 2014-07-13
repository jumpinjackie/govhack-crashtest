<?php

class Utils
{
	public static function MakeWktPolygon($x1, $y1, $x2, $y2) {
        return "POLYGON(($x1 $y1, $x2 $y1, $x2 $y2, $x1 $y2, $x1 $y1))";
    }

	public static function GetMapToLayerTransform($featSvc, $map, $resId, $schemaName, $className) {
		$transform = null;
        $factory = new MgCoordinateSystemFactory();
        $sourceCs = $factory->Create($map->GetMapSRS());
        $clsDef = $featSvc->GetClassDefinition($resId, $schemaName, $className);
        //Has a designated geometry property, use it's spatial context
        if ($clsDef->GetDefaultGeometryPropertyName() !== "") {
            $props = $clsDef->GetProperties();
            $idx = $props->IndexOf($clsDef->GetDefaultGeometryPropertyName());
            if ($idx >= 0) {
                $geomProp = $props->GetItem($idx);
                $scName = $geomProp->GetSpatialContextAssociation();
                $scReader = $featSvc->GetSpatialContexts($resId, false);
                while ($scReader->ReadNext()) {
                    if ($scReader->GetName() === $scName) {
                        if ($scReader->GetCoordinateSystemWkt() !== $sourceCs) {
                            $targetCs = $factory->Create($scReader->GetCoordinateSystemWkt());
                            $transform = $factory->GetTransform($sourceCs, $targetCs);
							echo "Transform from ".$map->GetMapSRS()." to ".$scReader->GetCoordinateSystemWkt()."<br/>";
                            break;
                        }
                    }
                }
                $scReader->Close();
            }
        }
        return $transform;
	}
}

?>