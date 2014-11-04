DROP TRIGGER IF EXISTS `t_afterinsert_on_base001`;
DELIMITER ;;
CREATE TRIGGER `t_afterinsert_on_base001` AFTER INSERT ON `base00` FOR EACH ROW update base11 set base11.id_date_cast=new.id_date_cast 
where base11.date>=new.date and base11.id=new.id
;;
DELIMITER ;


DROP TRIGGER IF EXISTS `t_afterinsert_on_base11`;
DELIMITER ;;
CREATE TRIGGER `t_afterinsert_on_base11` AFTER INSERT ON `base11` FOR EACH ROW 
update base11 set new.id_date_cast=(select id_date_cast from base00 where base00.id=new.id order by date DESC limit 1)
;;
DELIMITER ;