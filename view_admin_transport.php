<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transport Booking Details - Medical Tourism Service</title>
    <style>
        /* CSS styles */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-image: url('transport.png');
            background-size: cover;
            background-position: center;
        }

        header {
            background-color: #333;
            color: #fff;
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        header nav ul {
            list-style-type: none;
            margin: 0;
            padding: 0;
            display: flex;
        }
        header nav ul li {
            margin-right: 20px;
        }
        header nav ul li a {
            color: #fff;
            text-decoration: none;
            font-weight: bold;
        }
        header nav ul li a:hover {
            text-decoration: underline;
        }
        .container {
            margin: 50px auto;
            width: 80%;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            border-radius: 5px;
            overflow: hidden;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }
        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #333;
            color: #fff;
        }
        tbody tr:hover {
            background-color: #f5f5f5;
        }
        .update-button {
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            padding: 8px 12px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .update-button:hover {
            background-color: #0056b3;
        }
        .update-form {
            display: none;
        }
        .update-form input[type="text"],
        .update-form input[type="date"],
        .update-form input[type="time"] {
            width: 100%;
            padding: 8px;
            margin: 5px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }
        .update-form input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <header>
        <div class="logo">
            <img src="logo.png" alt="Medical Tourism Service Logo">
            <h1>Medical Tourism Service</h1>
        </div>
        <nav>
            <ul>
                <li><a href="dashboard.php">Home</a></li>
                <li><a href="login_admin.php">Admin</a></li>
                <li><a href="login_user.php">User</a></li>
                <li><a href="services.php">Services</a></li>
                <li><a href="help.php">Help</a></li>
            </ul>
        </nav>
    </header>

    <div class="container">
        <h2>Transport Booking Details</h2>
        <!-- Search Form -->
        <form method="GET" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <label for="patient_id">Search by Patient ID:</label>
            <input type="text" id="patient_id" name="patient_id" value="<?php echo isset($_GET['patient_id']) ? $_GET['patient_id'] : ''; ?>">
            <input type="submit" value="Search">
        </form>

        <table>
            <thead>
                <tr>
                    <th>Patient ID</th>
                    <th>Transport Type</th>
                    <th>Pickup Location</th>
                    <th>Destination</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Action</th> <!-- Added column for action button -->
                </tr>
            </thead>
            <tbody>
                <?php
                // Connect to the database
                $servername = "localhost";
                $username = "root";
                $password = "";
                $dbname = "mt_db";

                $conn = new mysqli($servername, $username, $password, $dbname);

                // Check connection
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                // Initialize search variable
                $search_patient_id = "";

                // Check if search form is submitted
                if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["patient_id"])) {
                    $search_patient_id = $_GET["patient_id"];
                }

                // Retrieve transport booking data from the database
                $sql = "SELECT * FROM transport_bookings";

                // If search query is provided, add WHERE clause
                if (!empty($search_patient_id)) {
                    $sql .= " WHERE patient_id LIKE '%$search_patient_id%'";
                }

                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>".$row['patient_id']."</td>";
                        echo "<td>".$row['transport_type']."</td>";
                        echo "<td>".$row['pickup_location']."</td>";
                        echo "<td>".$row['destination']."</td>";
                        echo "<td>".$row['date']."</td>";
                        echo "<td>".$row['time']."</td>";
                        echo "<td><button class='update-button' onclick='showUpdateForm(".$row['patient_id'].")'>Update</button></td>"; // Update button added
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='7'>No transport bookings found</td></tr>";
                }

                // Close database connection
                $conn->close();
                ?>
            </tbody>
        </table>
    </div>

    <div class="container">
        <form id="updateForm" class="update-form" method="post">
            <h2>Update Transport Booking</h2>
            <input type="hidden" id="patient_id" name="patient_id">
            <label for="transport_type">Transport Type:</label>
            <input type="text" id="transport_type" name="transport_type" required>
            <label for="pickup_location">Pickup Location:</label>
            <input type="text" id="pickup_location" name="pickup_location" required>
            <label for="destination">Destination:</label>
            <input type="text" id="destination" name="destination" required>
            <label for="date">Date:</label>
            <input type="date" id="date" name="date" required>
            <label for="time">Time:</label>
            <input type="time" id="time" name="time" required>
            <input type="submit" name="update" value="Update">
        </form>
    </div>

    <script>
        function showUpdateForm(patientId) {
            var updateForm = document.getElementById("updateForm");
            updateForm.style.display = "block";

            // Fetch data for the specific patient ID and populate the form fields
            var xhr = new XMLHttpRequest();
            xhr.open("GET", "fetch_data.php?patient_id=" + patientId, true);
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    var data = JSON.parse(xhr.responseText);
                    document.getElementById("patient_id").value = data.patient_id;
                    document.getElementById("transport_type").value = data.transport_type;
                    document.getElementById("pickup_location").value = data.pickup_location;
                    document.getElementById("destination").value = data.destination;
                    document.getElementById("date").value = data.date;
                    document.getElementById("time").value = data.time;
                }
            };
            xhr.send();
        }
    </script>
</body>
</html>
