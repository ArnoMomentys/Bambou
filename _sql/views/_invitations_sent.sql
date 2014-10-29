SELECT i.`eventID` AS `eventID`,
count(i.`invitationSent`) AS `invitationSent` 
FROM `bambou`.`invitations` AS i WHERE i.`invitationSent` = 1
AND `i`.`hostID` = (
SELECT MIN(`i2`.`hostID`)
FROM `bambou`.`invitations` AS `i2`
WHERE `i2`.`guestID` = `i`.`guestID`
AND `i2`.`eventID` = `i`.`eventID`
)
GROUP BY i.`eventID`
