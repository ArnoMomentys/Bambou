SELECT `e`.`eid` AS `eid`,count(`i`.`guestID`) AS `nbGuestsTotal` 
FROM (`bambou`.`events` `e` LEFT JOIN `bambou`.`invitations` `i` ON (`e`.`eid` = `i`.`eventID`))
WHERE `i`.`hostID` = (	
	SELECT MIN(`i2`.`hostID`)
	FROM `bambou`.`invitations` `i2` 
	WHERE `i2`.`guestID` = `i`.`guestID`
		AND `i2`.`eventID` = `i`.`eventID`
) 
GROUP BY `e`.`eid`