<?php
$title = "Import";
include '../includes/header.php';
include '../includes/admin-layout.php';

require_once '../lib/PHPExcel/PHPExcel.php';
require_once '../lib/PHPExcel/PHPExcel/IOFactory.php';


$path= $_FILES['file']["tmp_name"]??'';
$businessId = $_POST["businessId"]??'';

if($path=='' || !is_numeric($businessId)){
    header('Location: ./offerings.php');
}

$table = [];
$rowCount = 0;

$business =new Business();
$category=new OfferingCategory();
$offering=new Offering();


//set Category Object
$category->setId('');
$category->setType(new OfferingType());

//set Business Object
$business->setId($businessId);
$business->setOwner(new User());

//set Offering Object
$offering->setName('');
$offering->setCost('');
$offering->setDescription('');
$offering->setBusiness($business);
$offering->setCategory($category);

$offeringsList = OfferingService::findAll($offering, ['start'=>0,'limit'=>0]);
$categoriesList = OfferingCategoryService::findAll();
$categories = $offerings = [];

foreach($offeringsList as $offering){
    $offerings[] = $offering["name"];
}
foreach($categoriesList as $category){
    $categories[] = $category["category"];
}

$errors = [];
try{
    $objPHPExcel = PHPExcel_IOFactory::load($path);
}catch(Exception $e){
    $errors[] = $e->getMessage();
    $objPHPExcel = null;
}
if(isset($objPHPExcel)){
    foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
        $worksheetTitle     = $worksheet->getTitle();
        $highestRow         = $worksheet->getHighestRow();
        $rowCount           = $highestRow;
        $highestColumn      = $worksheet->getHighestColumn(); 
        $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
        $nrColumns = ord($highestColumn) - 64;
        if($worksheetTitle != "Items"){
            $errors[] = "Items worksheet not found";
            break;
        }else{
            for ($row = 1; $row <= $highestRow; ++ $row) {
                for ($col = 0; $col < $highestColumnIndex; ++ $col) {
                    $cell = $worksheet->getCellByColumnAndRow($col, $row);
                    $val = $cell->getValue();
                    $table[$row][$col] = $val;
                }
            }
            break;
        }
    }
}
?>

<div class="ui centered padded grid">
    <div class="sixteen column" >
        <a href="./offerings.php" class="ui labeled icon button">
            <i class="left chevron icon"></i>
            Back
        </a>
        <?php if(count($errors) > 0): ?>
           <div class="ui negative message">
                <i class="close icon"></i>
                <div class="header">
                    Oops we have encountered an error
                </div>
                <p><?=$errors[0];?></p>

                <button class="ui primary button">
                    <i class="download icon"></i>
                    Download Template
                </button>
            </div>
        <?php  exit; endif; ?>
        
        <table class="ui celled table">
            <thead>
                <tr>
                <th>Name</th>
                <th>Description</th>
                <th>Cost</th>
                <th>Category</th>
                </tr>
            </thead>

            <tbody>
                <?php for ($row = 2; $row <= $rowCount; ++ $row):?>
                    <tr>
                    <?php for ($col = 0; $col < 5; ++ $col):?>
                        <?php 
                        $exist = 'positive';
                        $invalidService = false;
                        if($col != 3):
                            if($col==0):
                                if(in_array($table[$row][$col], $offerings)):
                                    $exist="error";
                                elseif(in_array($table[$row][$col], $categories)):
                                    $invalidService = true;
                                else:
                                    //perpare for insert;

                                endif;
                            endif;
                            if($exist == "error")
                                
                                echo '<td class="'.$exist.'"> <del> '.$table[$row][$col].'</del></td>';
                            else if($invalidService):
                                echo '<td class="error">  <del> '.$table[$row][$col].'</del></td>';
                            else:
                                echo '<td class="'.$exist.'">'.(($col == 0)?'<i class="icon checkmark"></i>':'').' '.$table[$row][$col].'</td>';
                            endif;
                        endif; 
                        ?>
                    <?php endfor; ?>
                    
                    </tr>
                <?php endfor; ?>
            </tbody>
        </table>
   </div>
</div>