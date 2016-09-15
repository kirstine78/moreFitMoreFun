SELECT run.fldRunId, run.fldDate, route.fldRouteName, route.fldRouteDistance as fldDistance, run.fldSeconds, run.fldFeeling, run.fldRunCustomerId as fldCustomerId, run.fldRunRouteId as fldRouteId
FROM tblrun run, tblRoute route
WHERE (run.fldRunRouteId = route.fldRouteId AND route.fldRouteCustomerId = 1) 

union

SELECT fldRunId, fldDate, fldBlankRouteName as fldRouteName, fldDistance, fldSeconds, fldFeeling, fldRunCustomerId as fldCustomerId, fldRunRouteId as fldRouteId
FROM tblrun
WHERE fldRunCustomerId = 1 AND fldRunRouteId is null


