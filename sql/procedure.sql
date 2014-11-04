-- ----------------------------
-- Procedure for modifying the dirty data
-- ----------------------------
elimiter //
drop PROCEDURE if EXISTS myproc;
create procedure myproc() 
begin 
declare num int; 
set num=0; 
while num < 76 do 
update base11 set base11.id_date_cast=(SELECT id_date_cast from base00 where base00.id=num ORDER BY date DESC LIMIT 1) where base11.id=num AND base11.date>=(SELECT date from base00 where base00.id=num ORDER BY date DESC LIMIT 1); set num=num+1;
end while;
end //

delimiter ;
call myproc();

-- ----------------------------
-- Procedure for setting the analysisrep for company
-- ----------------------------
delimiter //
drop PROCEDURE if EXISTS pdfproc;
create procedure pdfproc() 
begin 
declare num int; 
declare pre varchar(18);
declare suff varchar(20);
set num=0; 
set pre='PDF/';
set suff='.pdf';
while num < 76 do 
update company set analysisrep=concat(pre,num,suff) where id=num; set num=num+1;
end while;
end //

delimiter ;
call pdfproc();