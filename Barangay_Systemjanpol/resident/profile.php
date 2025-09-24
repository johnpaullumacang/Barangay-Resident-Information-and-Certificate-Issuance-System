    <?php
    session_start();
    include("../config/db.php");
    include("../includes/header.php");
    include("../includes/sidebar_resident.php");
    ?>

    <div class="col-md-10 p-4">
    <h2>My Profile</h2>
    <?php
    if (isset($_SESSION['user_id'])) {
        $uid = $_SESSION['user_id'];

        // ✅ Use LEFT JOIN so user data shows even if resident info missing
        $stmt = $conn->prepare("
            SELECT u.full_name, r.address, r.contact, r.birthdate 
            FROM users u
            LEFT JOIN resident r ON u.user_id = r.user_id
            WHERE u.user_id = ?
        ");

        // If your user_id is INT, use "i"
        $stmt->bind_param("i", $uid);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            echo "<p><b>Name:</b> " . htmlspecialchars($row['full_name']) . "</p>";

            if ($row['address'] || $row['contact'] || $row['birthdate']) {
                echo "<p><b>Address:</b> " . htmlspecialchars($row['address']) . "</p>";
                echo "<p><b>Contact:</b> " . htmlspecialchars($row['contact']) . "</p>";
                echo "<p><b>Birthdate:</b> " . htmlspecialchars($row['birthdate']) . "</p>";
            } else {
                echo "<div class='alert alert-warning'>No resident details found. Please complete your resident information.</div>";
            }
        } else {
            echo "<div class='alert alert-danger'>User not found in the database.</div>";
        }

        $stmt->close();
    } else {
        echo "<div class='alert alert-danger'>You are not logged in. Please <a href='../auth/login.php'>login</a>.</div>";
    }
    ?>
    </div>

    <?php include("../includes/footer.php"); ?>
