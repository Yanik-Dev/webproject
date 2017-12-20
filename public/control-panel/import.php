<?php
$title = "Import";
include '../../includes/admin-layout.php';

require_once '../../lib/PHPExcel/PHPExcel.php';
require_once '../../lib/PHPExcel/PHPExcel/IOFactory.php';

//declarations
$table = $categories = $errors = $offerings = [];
$rowCount = $failedCount = $successCount  = 0;
$isSafe = true;
$business =new Business();
$category=new OfferingCategory();
$offering=new Offering();

$path= $_FILES['file']["tmp_name"]??'';
$businessId = $_POST["businessId"]??'';

if($path=='' || !is_numeric($businessId)){
    header('Location: ./offerings.php');
}

if(!Validator::isFileTypeMatch(['xls', 'csv', 'xlsx' ], $_FILES['file']["type"])){
    $errors[] = "Only .xls, .xlsx and .csv files are allowed";
}


if(count($errors) == 0){

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

    $offeringsList = OfferingService::findAll($offering);
    $categoriesList = OfferingCategoryService::findAll();

    foreach($offeringsList as $o){
        $offerings[] = strtolower($o["name"]);
    }
    foreach($categoriesList as $category){
        $categories[] = $category["category"];
    }

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
            $nrColumns          = ord($highestColumn) - 64;
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
}
?>

<div class="ui centered padded grid">
    <div class="sixteen column" >
        <a href="./offerings.php"  id="top" class="ui labeled icon button">
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

                <a class="ui primary button"  href="../assets/blookup_template.xlsx" download>
                    <i class="download icon"></i>
                    Download Template
                </a>
            </div>
        
       <?php  exit; else: ?>
            <?php if(count($table) > 12):?>
                <a href="#stats" class="ui primary labeled icon button">
                    <i class="bar chart icon"></i>
                    Results
                </a>
            <?php endif; ?>
        <?php endif; ?>
        
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
                <?php 
                   for ($row = 2; $row <= $rowCount; ++ $row):
                    $isSafe = true;
                    $isEmpty = false;
                    ?>
                    <tr>
                    <?php for ($col = 0; $col < 5; ++ $col):?>
                        <?php 
                        $exist=false;
                        if($col != 3):
                            if($col==0):
                                if(trim($table[$row][$col]) ==''):
                                    $isSafe = false;
                                    $isEmpty = true;
                                    break;
                                elseif(in_array(strtolower($table[$row][$col]), $offerings)):
                                    $exist=true;
                                    $isSafe = false;
                                else:
                                    $offerings[] = strtolower($table[$row][$col]);
                                endif;
                            endif;
                            if($exist):
                                echo '<td class="error" data-tooltip="Item \''.$table[$row][$col].'\' already exist" data-position="top left"> <del> '.$table[$row][$col].'</del></td>';
                            elseif($col == 4 && !in_array($table[$row][$col], $categories)):
                                    $isSafe = false;
                                    echo '<td class="error" data-tooltip="\''.$table[$row][$col].'\' is not a valid category" data-position="top left">  <del> '.$table[$row][$col].'</del></td>';
                            elseif($col == 2 && !is_numeric($table[$row][$col])):
                                $isSafe = false;
                                echo '<td class="error" data-tooltip="Expecting a numeric value" data-position="top left">  <del> '.$table[$row][$col].'</del></td>';
                            else:
                                echo '<td class="positive">'.(($col == 0)?'<i class="icon checkmark"></i>':'').' '.$table[$row][$col].'</td>';
                            endif;
                        endif; 
                        ?>
                    <?php endfor; ?>

                    <?php 
                        if($isSafe){
                            
                            $newOffering = new Offering();
                            $business =new Business();
                            $category=new OfferingCategory();

                        
                            foreach($categoriesList as $c){
                                if($c["category"] == $table[$row][4] ){
                                    //set Category Object
                                    $category->setId($c["id"]);
                                    break;
                                }
                            }
                            //set Business Object
                            $business->setId($businessId);

                            //set Offering Object
                            $newOffering->setName($table[$row][0]);
                            $newOffering->setCost($table[$row][2]);
                            $newOffering->setDescription($table[$row][1]);
                            $newOffering->setBusiness($business);
                            $newOffering->setCategory($category);
                            $id = OfferingService::insert($newOffering);
                            if($id){
                                $successCount++;
                            }
                        }elseif(!$isEmpty){
                            $failedCount++;
                        }
                    ?>
                    </tr>
            <?php endfor; ?>
            </tbody>
        </table>
        <div class="ui padded segment" id="stats">
          <p class="green"><i class="icon checkmark"></i> Successful Inserts: <?= $successCount ?></p>
          <p class="red"><i class="icon remove"></i> Unsuccessful: <?= $failedCount ?></p>
         <?php if(($failedCount + $successCount) > 12):?>
            <a href="./offerings.php"  id="top" class="ui labeled icon button">
                <i class="left chevron icon"></i>
                Back
            </a>
            <a href="#top" class="ui labeled icon button">
                <i class="chevron up icon"></i>
                Back To Top
            </a>
         <?php endif;?>
        </div>
   </div>
</div>