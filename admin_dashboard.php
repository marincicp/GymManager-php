<?php

require_once "config.php";


if (!isset($_SESSION["admin_id"])) {

    header("location: index.php");
    exit();
}



$sql = "SELECT * FROM training_plans";


$run = $conn->query($sql);

$res = $run->fetch_all(MYSQLI_ASSOC);


?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="./style.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css" type="text/css" />

    <title>Dashboard</title>
</head>

<body>


    <?php
    if (isset($_SESSION["success_message"])) {

        echo "<div class='alert'>
              <p>{$_SESSION['success_message']}</p>
             </div>";
        unset($_SESSION["success_message"]);
    }
    ?>

    <div class="container">

        <div class="wrapper-form">

            <form action="register_member.php" method="POST" enctype="multipart/form-data">


                <h2>Register member</h2>

                <div class="input_wraper">
                    <label for="first_name">First Name</label>
                    <input type="text" name="first_name">
                </div>

                <div class="input_wraper">
                    <label for="last_name">Last Name</label>
                    <input type="text" name="last_name">
                </div>

                <div class="input_wraper">
                    <label for="email">Email</label>
                    <input type="text" name="email">
                </div>

                <div class="input_wraper">
                    <label for="phone_number">Phone</label>
                    <input type="text" name="phone_number">
                </div>

                <div class="input_wraper">
                    <label>Select training plan</label>
                    <select name="training_plan_id">
                        <?php
                        foreach ($res as $plan) {
                            echo "<option value='{$plan['plan_id']}'>{$plan['name']}</option>";
                        }
                        ?>
                </div>
                </select>
        </div>
        <input type="hidden" name="photo_path" id="photoPathInput">
        <div id="dropzone-upload" class="dropzone">

        </div>

        <button type="submit">Add</button>
        </form>


    </div>


    <div class="wrapper-table">
        <h2>Members List</h2>

        <table>
            <tr class="header">
                <th>First Name</th>
                <th>Last Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Trainer</th>
                <th>Training Plan</th>
                <th>Image</th>
                <th>Access Card</th>
                <th>Created</th>
                <th>Action</th>
            </tr>

            <tbody>
                <?php
                $sql = "SELECT members.*, training_plans.name as training_plan_name,trainers.first_name as trainer_first_name, trainers.last_name as trainer_last_name FROM `members` LEFT JOIN training_plans ON members.training_plan_id = training_plans.plan_id LEFT JOIN trainers ON members.trainer_id = trainers.trainer_id";

                $run = $conn->query($sql);

                $res = $run->fetch_all(MYSQLI_ASSOC);

                $select_member = $res;

                foreach ($res as $row) {
                    $plan = $row['training_plan_name'] ?? "No plan";
                    $trainer = $plan = $row['trainer_first_name'] ? $row['trainer_first_name'] . " " . $row['trainer_last_name'] : "No Trainer";
                    $time = strtotime($row["created_at"]);
                    $new_time = date("F, jS Y", $time);

                    echo "<tr>
                    <td>{$row['first_name']}</td>
                    <td>{$row['last_name']}</td>
                    <td>{$row['email']}</td>
                    <td>{$row['phone_number']}</td>
                    <td>{$row['trainer_id']}</td>
                    <td>{$plan}</td>
                    <td><img src='{$row['photo_path']}' /></td>
                    <td><a target='_blank' href='{$row['access_card_pdf_path']}'>Open card</a></td>
                    <td>{$new_time}</td>
                    
                    <td>
                   <form action='delete_member.php' method='POST'>
                   <input type='hidden' name='member_id' value='{$row['member_id']}' />
                   <button>Delete</button>
                   </form>
                   </td>
                    </tr>";
                }

                ?>
            </tbody>
        </table>
    </div>






    </div>

    <div class="container margin-top">


        <div class="wrapper-form">


            <form action="register_trainer.php" method="POST">

                <h2>Register Trainer</h2>

                <div class="input_wraper">
                    <label for="first_name">First Name</label>
                    <input type="text" name="first_name">
                </div>

                <div class="input_wraper">
                    <label for="last_name">Last Name</label>
                    <input type="text" name="last_name">
                </div>
                <div class="input_wraper">
                    <label for="last_name">Email</label>
                    <input type="email" name="email">
                </div>
                <div class="input_wraper">
                    <label for="phone">Phone</label>
                    <input type="text" name="phone">
                </div>
                <button type="submit">Submit</button>
        </div>


        <div class="wrapper-table">
            <h2>Trainer List</h2>
            <table>
                <tr class="header">
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Email</th>
                    <th>Phone</th>

                </tr>

                <tbody>

                    <?php
                    $sql = "SELECT * FROM trainers";

                    $run = $conn->query($sql);
                    $res = $run->fetch_all(MYSQLI_ASSOC);

                    $select_trainers = $res;
                    foreach ($res as $row) {
                        echo "
<tr>
       <td>{$row['first_name']}</td>
       <td>{$row['last_name']}</td>
       <td>{$row['email']}</td>
       <td>{$row['phone_number']}</td>
</tr>";
                    }
                    $conn->close();
                    ?>


                </tbody>
            </table>
        </div>
        <div class="wrapper-form">
            <form action="assign_trainer.php" method="POST">
                <h2>Assign trainer to member</h2>

                <div class="input_wraper">
                    <label for="first_name">Select member</label>
                    <select name="member">
                        <?php

                        foreach ($select_member as $member) {
                            echo "<option value='{$member['member_id']}'> {$member['first_name']}  {$member['last_name']} </option>";
                        }


                        ?>

                    </select>
                </div>

                <div class="input_wraper">
                    <label for="trainer">Select trainer</label>
                    <select name="trainer">

                        <?php

                        foreach ($select_trainers as $trainer) {
                            echo "<option value='{$trainer['trainer_id']}'> {$trainer['first_name']}  {$trainer['last_name']} </option>";
                        }


                        ?>
                    </select>
                </div>

                <button type="submit">Submit</button>
            </form>
        </div>
    </div>





    <script src=" https://unpkg.com/dropzone@5/dist/min/dropzone.min.js">
    </script>



    <script>
        Dropzone.options.dropzoneUpload = {
            url: "upload_photo.php",
            paramName: "photo",
            maxFilesize: 20, // MB
            acceptedFiles: "image/*",
            init: function () {
                this.on("success", function (file, response) {
                    const jsonResponse = JSON.parse(response);


                    console.log("ress", response)

                    if (jsonResponse.success) {

                        document.querySelector("#photoPathInput").value = jsonResponse.photo.path;

                    } else {

                        console.error(jsonResponse.error)
                    }

                })
            }
        };
    </script>
</body>

</html>