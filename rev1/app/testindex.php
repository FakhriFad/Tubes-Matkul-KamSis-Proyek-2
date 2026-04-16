<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP Environment Test</title>
    <style>
        body { font-family: sans-serif; line-height: 1.6; padding: 20px; max-width: 800px; margin: auto; background: #f4f4f9; }
        .card { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); margin-bottom: 20px; }
        .success { color: #2ecc71; font-weight: bold; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>

    <h1>PHP System Test Page</h1>

    <div class="card">
        <h3>1. Basic PHP Status</h3>
        <p>Current Server Time: <strong><?php echo date('Y-m-d H:i:s'); ?></strong></p>
        <p>PHP Version: <strong><?php echo phpversion(); ?></strong></p>
        <p class="success">✔ If you can see the time above, PHP is executing correctly.</p>
    </div>

    <div class="card">
        <h3>2. Test Form Submission (POST)</h3>
        <form method="POST" action="">
            <label>Type something:</label><br>
            <input type="text" name="test_input" placeholder="Hello World..." required>
            <button type="submit">Submit Test</button>
        </form>

        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $input = htmlspecialchars($_POST['test_input']);
            echo "<p style='margin-top:15px;'>You submitted: <mark><strong>$input</strong></mark></p>";
        }
        ?>
    </div>

    <div class="card">
        <h3>3. Server Environment Details</h3>
        <table>
            <tr>
                <th>Variable</th>
                <th>Value</th>
            </tr>
            <tr>
                <td>Server Software</td>
                <td><?php echo $_SERVER['SERVER_SOFTWARE']; ?></td>
            </tr>
            <tr>
                <td>Your IP Address</td>
                <td><?php echo $_SERVER['REMOTE_ADDR']; ?></td>
            </tr>
            <tr>
                <td>Script Filename</td>
                <td><?php echo $_SERVER['SCRIPT_FILENAME']; ?></td>
            </tr>
        </table>
    </div>

</body>
</html>