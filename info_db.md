# DB設計
DB名：hackathon

> show tables;
+---------------------+
| Tables_in_hackathon |
+---------------------+
| games               |
| online_room         |
| players             |
| room                |
| room2               |
| test                |
+---------------------+


> show columns from games;
+--------------+-------------+------+-----+---------------------+----------------+
| Field        | Type        | Null | Key | Default             | Extra          |
+--------------+-------------+------+-----+---------------------+----------------+
| game_id      | int(11)     | NO   | PRI | NULL                | auto_increment |
| status       | varchar(20) | NO   |     | NULL                |                |
| current_turn | int(11)     | YES  |     | 0                   |                |
| max_turns    | int(11)     | YES  |     | NULL                |                |
| created_at   | timestamp   | NO   |     | current_timestamp() |                |
+--------------+-------------+------+-----+---------------------+----------------+

> show columns from online_room;
+--------------+-------------+------+-----+---------+----------------+
| Field        | Type        | Null | Key | Default | Extra          |
+--------------+-------------+------+-----+---------+----------------+
| ID           | int(11)     | NO   | PRI | NULL    | auto_increment |
| roomID       | varchar(10) | NO   |     | NULL    |                |
| creater      | varchar(50) | NO   |     | NULL    |                |
| participant1 | varchar(50) | YES  |     | NULL    |                |
| participant2 | varchar(50) | YES  |     | NULL    |                |
| participant3 | varchar(50) | YES  |     | NULL    |                |
| participant4 | varchar(50) | YES  |     | NULL    |                |
| participant5 | varchar(50) | YES  |     | NULL    |                |
+--------------+-------------+------+-----+---------+----------------+

> show columns from players;
+-----------------+-------------+------+-----+---------+----------------+
| Field           | Type        | Null | Key | Default | Extra          |
+-----------------+-------------+------+-----+---------+----------------+
| player_id       | int(11)     | NO   | PRI | NULL    | auto_increment |
| game_id         | int(11)     | YES  | MUL | NULL    |                |
| name            | varchar(50) | NO   |     | NULL    |                |
| turn_order      | int(11)     | YES  |     | NULL    |                |
| hand            | longtext    | YES  |     | NULL    |                |
| discarded_cards | longtext    | YES  |     | NULL    |                |
+-----------------+-------------+------+-----+---------+----------------+

> show columns from room;
+-------------+-------------+------+-----+---------+----------------+
| Field       | Type        | Null | Key | Default | Extra          |
+-------------+-------------+------+-----+---------+----------------+
| ID          | int(11)     | NO   | PRI | NULL    | auto_increment |
| roomID      | varchar(10) | NO   |     | NULL    |                |
| creater     | varchar(50) | NO   |     | NULL    |                |
| participant | varchar(50) | YES  |     | NULL    |                |
+-------------+-------------+------+-----+---------+----------------+

> show columns from room2;
+-------------+-------------+------+-----+---------+----------------+
| Field       | Type        | Null | Key | Default | Extra          |
+-------------+-------------+------+-----+---------+----------------+
| ID          | int(11)     | NO   | PRI | NULL    | auto_increment |
| roomID      | varchar(10) | NO   |     | NULL    |                |
| creater     | varchar(50) | NO   |     | NULL    |                |
| participant | varchar(50) | YES  |     | NULL    |                |
| state       | varchar(50) | NO   |     | NULL    |                |
+-------------+-------------+------+-----+---------+----------------+

> show columns from test;
+----------+-------------+------+-----+---------+-------+
| Field    | Type        | Null | Key | Default | Extra |
+----------+-------------+------+-----+---------+-------+
| username | varchar(50) | NO   | PRI | NULL    |       |
| card     | longtext    | YES  |     | NULL    |       |
| reason   | longtext    | YES  |     | NULL    |       |
| reason2  | longtext    | YES  |     | NULL    |       |
| discard  | longtext    | YES  |     | NULL    |       |
+----------+-------------+------+-----+---------+-------+

> show columns from test;
+----------+-------------+------+-----+---------+-------+
| Field    | Type        | Null | Key | Default | Extra |
+----------+-------------+------+-----+---------+-------+
| username | varchar(50) | NO   | PRI | NULL    |       |
| card     | longtext    | YES  |     | NULL    |       |
| reason   | longtext    | YES  |     | NULL    |       |
| reason2  | longtext    | YES  |     | NULL    |       |
| discard  | longtext    | YES  |     | NULL    |       |
| No.  | int(10)    | YES  |     | NULL    |       |
| active  | varchar(50)    | YES  |     | NULL    |       |
+----------+-------------+------+-----+---------+-------+