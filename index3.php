
<?php
 echo "hey there";
 shell_exec('start /wait soffice --headless --convert-to pdf --outdir  "."  "./MATHS test paper.docx"');
?>