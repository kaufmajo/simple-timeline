--
-- Definition of trigger `tajo1_trigger_media_insert_before`
--

DROP TRIGGER /*!50030 IF EXISTS */ `tajo1_trigger_media_insert_before`;

DELIMITER $$

CREATE TRIGGER `tajo1_trigger_media_insert_before`
    BEFORE INSERT
    ON `tajo1_media`
    FOR EACH ROW
BEGIN

    IF (@DISABLE_TRIGGERS IS NULL) THEN

        # SET HASH VALUE

        IF NEW.media_name IS NOT NULL THEN
            SET NEW.media_hash = SHA2(NEW.media_name, 256);
        END IF;

    END IF;

END $$

DELIMITER ;