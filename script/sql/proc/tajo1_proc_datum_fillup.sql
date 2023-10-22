#
# CALL tajo1_proc_datum_fillup('2019-01-01','2038-01-01');
#

DROP PROCEDURE IF EXISTS `tajo1_proc_datum_fillup`;

DELIMITER $$

CREATE PROCEDURE `tajo1_proc_datum_fillup`(IN pi_datum_start DATE, IN pi_datum_ende DATE)
BEGIN

    #
    # reset datum table
    #

    DELETE FROM `tajo1_datum`;

    WHILE pi_datum_start <= pi_datum_ende
        DO
            INSERT IGNORE INTO
                tajo1_datum
            (datum_datum,
             datum_tag,
             datum_monat,
             datum_jahr,
             datum_woche,
             datum_wochentag,
             datum_wochentag_lang_de,
             datum_wochentag_lang_en,
             datum_monat_lang_de,
             datum_monat_lang_en,
             datum_datum_1_de,
             datum_datum_2_de,
             datum_datum_3_de,
             datum_datum_4_de,
             datum_datum_5_de,
             datum_datum_6_de,
             datum_datum_7_de,
             datum_datum_8_de)
            VALUES
            (pi_datum_start,
             DAYOFMONTH(pi_datum_start),
             MONTH(pi_datum_start),
             YEAR(pi_datum_start),
             WEEK(pi_datum_start, 3),
             WEEKDAY(pi_datum_start) + 1,
             CASE WEEKDAY(pi_datum_start) + 1
                 WHEN 1 THEN 'Montag'
                 WHEN 2 THEN 'Dienstag'
                 WHEN 3 THEN 'Mittwoch'
                 WHEN 4 THEN 'Donnerstag'
                 WHEN 5 THEN 'Freitag'
                 WHEN 6 THEN 'Samstag'
                 WHEN 7 THEN 'Sonntag' END,
             CASE WEEKDAY(pi_datum_start) + 1
                 WHEN 1 THEN 'Monday'
                 WHEN 2 THEN 'Tuesday'
                 WHEN 3 THEN 'Wednesday'
                 WHEN 4 THEN 'Thursday'
                 WHEN 5 THEN 'Friday'
                 WHEN 6 THEN 'Saturday'
                 WHEN 7 THEN 'Sunday' END,
             CASE MONTH(pi_datum_start)
                 WHEN 1 THEN 'Januar'
                 WHEN 2 THEN 'Februar'
                 WHEN 3 THEN 'März'
                 WHEN 4 THEN 'April'
                 WHEN 5 THEN 'Mai'
                 WHEN 6 THEN 'Juni'
                 WHEN 7 THEN 'Juli'
                 WHEN 8 THEN 'August'
                 WHEN 9 THEN 'September'
                 WHEN 10 THEN 'Oktober'
                 WHEN 11 THEN 'November'
                 WHEN 12 THEN 'Dezember' END,
             CASE MONTH(pi_datum_start)
                 WHEN 1 THEN 'January'
                 WHEN 2 THEN 'February'
                 WHEN 3 THEN 'March'
                 WHEN 4 THEN 'April'
                 WHEN 5 THEN 'May'
                 WHEN 6 THEN 'June'
                 WHEN 7 THEN 'July'
                 WHEN 8 THEN 'August'
                 WHEN 9 THEN 'September'
                 WHEN 10 THEN 'October'
                 WHEN 11 THEN 'November'
                 WHEN 12 THEN 'December' END,
             DATE_FORMAT(pi_datum_start, '%d.%m.%Y'),
             DATE_FORMAT(pi_datum_start, '%e.%m.%Y'),
             DATE_FORMAT(pi_datum_start, '%d.%c.%Y'),
             DATE_FORMAT(pi_datum_start, '%e.%c.%Y'),
             DATE_FORMAT(pi_datum_start, '%d.%m.%y'),
             DATE_FORMAT(pi_datum_start, '%e.%m.%y'),
             DATE_FORMAT(pi_datum_start, '%d.%c.%y'),
             DATE_FORMAT(pi_datum_start, '%e.%c.%y'));

            SET pi_datum_start = date_add(pi_datum_start, INTERVAL 1 DAY);

        END WHILE;

    #
    # refill lnk table
    #

    DELETE FROM `tajo1_lnk_datum_termin`;

    INSERT INTO
        `tajo1_lnk_datum_termin` (`datum_id`, `termin_id`)
        (
            SELECT
                `datum_id`,
                `termin_id`
            FROM
                `tajo1_datum`
                    INNER JOIN
                    `tajo1_termin` ON
                        `tajo1_datum`.`datum_datum` BETWEEN
                            `tajo1_termin`.`termin_datum_start` AND
                            `tajo1_termin`.`termin_datum_ende`
        );

END;