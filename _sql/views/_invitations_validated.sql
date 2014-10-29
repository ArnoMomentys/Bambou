SELECT i.`eventID` AS `eventID`,
count(i.`validated`) AS `validated` 
FROM `bambou`.`invitations` AS i WHERE i.`validated` = 1
AND `i`.`hostID` = (
SELECT MIN(`i2`.`hostID`)
FROM `bambou`.`invitations` AS `i2`
WHERE `i2`.`guestID` = `i`.`guestID`
AND `i2`.`eventID` = `i`.`eventID`
)
GROUP BY i.`eventID`