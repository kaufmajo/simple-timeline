DROP PROCEDURE IF EXISTS `tajo1_proc_terminHistory_entry`;

DELIMITER $$ 

CREATE PROCEDURE `tajo1_proc_terminHistory_entry`(
    IN pi_trigger_table VARCHAR(255),
    IN pi_trigger_action VARCHAR(255),
    IN pi_termin_id INT(11)
) BEGIN
INSERT INTO
    tajo1_terminHistory (
        `trigger_table`,
        `trigger_action`,
        `termin_id`,
        `termin_status`,
        `termin_datum_start`,
        `termin_datum_ende`,
        `termin_zeit_start`,
        `termin_zeit_ende`,
        `termin_zeit_ganztags`,
        `termin_betreff`,
        `termin_text`,
        `termin_kategorie`,
        `termin_mitvon`,
        `termin_link`,
        `termin_link_titel`,
        `termin_link2`,
        `termin_link2_titel`,
        `termin_erstellt_am`,
        `termin_aktualisiert_am`,
        `termin_aktualisiert_am_trigger`,
        `termin_ist_geloescht`,
        `termin_ist_konfliktrelevant`,
        `termin_zeige_konflikt`,
        `termin_zeige_einmalig`,
        `termin_zeige_tagezuvor`,
        `termin_aktiviere_drucken`,
        `termin_ansicht`,
        `termin_notiz`
    ) (
        SELECT
            pi_trigger_table,
            pi_trigger_action,
            `termin_id`,
            `termin_status`,
            `termin_datum_start`,
            `termin_datum_ende`,
            `termin_zeit_start`,
            `termin_zeit_ende`,
            `termin_zeit_ganztags`,
            `termin_betreff`,
            `termin_text`,
            `termin_kategorie`,
            `termin_mitvon`,
            `termin_link`,
            `termin_link_titel`,
            `termin_link2`,
            `termin_link2_titel`,
            `termin_erstellt_am`,
            `termin_aktualisiert_am`,
            `termin_aktualisiert_am_trigger`,
            `termin_ist_geloescht`,
            `termin_ist_konfliktrelevant`,
            `termin_zeige_konflikt`,
            `termin_zeige_einmalig`,
            `termin_zeige_tagezuvor`,
            `termin_aktiviere_drucken`,
            `termin_ansicht`,
            `termin_notiz`
        FROM
            tajo1_termin
        WHERE
            tajo1_termin.termin_id = pi_termin_id
    );

END;