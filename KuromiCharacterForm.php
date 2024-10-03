<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kuromi Character Form</title>
    <link rel="stylesheet" href="form.css">
</head>
<body>
    <div class="container">
        <form class="kuromiform" action="" method="post">
            <h1>Kuromi Character Form</h1>
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required placeholder="Enter your name" value="<?php echo isset($_SESSION['last_name']) ? htmlspecialchars($_SESSION['last_name']) : ''; ?>">
            <div id="response"></div>

            <label for="age">Age:</label>
            <input type="number" id="age" name="age" min="1" required placeholder="Enter your age" value="<?php echo isset($_SESSION['last_age']) ? htmlspecialchars($_SESSION['last_age']) : ''; ?>">
            
            <input type="submit" value="Submit">
        </form>

        <form action="form.html" method="get" style="text-align: center; margin-top: 20px;">
            <input type="submit" value="Back to Form" class="small-button">
        </form>

        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $name = trim(filter_input(INPUT_POST, 'name'));
            $age = filter_input(INPUT_POST, 'age', FILTER_VALIDATE_INT);

            // Check for duplicate character
            $duplicate = false;
            if (!empty($_SESSION['characters'])) {
                foreach ($_SESSION['characters'] as $character) {
                    if (strcasecmp($character['name'], $name) === 0 && $character['age'] === $age) {
                        $duplicate = true;
                        break;
                    }
                }
            }

            if ($duplicate) {
                echo "<p class='error'>Character with the same name and age already exists.</p>";
            } elseif (empty($name) || !preg_match("/^[A-Za-z\s]+$/", $name)) {
                echo "<p class='error'>Invalid name. Only letters and spaces are allowed.</p>";
            } elseif ($age < 1 || $age > 120) {
                echo "<p class='error'>Invalid age. Please enter an age between 1 and 120.</p>";
            } else {
                $_SESSION['characters'][] = ['name' => $name, 'age' => $age];
                $_SESSION['last_name'] = $name;
                $_SESSION['last_age'] = $age;
            }
        }

        if (!empty($_SESSION['characters'])) {
            echo "<h1>Character Information</h1>";
            foreach ($_SESSION['characters'] as $index => $character) {
                echo "<div class='character-card'>";
                echo "<p><strong>Name:</strong> " . htmlspecialchars($character['name']) . "</p>";
                echo "<p><strong>Age:</strong> " . htmlspecialchars($character['age']) . "</p>";
                echo '<form method="POST" action="" style="text-align: center;">
                        <input type="hidden" name="delete_index" value="' . $index . '">
                        <p>Are you sure you want to delete <strong>' . htmlspecialchars($character['name']) . '</strong>, Age: <strong>' . htmlspecialchars($character['age']) . '</strong>?</p>
                        <input type="submit" value="Delete Character">
                      </form>';
                echo "</div>";
            }
        } else {
            echo "<p>No character information available.</p>";
        }

        if (isset($_POST['delete_index'])) {
            $deleteIndex = filter_input(INPUT_POST, 'delete_index', FILTER_VALIDATE_INT);
            if (isset($_SESSION['characters'][$deleteIndex])) {
                $deleteName = $_SESSION['characters'][$deleteIndex]['name'];
                unset($_SESSION['characters'][$deleteIndex]);
                $_SESSION['characters'] = array_values($_SESSION['characters']); // Reindex array
                echo "<div class='success'>Character '<strong>" . htmlspecialchars($deleteName) . "</strong>' deleted successfully.</div>";
            }
        }
        ?>

    </div>
    <script src="form.js"></script>
</body>
</html>
