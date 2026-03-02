DROP DATABASE IF EXISTS SocialMediaDB;
CREATE DATABASE SocialMediaDB;
USE SocialMediaDB;

DROP TABLE IF EXISTS UserDetails;
DROP TABLE IF EXISTS Users;

CREATE TABLE Users (
    username VARCHAR(50) PRIMARY KEY,
    password VARCHAR(255) NOT NULL,
    created_at DATE
);

INSERT INTO Users (username, password, created_at)
VALUES
('tisha', '$2y$10$rSIpiZAfT.4W6dekETVDveFyB3/R1O4Mjh12gWdZM/ZFurWhLbzz.', '2024-01-01'),
('student1', '$2y$10$q0U8JcpyRkNCdVxQMk6bNe9iW6hw9zeiVi.smLgSKVHWu9U123LZq', '2024-02-01');

CREATE TABLE UserDetails (
    username VARCHAR(50) PRIMARY KEY,
    full_name VARCHAR(100),
    email VARCHAR(100),
    city VARCHAR(50),
    created_at DATE,
    CONSTRAINT fk_userdetails_users
      FOREIGN KEY (username) REFERENCES Users(username)
);

INSERT INTO UserDetails (username, full_name, email, city, created_at)
VALUES
('tisha', 'Sirazum Tisha', 'tisha@email.com', 'Orlando', '2024-01-01'),
('student1', 'John Miller', 'john@email.com', 'Miami', '2024-02-01');

SELECT * FROM Users JOIN UserDetails ON Users.username = UserDetails.username;
SELECT * FROM Users INNER JOIN UserDetails USING (username);