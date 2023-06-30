USE booknow;

INSERT INTO user(email, roles, password, name, last_name, phone)
VALUES ('admin@outlook.com', '["ROLE_USER", "ROLE_EMPLEADO", "ROLE_ADMIN"]', '$2y$13$n7Ojcm1VZIL6WjhIGsxrQOj8PFgXNEpUwyK6HV2G2fMEXWru9ZlFa', 'Administrador', '', 111111111);

INSERT INTO user(email, roles, password, name, last_name, phone)
VALUES ('jose_016al@outlook.com', '["ROLE_USER", "ROLE_EMPLEADO"]', '$2y$13$n7Ojcm1VZIL6WjhIGsxrQOj8PFgXNEpUwyK6HV2G2fMEXWru9ZlFa', 'Jose', 'Almiron', 111111111);

INSERT INTO user(email, roles, password, name, last_name, phone)
VALUES ('nerea@outlook.com', '["ROLE_USER"]', '$2y$13$n7Ojcm1VZIL6WjhIGsxrQOj8PFgXNEpUwyK6HV2G2fMEXWru9ZlFa', 'nerea', 'Berrio', 111111111);

INSERT INTO user(email, roles, password, name, last_name, phone)
VALUES ('example1@example.com', '["ROLE_USER"]', '$2y$13$n7Ojcm1VZIL6WjhIGsxrQOj8PFgXNEpUwyK6HV2G2fMEXWru9ZlFa', 'John', 'Doe', 111111111);

INSERT INTO user(email, roles, password, name, last_name, phone)
VALUES ('example2@example.com', '["ROLE_USER"]', '$2y$13$n7Ojcm1VZIL6WjhIGsxrQOj8PFgXNEpUwyK6HV2G2fMEXWru9ZlFa', 'Jane', 'Smith', 222222222);

INSERT INTO user(email, roles, password, name, last_name, phone)
VALUES ('example3@example.com', '["ROLE_USER"]', '$2y$13$n7Ojcm1VZIL6WjhIGsxrQOj8PFgXNEpUwyK6HV2G2fMEXWru9ZlFa', 'Michael', 'Johnson', 333333333);

INSERT INTO user(email, roles, password, name, last_name, phone)
VALUES ('example4@example.com', '["ROLE_USER"]', '$2y$13$n7Ojcm1VZIL6WjhIGsxrQOj8PFgXNEpUwyK6HV2G2fMEXWru9ZlFa', 'Emily', 'Brown', 444444444);

INSERT INTO user(email, roles, password, name, last_name, phone)
VALUES ('example5@example.com', '["ROLE_USER"]', '$2y$13$n7Ojcm1VZIL6WjhIGsxrQOj8PFgXNEpUwyK6HV2G2fMEXWru9ZlFa', 'David', 'Lee', 555555555);

INSERT INTO user(email, roles, password, name, last_name, phone)
VALUES ('example6@example.com', '["ROLE_USER"]', '$2y$13$n7Ojcm1VZIL6WjhIGsxrQOj8PFgXNEpUwyK6HV2G2fMEXWru9ZlFa', 'Sophia', 'Taylor', 666666666);

INSERT INTO user(email, roles, password, name, last_name, phone)
VALUES ('example7@example.com', '["ROLE_USER"]', '$2y$13$n7Ojcm1VZIL6WjhIGsxrQOj8PFgXNEpUwyK6HV2G2fMEXWru9ZlFa', 'Daniel', 'Anderson', 777777777);

INSERT INTO booking(user_id, type, date, time, duration, status)
VALUES (2, 'a:3:{i:0;s:6:"Lavado";i:1;s:5:"Corte";i:2;s:7:"Peinado";}', '2023-06-28', '17:00:00', 75, 1);

INSERT INTO booking(user_id, type, date, time, duration, status)
VALUES (3, 'a:3:{i:0;s:6:"Lavado";i:1;s:5:"Corte";i:2;s:7:"Peinado";}', '2023-06-29', '14:00:00', 75, 2);

INSERT INTO booking(user_id, type, date, time, duration, status)
VALUES (4, 'a:3:{i:0;s:6:"Lavado";i:1;s:5:"Corte";i:2;s:7:"Peinado";}', '2023-06-30', '09:00:00', 75, 1);

INSERT INTO booking(user_id, type, date, time, duration, status)
VALUES (5, 'a:3:{i:0;s:6:"Lavado";i:1;s:5:"Corte";i:2;s:7:"Peinado";}', '2023-07-01', '12:45:00', 75, 2);

INSERT INTO booking(user_id, type, date, time, duration, status)
VALUES (6, 'a:3:{i:0;s:6:"Lavado";i:1;s:5:"Corte";i:2;s:7:"Peinado";}', '2023-07-02', '17:00:00', 75, 1);

INSERT INTO booking(user_id, type, date, time, duration, status)
VALUES (7, 'a:3:{i:0;s:6:"Lavado";i:1;s:5:"Corte";i:2;s:7:"Peinado";}', '2023-07-03', '10:30:00', 75, 2);

INSERT INTO booking(user_id, type, date, time, duration, status)
VALUES (8, 'a:3:{i:0;s:6:"Lavado";i:1;s:5:"Corte";i:2;s:7:"Peinado";}', '2023-07-04', '13:00:00', 75, 1);

INSERT INTO booking(user_id, type, date, time, duration, status)
VALUES (9, 'a:3:{i:0;s:6:"Lavado";i:1;s:5:"Corte";i:2;s:7:"Peinado";}', '2023-07-05', '11:15:00', 75, 2);

INSERT INTO booking(user_id, type, date, time, duration, status)
VALUES (10, 'a:3:{i:0;s:6:"Lavado";i:1;s:5:"Corte";i:2;s:7:"Peinado";}', '2023-07-06', '09:30:00', 75, 1);