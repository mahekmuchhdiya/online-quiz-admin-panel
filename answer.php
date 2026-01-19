INSERT INTO question( question, ans_id) VALUES (' Which of the following is an input device?',1);
INSERT INTO question( question, ans_id) VALUES (' What does CPU stand for?',2);
INSERT INTO question( question, ans_id) VALUES ('Which of the following is software?',3);
INSERT INTO question( question, ans_id) VALUES ('Which part of the computer is called the ‘brain’?',4);
INSERT INTO question( question, ans_id) VALUES ('What is the full form of RAM?',5);
INSERT INTO question( question, ans_id) VALUES ('Which device is used to display the output?',6);
INSERT INTO question( question, ans_id) VALUES (' What type of software is MS Word?',7);
INSERT INTO question( question, ans_id) VALUES ('Which of the following is a programming language?',8);
INSERT INTO question( question, ans_id) VALUES ('Which key is used to delete characters to the left of the cursor?',9);
INSERT INTO question( question, ans_id) VALUES ('What does www stand for?',10);



1  moniter
2  central processing Unit
3  Windows
4  CPU
5  Random Access Memory
6  moniter
7  application softwere
8  c++
9  Backspace
10 Word Wide Web








INSERT INTO answer( qid,answer) VALUES (1,'Monitor');
INSERT INTO answer( qid,answer ) VALUES (1,'Printer');
INSERT INTO answer( qid,answer ) VALUES (1,'Mouse');
INSERT INTO answer( qid,answer) VALUES (1,'Speaker');

INSERT INTO answer( qid,answer ) VALUES (2,'Central Performance Unit');
INSERT INTO answer( qid,answer ) VALUES (2,'Central Processing Unit'); 
INSERT INTO answer( qid,answer) VALUES (2,' Central Power Unit');
INSERT INTO answer( qid,answer) VALUES (2,' Central powing unit');

INSERT INTO answer( qid,answer ) VALUES (3,'Keyboard';
INSERT INTO answer( qid,answer ) VALUES (3,'Hard Disk');
INSERT INTO answer( qid,answer ) VALUES (3,'Windows');
INSERT INTO answer( qid,answer ) VALUES (3,'RAM');

INSERT INTO answer( qid,answer) VALUES (4,'Monitor';
INSERT INTO answer( qid,answer) VALUES (4,'CPU');
INSERT INTO answer( qid,answer) VALUES (4,'Mouse');
INSERT INTO answer( qid,answer) VALUES (4,'Keyboard');

INSERT INTO answer( qid,answer) VALUES (5,'Read Access Memory);
INSERT INTO answer( qid,answer) VALUES (5,'Random Access Memory');
INSERT INTO answer( qid,answer) VALUES (5,'Run Access Memory');
INSERT INTO answer( qid,answer) VALUES (5,'Real Actual Memory');

INSERT INTO answer( qid,answer) VALUES (6,'Keyboard',);
INSERT INTO answer( qid,answer) VALUES (6,'Mouse');
INSERT INTO answer( qid,answer) VALUES (6,'moniter');
INSERT INTO answer( qid,answer) VALUES (6,'microphone');

INSERT INTO answer( qid,answer) VALUES (7,'System Software');
INSERT INTO answer( qid,answer) VALUES (7,'Application Software');
INSERT INTO answer( qid,answer) VALUES(7,'programming software');
INSERT INTO answer( qid,answer) VALUES (7,'Utility Software');

INSERT INTO answer( qid,answer) VALUES (8,'HTML');
INSERT INTO answer( qid,answer) VALUES (8,'C++');
INSERT INTO answer(qid, answer) VALUES (8,'CSS');
INSERT INTO answer( qid,answer) VALUES (8,'JSON');

INSERT INTO answer(qid, answer) VALUES (9,'Insert');
INSERT INTO answer( qid,answer) VALUES (9,'Delete');
INSERT INTO answer( qid,answer) VALUES (9,'Backspace');
INSERT INTO answer( qid,answer) VALUES (9,'Esc');

INSERT INTO answer(qid, answer) VALUES (10,'World Word Web');
INSERT INTO answer(qid,answer) VALUES (10,'Wide World Web');
INSERT INTO answer(qid,answer) VALUES (10,'Web World Wide';
INSERT INTO answer(qid,answer) VALUES (10,'Web World Wed');



				question table ma
				id   question   ans_id
				int    text      int
				
									
				answer table ma:-
				id     qid      answer						
				int	    int		varchar
				
				
				user table ma 
	1	id Primary	int(11)			No	None		AUTO_INCREMENT	
	2	name	varchar(255)	utf8mb4_general_ci		No	None				
	3	password	varchar(255)	utf8mb4_general_ci		No	None				
	4	created_at	timestamp			No	current_timestamp()	





				scores table 
				id    int(11) auto _increment
				user_id int
				timing_id int
				score      int
				total_questions int
				created_at   datetime 	 	
				   
				   
	1	id Primary	int(11)				No	None		AUTO_INCREMENT	   Change Change	Drop Drop	
	2	user_id Index	int(11)		No	None			Change Change	Drop Drop	
	forein key avse syad 
	
	3	timing_id Index	int(11)			No	None			Change Change	Drop Drop	
	forei key 
	
	4	score	int(11)					No	None			Change Change	Drop Drop	
	5	total_questions	int(11)			No	None			Change Change	Drop Drop	
	6	created_at	datetime			Yes	current_timestamp()		






			timing table ma 
			CREATE TABLE timing (
			id INT AUTO_INCREMENT PRIMARY KEY,
			user_id INT,
			quiz_date DATE,
			start_time TIME,
			end_time TIME,
			FOREIGN KEY (user_id) REFERENCES user(id)
);

