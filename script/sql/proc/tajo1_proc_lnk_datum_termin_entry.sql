DROP PROCEDURE IF EXISTS `tajo1_proc_lnk_datum_termin_entry`;

DELIMITER $$

CREATE PROCEDURE `tajo1_proc_lnk_datum_termin_entry`(IN pi_datum_start DATE, IN pi_datum_ende DATE, IN pi_termin_id INT(11))
BEGIN

    DELETE FROM `tajo1_lnk_datum_termin` WHERE `termin_id` = pi_termin_id;

    INSERT INTO
        `tajo1_lnk_datum_termin` (`datum_id`, `termin_id`)
        (
            SELECT
                `datum_id`,
                pi_termin_id
            FROM
                `tajo1_datum`
            WHERE
                `datum_datum` BETWEEN pi_datum_start AND pi_datum_ende
        );
END;