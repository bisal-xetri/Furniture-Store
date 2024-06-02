<?php
require('fpdf/fpdf.php');
include_once('include/dbcon.php');

class PDF extends FPDF
{
    // Page header
    function Header()
    {
        $this->SetFont('Arial', 'B', 14);
        $this->Cell(130, 10, 'Himalayan Furniture Shop', 0, 0);
        $this->Cell(59, 10, 'INVOICE', 0, 1);
        $this->SetFont('Arial', 'I', 12);
        $this->Cell(130, 10, 'Ganeshman Singh Path, Kathmandu, 3400', 0, 1);
        $this->Cell(130, 10, 'Phone: 01-4283886', 0, 1);
        $this->Ln(10); // Line break
    }

    // // Page footer
    // function Footer()
    // {
    //     // Position at 1.5 cm from bottom
    //     $this->SetY(-15);
    //     // Arial italic 8
    //     $this->SetFont('Arial', 'I', 8);
    //     // Page number
    //     $this->Cell(0, 10, 'Page ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
    // }
}

$pdf = new PDF('P', 'mm', 'A4');
$pdf->AliasNbPages();
$pdf->AddPage();

if (isset($_GET['order_id'])) {
    $order_id = $_GET['order_id'];

    // Set font to Arial, regular, 12pt
    $pdf->SetFont('Arial', '', 12);

    $query = "SELECT * FROM tbl_order WHERE id = $order_id";
    $run = mysqli_query($con, $query);
    $row = mysqli_fetch_array($run);

    $cust_id = $row['customer_id'];
    $product_id = $row['product_id'];
    $product_qty = $row['qty'];
    $product_amount = $row['total'];
    $order_date = $row['order_date'];
    $order_status = $row['status'];
    $esewa = $row['esewa'];

    // Customer query
    $query = "SELECT * FROM tbl_user WHERE id=$cust_id";
    $run = mysqli_query($con, $query);
    $row = mysqli_fetch_array($run);
    $cust_name = $row['full_name'];
    $cust_email = $row['email'];
    $cust_add = $row['address'];
    $cust_number = $row['phone'];

    // Product query
    $query = "SELECT * FROM tbl_furniture WHERE id=$product_id";
    $run = mysqli_query($con, $query);
    $row = mysqli_fetch_array($run);
    $title = $row['title'];
    $price = $row['price'];

    // Invoice details
    $pdf->Cell(130, 5, '', 0, 0);
    $pdf->Cell(25, 5, 'Date', 0, 0);
    $pdf->Cell(34, 5, $order_date, 0, 1);

    $pdf->Cell(130, 5, '', 0, 0);
    $pdf->Cell(25, 5, 'Order No', 0, 0);
    $pdf->Cell(34, 5, $order_id, 0, 1);

    $pdf->Cell(130, 5, '', 0, 0);
    $pdf->Cell(25, 5, 'Customer ID', 0, 0);
    $pdf->Cell(34, 5, $cust_id, 0, 1);

    // Add a vertical spacer
    $pdf->Cell(189, 10, '', 0, 1);

    // Billing address
    $pdf->Cell(100, 5, 'Bill to', 0, 1);

    $pdf->Cell(10, 5, '', 0, 0);
    $pdf->Cell(90, 5, $cust_name, 0, 1);

    $pdf->Cell(10, 5, '', 0, 0);
    $pdf->Cell(90, 5, $cust_email, 0, 1);

    $pdf->Cell(10, 5, '', 0, 0);
    $pdf->Cell(90, 5, $cust_number, 0, 1);

    // Add a vertical spacer
    $pdf->Cell(189, 10, '', 0, 1);

    // Invoice contents
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(130, 5, 'Description', 1, 0);
    $pdf->Cell(25, 5, 'Quantity', 1, 0);
    $pdf->Cell(34, 5, 'Single Amount', 1, 1);

    $pdf->SetFont('Arial', '', 10);
    $pdf->Cell(130, 5, $title, 1, 0);
    $pdf->Cell(25, 5, $product_qty, 1, 0);
    $pdf->Cell(34, 5, 'Rs. ' . number_format($price, 2), 1, 1, 'R');

    // Summary
    $pdf->Cell(130, 5, '', 0, 0);
    $pdf->Cell(25, 5, 'Shipping', 1, 0);
    $pdf->Cell(10, 5, 'Rs.', 1, 0);
    $pdf->Cell(24, 5, '0.00', 1, 1, 'R');

    $pdf->Cell(130, 5, '', 0, 0);
    $pdf->Cell(25, 5, 'Subtotal', 1, 0);
    $pdf->Cell(10, 5, 'Rs.', 1, 0);
    $pdf->Cell(24, 5, 'Rs. ' . number_format($product_amount, 2), 1, 1, 'R');

    // Conditionally add Total Due
    if ($esewa == 1) {
        $pdf->Cell(130, 5, '', 0, 0);
        $pdf->Cell(25, 5, 'Total Paid', 1, 0);
        $pdf->Cell(10, 5, 'Rs.', 1, 0);
        $pdf->Cell(24, 5, 'Rs. ' . number_format($product_amount, 2), 1, 1, 'R');
    }else{
        $pdf->Cell(130, 5, '', 0, 0);
        $pdf->Cell(25, 5, 'Total Due', 1, 0);
        $pdf->Cell(10, 5, 'Rs.', 1, 0);
        $pdf->Cell(24, 5, 'Rs. ' . number_format($product_amount, 2), 1, 1, 'R');
    }
}

$pdf->Output();
?>
