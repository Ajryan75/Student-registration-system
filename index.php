<?php
require_once "db.php";

$errors = [];
$success = isset($_GET["success"]);

$full_name = "";
$email = "";
$course = "";
$year_of_study = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $full_name = trim($_POST["full_name"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $course = trim($_POST["course"] ?? "");
    $year_of_study = trim($_POST["year_of_study"] ?? "");

    if ($full_name === "") {
        $errors[] = "Please enter the student's full name.";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Please enter a valid email address.";
    }

    if ($course === "") {
        $errors[] = "Please select a course.";
    }

    if (!in_array($year_of_study, ["1", "2", "3", "4"], true)) {
        $errors[] = "Please select a valid year of study.";
    }

    if (!$errors) {
        try {
            $stmt = $pdo->prepare(
                "INSERT INTO students (full_name, email, course, year_of_study)
                 VALUES (:full_name, :email, :course, :year_of_study)"
            );
            $stmt->execute([
                ":full_name" => $full_name,
                ":email" => $email,
                ":course" => $course,
                ":year_of_study" => (int)$year_of_study
            ]);

            header("Location: index.php?success=1");
            exit;
        } catch (PDOException $e) {
            if ($e->getCode() === "23000") {
                $errors[] = "That email address is already registered.";
            } else {
                $errors[] = "The student could not be saved. Please try again.";
            }
        }
    }
}

$stmt = $pdo->query(
    "SELECT id, full_name, email, course, year_of_study, registered_at
     FROM students
     ORDER BY registered_at DESC"
);
$students = $stmt->fetchAll();

function h($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, "UTF-8");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>University Student Registration</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header class="site-header">
        <div class="container">
            <p class="eyebrow">UNIVERSITY ADMINISTRATION</p>
            <h1>Student Registration System</h1>
            <p>Register students and manage the student list.</p>
        </div>
    </header>

    <main class="container main-content">
        <section class="panel">
            <h2>Register a student</h2>
            <p class="muted">Complete the fields below to add a student record.</p>

            <?php if ($success): ?>
                <div class="message success">Student registered successfully.</div>
            <?php endif; ?>

            <?php if ($errors): ?>
                <div class="message error">
                    <strong>Please correct the following:</strong>
                    <ul>
                        <?php foreach ($errors as $error): ?>
                            <li><?= h($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form method="POST" action="index.php">
                <label for="full_name">Full name</label>
                <input id="full_name" name="full_name" type="text"
                       value="<?= h($full_name) ?>" required maxlength="120"
                       placeholder="e.g. Alex Ramtoola">

                <label for="email">Email address</label>
                <input id="email" name="email" type="email"
                       value="<?= h($email) ?>" required maxlength="190"
                       placeholder="e.g. alex@example.com">

                <label for="course">Course / programme</label>
                <select id="course" name="course" required>
                    <option value="">Choose a course</option>
                    <?php
                    $courses = [
                        "Software Engineering",
                        "Computer Science",
                        "Information Systems",
                        "Business Management",
                        "Electrical Engineering"
                    ];
                    foreach ($courses as $course_option):
                    ?>
                        <option value="<?= h($course_option) ?>"
                            <?= $course === $course_option ? "selected" : "" ?>>
                            <?= h($course_option) ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <label for="year_of_study">Year of study</label>
                <select id="year_of_study" name="year_of_study" required>
                    <option value="">Choose a year</option>
                    <?php for ($year = 1; $year <= 4; $year++): ?>
                        <option value="<?= $year ?>"
                            <?= $year_of_study === (string)$year ? "selected" : "" ?>>
                            Year <?= $year ?>
                        </option>
                    <?php endfor; ?>
                </select>

                <button type="submit">Register student</button>
            </form>
        </section>

        <section class="panel">
            <div class="section-heading">
                <div>
                    <h2>Registered students</h2>
                    <p class="muted">Total records: <?= count($students) ?></p>
                </div>
            </div>

            <?php if (!$students): ?>
                <p class="empty-state">No students registered yet. Add the first student using the form.</p>
            <?php else: ?>
                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Student</th>
                                <th>Email</th>
                                <th>Course</th>
                                <th>Year</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($students as $student): ?>
                                <tr>
                                    <td><?= h($student["id"]) ?></td>
                                    <td><?= h($student["full_name"]) ?></td>
                                    <td><?= h($student["email"]) ?></td>
                                    <td><?= h($student["course"]) ?></td>
                                    <td>Year <?= h($student["year_of_study"]) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </section>
    </main>

    <footer class="container footer">
        
    </footer>
</body>
</html>
