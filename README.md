# ✅ Features Overview

This document summarizes what **Students** and **Teachers** can and cannot do in the system,
based on the passing feature tests.

---

## 👨‍🏫 Teacher Capabilities

### ✅ Can Do
- View, create, edit, and delete **Assignments**.
- Upload and delete assignment **files**.
- View all **student submissions**, grades, and comments.
- Update **student grades and feedback** (with optional file upload).
- View **Chats** with students and mark unread messages as read.
- Send messages to students.
- View **Courses**, create new ones with images, update and delete them.
- View **Dashboard** successfully.
- Manage **Enrollments**:
  - Approve, reject, or delete student enrollment requests.
- Create and manage **Exams**:
  - Add, edit, update, and delete **Exam Questions**.
  - View student exam results with all questions and correct answers.
- Manage **Groups**:
  - View own groups.
  - Create, update, and delete groups.
  - Add and remove students from their groups.
  - Approve or reject join requests.
  - Search students.
- Manage **Lessons**:
  - Create lessons with files and video.
  - View, edit, update, and delete lessons.
- Manage **Sessions**:
  - View, create, update, and delete sessions.
- Manage **Contacts**:
  - View, delete, and manage public contact messages.
- Manage **Profile**:
  - View, update, and delete their profile.
  - Change password.
- Access **Authentication** and **Registration**:
  - Login, logout, register, verify email, reset password.

### ❌ Cannot Do
- Access another teacher’s course, lesson, or group.
- Modify or delete another teacher’s exams or questions.
- Access unauthorized routes for students or guests.

---

## 👨‍🎓 Student Capabilities

### ✅ Can Do
- Submit **Assignments** with text and/or files.
- Resubmit assignments (with text or file).
- View their own **submission results** after submission.
- Send and receive **Messages** with teachers.
- View **Available Courses**, **Lessons**, and **Sessions** if enrolled.
- View **Exams** and their **Results** after taking them.
- Submit **Enrollment requests** for courses.
- Join **Groups** or send **Join Requests** to teachers.
- View **Dashboard** and **Profile** pages.
- Stream **Lesson Videos** if enrolled.
- Access authentication routes (login, logout, registration, password reset).

### ❌ Cannot Do
- Submit the same assignment twice.
- Submit an assignment without text or file.
- Submit invalid file types.
- View results without submitting.
- Access assignments, exams, or lessons if not enrolled or not in a group.
- Access teacher routes or manage other students.
- Create or manage groups, courses, lessons, exams, or sessions.

---

## 🧭 Guest User Restrictions

### ❌ Cannot Do
- Access any course, group, enrollment, or lesson routes.
- Perform any student or teacher actions.
- Must authenticate to access the system.

---

✅ **Total Tests Passed:** 141  
⚙️ **Assertions:** 433  
⏱️ **Duration:** 66.51s  

---

**System Status:** 🟢 All features working as expected.
