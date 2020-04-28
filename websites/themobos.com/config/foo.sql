DROP TABLE IF EXISTS cities;
CREATE TABLE IF NOT EXISTS cities(
       city       VARCHAR(255) NOT NULL PRIMARY KEY
      ,province   VARCHAR(255) NOT NULL
      ,state      VARCHAR(255) NOT NULL
      ,culture    VARCHAR(255) NOT NULL
      ,population INTEGER  NOT NULL
      ,size       VARCHAR(11) NOT NULL
    );
    INSERT INTO cities(city,province,state,culture,population,size) VALUES ('Stygia','Stygia','The Amaranthine Kingdoms of Byzantium','Stygian',209053,'LARGE');
    INSERT INTO cities(city,province,state,culture,population,size) VALUES ('Cörpathium','The Western Wood','The Jeweled Crown of Aquilonia','Skaldic',194973,'LARGE');
    INSERT INTO cities(city,province,state,culture,population,size) VALUES ('City of Amethyst','The Purple Empire','The Immortal Empire of Yoon-Suin','Kaddish',157929,'LARGE');
    INSERT INTO cities(city,province,state,culture,population,size) VALUES ('Lankhmar','Nehwon','The Free Confederacy of the Sea Princes','Cockney',141420,'LARGE');
    INSERT INTO cities(city,province,state,culture,population,size) VALUES ('Illthmar','Nehwon','The Free Confederacy of the Sea Princes','Cockney',73923,'LARGE');
    INSERT INTO cities(city,province,state,culture,population,size) VALUES ('Ulaan Khot','The Steppe','The Immortal Empire of Yoon-Suin','Mingol',19456,'LARGE');
    INSERT INTO cities(city,province,state,culture,population,size) VALUES ('Dunwich','The March','The Jeweled Crown of Aquilonia','Saltish',16631,'LARGE');
    INSERT INTO cities(city,province,state,culture,population,size) VALUES ('Khufu','Ras Thavas','The Amaranthine Kingdoms of Byzantium','Stygian',13600,'LARGE');
    INSERT INTO cities(city,province,state,culture,population,size) VALUES ('Ur-Hadad','Stygia','The Amaranthine Kingdoms of Byzantium','Stygian',13220,'LARGE');
    INSERT INTO cities(city,province,state,culture,population,size) VALUES ('City of Larks','The Purple Empire','The Immortal Empire of Yoon-Suin','Kaddish',12543,'LARGE');
    INSERT INTO cities(city,province,state,culture,population,size) VALUES ('Violet City','Ultraviolet Grasslands','The Amaranthine Kingdoms of Byzantium','Rainbowlands',11562,'LARGE');
    INSERT INTO cities(city,province,state,culture,population,size) VALUES ('Blackmoor','The March','The Jeweled Crown of Aquilonia','Skaldic',10815,'LARGE');
    INSERT INTO cities(city,province,state,culture,population,size) VALUES ('Samara','Medina','The Amaranthine Kingdoms of Byzantium','Baroque',10547,'LARGE');
    INSERT INTO cities(city,province,state,culture,population,size) VALUES ('Emerald City','Ultraviolet Grasslands','The Amaranthine Kingdoms of Byzantium','Rainbowlands',10202,'LARGE');
    INSERT INTO cities(city,province,state,culture,population,size) VALUES ('Ianthe','Medina','The Amaranthine Kingdoms of Byzantium','Baroque',10166,'LARGE');
    INSERT INTO cities(city,province,state,culture,population,size) VALUES ('Zamora','Stygia','The Amaranthine Kingdoms of Byzantium','Stygian',9470,'MEDIUM');
    INSERT INTO cities(city,province,state,culture,population,size) VALUES ('Quarmall','Nehwon','The Free Confederacy of the Sea Princes','Cockney',9187,'MEDIUM');
    INSERT INTO cities(city,province,state,culture,population,size) VALUES ('Akkad','Stygia','The Amaranthine Kingdoms of Byzantium','Stygian',9184,'MEDIUM');
    INSERT INTO cities(city,province,state,culture,population,size) VALUES ('City of Black Lotus','The Purple Empire','The Immortal Empire of Yoon-Suin','Kaddish',9178,'MEDIUM');
    INSERT INTO cities(city,province,state,culture,population,size) VALUES ('Black City','Ultraviolet Grasslands','The Amaranthine Kingdoms of Byzantium','Rainbowlands',9144,'MEDIUM');
    INSERT INTO cities(city,province,state,culture,population,size) VALUES ('Erl','The Western Wood','The Jeweled Crown of Aquilonia','Brogue',8500,'MEDIUM');
    INSERT INTO cities(city,province,state,culture,population,size) VALUES ('City of Wolves','The Purple Empire','The Immortal Empire of Yoon-Suin','Kaddish',8493,'MEDIUM');
    INSERT INTO cities(city,province,state,culture,population,size) VALUES ('Portsmouth','The March','The Jeweled Crown of Aquilonia','Saltish',8489,'MEDIUM');
    INSERT INTO cities(city,province,state,culture,population,size) VALUES ('Tortuga','Islas de Sal','The Free Confederacy of the Sea Princes','Kleshite',7811,'MEDIUM');
    INSERT INTO cities(city,province,state,culture,population,size) VALUES ('Xochotl','The Jungles of Klesh','The Free Confederacy of the Sea Princes','Kleshite',7474,'MEDIUM');
    INSERT INTO cities(city,province,state,culture,population,size) VALUES ('Innsmouth','The March','The Jeweled Crown of Aquilonia','Saltish',7385,'MEDIUM');
    INSERT INTO cities(city,province,state,culture,population,size) VALUES ('Jukai','Medina','The Amaranthine Kingdoms of Byzantium','Baroque',7136,'MEDIUM');
    INSERT INTO cities(city,province,state,culture,population,size) VALUES ('City of Gods','The Purple Empire','The Immortal Empire of Yoon-Suin','Kaddish',7104,'MEDIUM');
    INSERT INTO cities(city,province,state,culture,population,size) VALUES ('Ophir','Stygia','The Amaranthine Kingdoms of Byzantium','Stygian',6784,'MEDIUM');
    INSERT INTO cities(city,province,state,culture,population,size) VALUES ('Carcassonne','León','The Jeweled Crown of Aquilonia','Aquitaine',6422,'MEDIUM');
    INSERT INTO cities(city,province,state,culture,population,size) VALUES ('Commorium','Medina','The Amaranthine Kingdoms of Byzantium','Baroque',6090,'MEDIUM');
    INSERT INTO cities(city,province,state,culture,population,size) VALUES ('Arkham','The Taiga','The Jeweled Crown of Aquilonia','Saltish',5420,'MEDIUM');
    INSERT INTO cities(city,province,state,culture,population,size) VALUES ('Burgundy','León','The Jeweled Crown of Aquilonia','Aquitaine',5156,'MEDIUM');
    INSERT INTO cities(city,province,state,culture,population,size) VALUES ('Barbary','Islas de Sal','The Free Confederacy of the Sea Princes','Saltish',4430,'MEDIUM');
    INSERT INTO cities(city,province,state,culture,population,size) VALUES ('Kush','Stygia','The Amaranthine Kingdoms of Byzantium','Stygian',4419,'MEDIUM');
    INSERT INTO cities(city,province,state,culture,population,size) VALUES ('Koth','Stygia','The Amaranthine Kingdoms of Byzantium','Stygian',4412,'MEDIUM');
    INSERT INTO cities(city,province,state,culture,population,size) VALUES ('Narl','The Taiga','The Jeweled Crown of Aquilonia','Brogue',4078,'MEDIUM');
    INSERT INTO cities(city,province,state,culture,population,size) VALUES ('City of Blood','The Purple Empire','The Immortal Empire of Yoon-Suin','Kaddish',4076,'MEDIUM');
    INSERT INTO cities(city,province,state,culture,population,size) VALUES ('City of Crows','The Purple Empire','The Immortal Empire of Yoon-Suin','Kaddish',4040,'MEDIUM');
    INSERT INTO cities(city,province,state,culture,population,size) VALUES ('Vornheim','The Taiga','The Jeweled Crown of Aquilonia','Skaldic',3758,'MEDIUM');
    INSERT INTO cities(city,province,state,culture,population,size) VALUES ('Threl','The Taiga','The Jeweled Crown of Aquilonia','Brogue',3754,'MEDIUM');
    INSERT INTO cities(city,province,state,culture,population,size) VALUES ('Arn','The Taiga','The Jeweled Crown of Aquilonia','Brogue',3729,'MEDIUM');
    INSERT INTO cities(city,province,state,culture,population,size) VALUES ('Syr Darya','Sughd','The Immortal Empire of Yoon-Suin','Mingol',3349,'MEDIUM');
    INSERT INTO cities(city,province,state,culture,population,size) VALUES ('Luxur','Ras Thavas','The Amaranthine Kingdoms of Byzantium','Stygian',2424,'MEDIUM');
    INSERT INTO cities(city,province,state,culture,population,size) VALUES ('Shadizar','Shadizar','The Amaranthine Kingdoms of Byzantium','Baroque',2082,'MEDIUM');
    INSERT INTO cities(city,province,state,culture,population,size) VALUES ('Irem','Medina','The Amaranthine Kingdoms of Byzantium','Baroque',1737,'MEDIUM');
    INSERT INTO cities(city,province,state,culture,population,size) VALUES ('City of Ghouls','The Purple Empire','The Immortal Empire of Yoon-Suin','Kaddish',1695,'MEDIUM');
    INSERT INTO cities(city,province,state,culture,population,size) VALUES ('Oth','The Taiga','The Jeweled Crown of Aquilonia','Brogue',1692,'MEDIUM');
    INSERT INTO cities(city,province,state,culture,population,size) VALUES ('Silaish Vo','Sangmenzhang','The Immortal Empire of Yoon-Suin','Mingol',1683,'MEDIUM');
    INSERT INTO cities(city,province,state,culture,population,size) VALUES ('Averoigne','León','The Jeweled Crown of Aquilonia','Aquitaine',1641,'MEDIUM');
    INSERT INTO cities(city,province,state,culture,population,size) VALUES ('Cold Corner','Islas de Sal','The Free Confederacy of the Sea Princes','Saltish',1382,'MEDIUM');
    INSERT INTO cities(city,province,state,culture,population,size) VALUES ('Salthaven','Islas de Sal','The Free Confederacy of the Sea Princes','Saltish',1213,'MEDIUM');
    INSERT INTO cities(city,province,state,culture,population,size) VALUES ('Marlinko','The Lands of Nod','The Jeweled Crown of Aquilonia','Wildlands',677,'SMALL');
    INSERT INTO cities(city,province,state,culture,population,size) VALUES ('Byblos','Byblos Province','The Amaranthine Kingdoms of Byzantium','Stygian',676,'SMALL');

