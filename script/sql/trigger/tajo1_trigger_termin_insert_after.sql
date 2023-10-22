--
-- Definition of trigger `tajo1_trigger_termin_insert_after`
--

DROP TRIGGER /*!50030 IF EXISTS */ `tajo1_trigger_termin_insert_after`;

DELIMITER $$

CREATE TRIGGER `tajo1_trigger_termin_insert_after` AFTER INSERT ON `tajo1_termin` FOR EACH ROW BEGIN

  IF (@DISABLE_TRIGGERS IS NULL) THEN

    # History

    CALL `tajo1_proc_terminHistory_entry`('termin', 'insert', NEW.`termin_id`);

    # Lnk to Datum

    CALL `tajo1_proc_lnk_datum_termin_entry`(NEW.`termin_datum_start`, NEW.`termin_datum_ende`, NEW.`termin_id`);

  END IF;

END $$

DELIMITER ;