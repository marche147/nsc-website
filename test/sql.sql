create database nsc_website;
use nsc_website;
create table user(uid  int(10)  not null,
				uname  varchar(40) not null,
				passwd varchar(40) not null,
				salt   varchar(40) not null,
				level  int(10) default '0'  not null,
				email  varchar(40)  not null, 
				score  int(10)  default '0' not null,
				new_message int(10)  not null,
				last_login_ip varchar(20) not null,
				primary key(uid)
);
create table message(mid int(10) not null, 
					from_uid  int(10) not null,
					to_uid   int(10)  not null,
					message_time timestamp  not null,
					content   varchar(3000)   not null,
					primary key(mid)
);
create table download(did int(10) not null,
					download_content varchar(3000) not null,
					address  varchar(40) not null,
					frequency int(10)  not null,
					primary key(did)
);
create table galary(aid int(10) not null,
					article varchar(3000) not null,
					article_time timestamp not null,
					writer varchar(40) not null,
					primary key(aid)
);
create table db_status(user_number int(10) default '0' not null,
					  now_time timestamp   not null
);
insert into db_status values('0','');