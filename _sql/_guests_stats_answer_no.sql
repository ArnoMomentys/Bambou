SELECT `i`.`eventID` AS `eid`, count(`ig`.`guestAnswer`) AS `nbGuestsAnswerNo` 
FROM (`bambou`.`invitations` `i` 
	LEFT JOIN `bambou`.`invitationguests` `ig` ON (`i`.`iid` = `ig`.`invitationID`)
) 
WHERE 1 = 1 
AND `i`.`hostID` = (	
	SELECT MIN(`i2`.`hostID`)
	FROM (`bambou`.`invitations` `i2` 
		LEFT JOIN `bambou`.`invitationguests` `ig2` ON (`i2`.`iid` = `ig2`.`invitationID`)
	)
	WHERE `ig2`.`guestAnswer` = `ig`.`guestAnswer` 
	AND `i2`.`guestID` = `i`.`guestID`
) AND `ig`.`guestAnswer` = 2 
GROUP BY `i`.`eventID`
