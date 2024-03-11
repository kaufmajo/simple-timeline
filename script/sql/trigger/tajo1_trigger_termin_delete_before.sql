--
-- Definition of trigger `tajo1_trigger_termin_delete_before`
--

DROP TRIGGER /*!50030 IF EXISTS */ `tajo1_trigger_termin_delete_before`;

DELIMITER $$

CREATE TRIGGER `tajo1_trigger_termin_delete_before` BEFORE DELETE ON `tajo1_termin` FOR EACH ROW BEGIN

  IF (@DISABLE_TRIGGERS IS NULL) THEN

    # History

    CALL `tajo1_proc_terminHistory_entry`('termin', 'delete', OLD.`termin_id`);

  END IF;

END $$

DELIMITER ;