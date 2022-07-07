<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Word to PDF</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
    <style>
        .gradient-custom {
    /* fallback for old browsers */
    background: #6a11cb;
    
    /* Chrome 10-25, Safari 5.1-6 */
    background: -webkit-linear-gradient(to right, rgba(106, 17, 203, 1), rgba(37, 117, 252, 1));
    
    /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */
    background: linear-gradient(to right, rgba(106, 17, 203, 1), rgba(37, 117, 252, 1))
    }
    </style>
</head>
<body>
<section class="vh-100 gradient-custom">
  <div class="container py-5 h-100">
    <div class="row d-flex justify-content-center align-items-center h-100">
      <div class="col-12 col-md-8 col-lg-6 col-xl-5">
        <div class="card bg-dark text-white" style="border-radius: 1rem;">
          <div class="card-body p-5 text-center">

            <div class="mb-md-5 mt-md-4 pb-5">

              <h2 class="fw-bold mb-2 text-uppercase mb-5">DOCX to PDF Converter</h2>
              <form action="#" method="post" enctype="multipart/form-data">
              <div class="form-outline form-white mb-4">
                <input type="file" id="typeEmailX" name="file" class="form-control form-control-lg" />
                <label class="form-label" for="typeEmailX">Select Docx File</label>
              </div>

              <button class="btn btn-danger btn-outline-light btn-lg px-5" name="upload" type="submit">Convert</button>
              </form>
            </div>

          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.5/dist/umd/popper.min.js" integrity="sha384-Xe+8cL9oJa6tN/veChSP7q+mnSPaj5Bcu9mPX5F5xIGE0DVittaqT5lorf0EI7Vk" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.min.js" integrity="sha384-kjU+l4N0Yf4ZOJErLsIcvOU2qSb74wXpOhqTvwVx3OElZRweTnQ6d31fXEoRD1Jy" crossorigin="anonymous"></script>
</body>
</html>

<?php
 if(isset($_POST['upload'])){
    
    $filename = $_FILES['file']['name'];
    $tmpName = $_FILES['file']['tmp_name'];
    $folder = './';
    echo $filename ;

    if(!file_exists($_FILES['file']['tmp_name']) || !is_uploaded_file($_FILES['file']['tmp_name'])) {
        echo '<script>alert("Please Select Your File!")</script>';
    }else{
    move_uploaded_file($tmpName, $folder.$filename);
    
    function read_docx($filename){

        $striped_content = '';
        $content = '';
    
        if(!$filename || !file_exists($filename)) return false;
    
        $zip = zip_open($filename);
        if (!$zip || is_numeric($zip)) return false;
    
        while ($zip_entry = zip_read($zip)) {
    

            if (zip_entry_open($zip, $zip_entry) == FALSE) continue;
    
            if (zip_entry_name($zip_entry) != "word/document.xml") continue;
    
            $content .= zip_entry_read($zip_entry, zip_entry_filesize($zip_entry));
    
            zip_entry_close($zip_entry);
        }
        zip_close($zip);      
        $content = str_replace('</w:r></w:p></w:tc><w:tc>', " ", $content);
        $content = str_replace('</w:r></w:p>', "\r\n", $content);
        $striped_content = strip_tags($content);
        return $striped_content;
      }
     $content = read_docx($filename);

    ob_end_clean();
    require('./fpdf.php');
  
// Instantiate and use the FPDF class 
$pdf = new FPDF();
  
//Add a new page
$pdf->AddPage();

// $pdf->Image('images/pdf-header.jpg',0,0);
  
// Set the font for the text
$pdf->SetFont('Arial', '', 11);
  
// Prints a cell with given text 
$pdf->Multicell(0,10,$content);
  
// return the generated output
$pdf->output();

// header("Content-Length: " . filesize($pdf->Output()));
}
  }
?>

