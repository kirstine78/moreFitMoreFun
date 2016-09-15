-- get all runs for specific customer
SELECT *
FROM tblrun run, tblRoute route
WHERE (run.fldRunRouteId = route.fldRouteId AND route.fldRouteCustomerId = 1) 
OR (run.fldRunCustomerId = 1 AND run.fldRunRouteId is null AND route.fldRouteCustomerId is null)


SELECT *
FROM tblrun run, tblRoute route
WHERE (run.fldRunRouteId = route.fldRouteId AND route.fldRouteCustomerId = 2) 
OR (run.fldRunCustomerId = 2 AND run.fldRunRouteId is null AND route.fldRouteCustomerId = 2)


SELECT *
FROM  tblrun
WHERE fldRunCustomerId = 1


-- get all blank runs for specific customer
SELECT *
FROM  tblrun
WHERE fldRunCustomerId = 1 AND fldRunRouteId is null


SELECT *
FROM tblrun run, tblRoute route
WHERE run.fldRunRouteId = route.fldRouteId AND route.fldRouteCustomerId = 1




SELECT (SELECT *
FROM tblrun run, tblRoute route
WHERE run.fldRunRouteId = route.fldRouteId AND route.fldRouteCustomerId = 1)(SELECT *
FROM  tblrun
WHERE fldRunCustomerId = 1 AND fldRunRouteId is null)


SELECT *
FROM tblrun run, tblRoute route
WHERE (run.fldRunRouteId = route.fldRouteId AND route.fldRouteCustomerId = 1) 

union

SELECT *
FROM  tblrun
WHERE fldRunCustomerId = 1 AND fldRunRouteId is null

-- ------------------

SELECT run.fldRunId, run.fldDate, route.fldRouteName, route.fldRouteDistance as fldDistance, run.fldSeconds, run.fldFeeling, run.fldRunCustomerId as fldCustomerId, run.fldRunRouteId as fldRouteId
FROM tblrun run, tblRoute route
WHERE (run.fldRunRouteId = route.fldRouteId AND route.fldRouteCustomerId = 1) 

union

SELECT fldRunId, fldDate, fldBlankRouteName as fldRouteName, fldDistance, fldSeconds, fldFeeling, fldRunCustomerId as fldCustomerId, fldRunRouteId as fldRouteId
FROM tblrun
WHERE fldRunCustomerId = 1 AND fldRunRouteId is null


