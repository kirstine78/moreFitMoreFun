SELECT ru.`fldDate`, ru.`fldSeconds`, ru.`fldFeeling`, ro.*
FROM `tblrun` ru, tblRoute ro
WHERE ru.`fldRouteId`= ro.fldRouteId and ro.`fldCustomerId` = 1