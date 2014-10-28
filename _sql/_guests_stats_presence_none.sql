SELECT `i`.`eventID` AS `eid`, count(`ig`.`guestAnswer`) AS `nbGuestsPresenceNone`
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
AND `i2`.`eventID` = `i`.`eventID`
) AND `ig`.`guestPresence` = 0
GROUP BY `i`.`eventID`
