DROP TRIGGER IF EXISTS `t_afterinsert_on_base001`;
DELIMITER ;;
CREATE TRIGGER `t_afterinsert_on_base001` AFTER INSERT ON `base00` FOR EACH ROW update base11 set base11.id_date_cast=new.id_date_cast 
where base11.id=new.id and base11.date>=new.date and base11.date<(select date from base00 where base00.id=new.id AND base00.date>new.date order by date ASC limit 1)
;;
DELIMITER ;