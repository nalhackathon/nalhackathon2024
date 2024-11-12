# DB設計
DB名：hackathon

> show tables;
+---------------------+
| Tables_in_hackathon |
+---------------------+
| games               |
| players             |
| room                |
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