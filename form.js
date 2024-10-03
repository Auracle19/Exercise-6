document.getElementById('bmiForm').addEventListener('submit', function(event) {
    event.preventDefault();

    const formData = new FormData(this);

    fetch('KuromiBMI.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        document.getElementById('result').innerText = '';
        document.getElementById('classification').innerText = '';

        if (data.errors) {
            data.errors.forEach(error => {
                alert(error);
            });
        } else {
            document.getElementById('result').innerText = `Your BMI is ${data.bmi}.`;
            document.getElementById('classification').innerText = `Classification: ${data.classification}`;
        }
    })
    .catch(error => console.error('Error:', error));
});
