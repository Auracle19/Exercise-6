<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $weight = filter_input(INPUT_POST, 'weight', FILTER_VALIDATE_FLOAT);
    $height = filter_input(INPUT_POST, 'height', FILTER_VALIDATE_FLOAT);
    $birthdate = $_POST['birthdate'] ?? '';
    $errors = [];

    if ($weight <= 0) {
        $errors[] = "Weight must be a positive number.";
    }

    if ($height <= 0) {
        $errors[] = "Height must be a positive number.";
    }

    if ($birthdate) {
        $minDate = new DateTime('2000-01-01');
        if (new DateTime($birthdate) < $minDate) {
            $errors[] = "Birthdate must be from the year 2000.";
        }
    } else {
        $errors[] = "Birthdate is required.";
    }

    if (empty($errors)) {
        $heightInMeters = $height / 100;
        $bmi = $weight / ($heightInMeters * $heightInMeters);
        $classification = '';

        if ($bmi < 18.5) {
            $classification = 'Underweight';
        } elseif ($bmi >= 18.5 && $bmi < 24.9) {
            $classification = 'Normal weight';
        } elseif ($bmi >= 25.0 && $bmi < 29.9) {
            $classification = 'Overweight';
        } else {
            $classification = 'Obese';
        }

        echo json_encode([
            'bmi' => round($bmi, 2),
            'classification' => $classification
        ]);
    } else {
        echo json_encode(['errors' => $errors]);
    }
}
?>
