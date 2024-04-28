--
-- Definition of trigger `tajo1_trigger_termin_update_before`
--

DROP TRIGGER /*!50030 IF EXISTS */ `tajo1_trigger_termin_update_before`;

DELIMITER $$

CREATE TRIGGER `tajo1_trigger_termin_update_before`
    BEFORE UPDATE
    ON `tajo1_termin`
    FOR EACH ROW
BEGIN

    IF (@DISABLE_TRIGGERS IS NULL) THEN

        # aktualisiert_am_trigger
        IF (
                0 = (OLD.termin_datum_start <=> NEW.termin_datum_start)
                OR 0 = (OLD.termin_datum_ende <=> NEW.termin_datum_ende)
                OR 0 = (OLD.termin_zeit_start <=> NEW.termin_zeit_start)
                OR 0 = (OLD.termin_zeit_ende <=> NEW.termin_zeit_ende)
                OR 0 = (OLD.termin_zeit_ganztags <=> NEW.termin_zeit_ganztags)
                OR 0 = (OLD.termin_betreff <=> NEW.termin_betreff)
                OR 0 = (OLD.termin_mitvon <=> NEW.termin_mitvon)
            ) THEN

            SET NEW.termin_aktualisiert_am_trigger = CURRENT_TIMESTAMP();

        END IF;

    END IF;

END $$

DELIMITER ;