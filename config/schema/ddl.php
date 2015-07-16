create table keeper_user(

	id int(10) auto_increment,
	username varchar(100),
	password varchar(100),
	first_name varchar(100),
	last_name varchar(100),
	mobile varchar(100),
	status varchar(200),
	dated varchar(100),
	
	primary key (id)
	
)engine=innodb;

create table keeper_locker(

	id int(10) auto_increment,
	name varchar(200),
	description text,
	status varchar(200),
	dated varchar(100),
	
	primary key (id)
	
)engine=innodb;

create table keeper_box(

	id int(10) auto_increment,
	name varchar(200), -- ftp ssh etc or anything to define type
	status varchar(200),
	dated varchar(100),
	
	primary key (id)

)engine=innodb;

create table keeper_data(
	
	id int(10) auto_increment,
	name varchar(200),
	value text,
	notes text,
	status varchar(200),
	dated varchar(100),
	
	primary key(id)
	
)engine=innodb;

create table keeper_file(

	id int(10) auto_increment,
	real_name text,
	server_name varchar(200),
	mime_type varchar(200),
	web_path text,
	physical_path text,
	
	status varchar(200),
	dated varchar(100),
	
	primary key (id)

)engine=innodb;

create table keeper_user_locker(

	id int(10) auto_increment,
	user_id int(10),
 	locker_id int(10),
	
 	primary key(id),
	foreign key(locker_id) references keeper_locker(id) on delete cascade on update cascade,
	foreign key(user_id) references keeper_user(id) on delete cascade on update cascade
	
)engine=innodb;

create table keeper_locker_box(

	id int(10) auto_increment,
	locker_id int(10),
	box_id int(10),
 	
 	primary key(id),
	foreign key(locker_id) references keeper_locker(id) on delete cascade on update cascade,
	foreign key(box_id) references keeper_box(id) on delete cascade on update cascade
	
)engine=innodb;

create table keeper_box_data(

	id int(10) auto_increment,
	box_id int(10),
	data_id int(10),
 	
 	primary key(id),
	foreign key(box_id) references keeper_box(id) on delete cascade on update cascade,
	foreign key(data_id) references keeper_data(id) on delete cascade on update cascade
	
)engine=innodb;

create table keeper_box_file(

	id int(10) auto_increment,
	box_id int(10),
	file_id int(10),
 	
 	primary key(id),
	foreign key(box_id) references keeper_box(id) on delete cascade on update cascade,
	foreign key(file_id) references keeper_file(id) on delete cascade on update cascade
	
)engine=innodb;








