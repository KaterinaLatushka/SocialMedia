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
FROM students AS s
JOIN enrollments AS e ON s.student_id = e.student_id
JOIN courses AS c ON e.course_id = c.course_id;
