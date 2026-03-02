-- Create Database
DROP DATABASE IF EXISTS UniversityDB;
CREATE DATABASE UniversityDB;
USE UniversityDB;

-- Create Tables
CREATE TABLE students (
    student_id INT PRIMARY KEY,
    student_name VARCHAR(100),
    major VARCHAR(100)
);

CREATE TABLE courses (
    course_id INT PRIMARY KEY,
    course_name VARCHAR(100),
    credits INT
);

CREATE TABLE enrollments (
    enrollment_id INT PRIMARY KEY,
    student_id INT,
    course_id INT,
    semester VARCHAR(20),
    grade VARCHAR(5),
    FOREIGN KEY (student_id) REFERENCES students(student_id),
    FOREIGN KEY (course_id) REFERENCES courses(course_id)
);

-- Create View
CREATE VIEW student_course_details AS
SELECT
  s.student_id,
  s.student_name,
  s.major,
  c.course_id,
  c.course_name,
  c.credits,
  e.semester,
  e.grade
FROM students s
INNER JOIN enrollments e
  ON s.student_id = e.student_id
INNER JOIN courses c
  ON e.course_id = c.course_id;
  
INSERT INTO students VALUES
(1, 'Alice Johnson', 'Computer Science'),
(2, 'Bob Smith', 'Mathematics');

INSERT INTO courses VALUES
(101, 'Database Systems', 3),
(102, 'Calculus II', 4);

INSERT INTO enrollments VALUES
(1, 1, 101, 'Fall 2024', 'A'),
(2, 2, 102, 'Fall 2024', 'B');

SELECT * FROM student_course_details;