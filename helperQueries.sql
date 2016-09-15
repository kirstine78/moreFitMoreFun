-- get all runs with route for specific customer
SELECT *
FROM tblrun run, tblRoute route
WHERE run.fldRunRouteId = route.fldRouteId AND route.fldRouteCustomerId = 1



SELECT *
FROM  tblrun
WHERE fldRunCustomerId = 1


-- get all blank runs for specific customer
SELECT *
FROM  tblrun
WHERE fldRunCustomerId = 1 AND fldRunRouteId is null




SELECT (SELECT *
FROM tblrun run, tblRoute route
WHERE run.fldRunRouteId = route.fldRouteId AND route.fldRouteCustomerId = 1)(SELECT *
FROM  tblrun
WHERE fldRunCustomerId = 1 AND fldRunRouteId is null)