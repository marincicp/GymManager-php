<?php
require_once "config.php";
require_once "fpdf/fpdf.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $first_name = $_POST["first_name"];
    $last_name = $_POST["last_name"];
    $email = $_POST["email"];
    $email = $_POST["email"];
    $phone_number = $_POST["phone_number"];
    $photo_path = $_POST["photo_path"];
    $training_plan_id = $_POST["training_plan_id"];
    $trainer_id = 0;
    $access_card_pdf_path = "";

    $sql = "INSERT INTO members (first_name, last_name, email, phone_number, photo_path, training_plan_id, trainer_id, access_card_pdf_path) VALUES (?,?,?,?,?,?,?,?)";

    $run = $conn->prepare($sql);
    $run->bind_param("sssssiis", $first_name, $last_name, $email, $phone_number, $photo_path, $training_plan_id, $trainer_id, $access_card_pdf_path);
    $run->execute();

    $member_id = $conn->insert_id;

    $pdf = new FPDF();
    $pdf->addPage();
    $pdf->SetFont("Arial", "B", 16);
    $pdf->Cell(0, 10, "Access Card", 0, 1, 'C');
    $pdf->Ln(10);
    $pdf->SetFont("Arial", "", 12);
    $pdf->Cell(40, 10, "Member ID:");
    $pdf->Cell(0, 10, $member_id, 0, 1);
    $pdf->Cell(40, 10, "Name:");
    $pdf->Cell(0, 10, $first_name . " " . $last_name, 0, 1);
    $pdf->Cell(40, 10, "Email:");
    $pdf->Cell(0, 10, $email, 0, 1);

    $filename = "access_cards/access_card_" . $member_id . ".pdf";
    $pdf->Output("F", $filename);


    $sql = "UPDATE  members  SET access_card_pdf_path = '$filename' WHERE member_id = $member_id";

    $conn->query($sql);
    $conn->close();

    $_SESSION["success_message"] = "Member successfully added.";
    header("location: admin_dashboard.php");
    exit();
}
