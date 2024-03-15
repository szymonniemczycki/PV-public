<section>
    <div class="errorMessage">
        <?php
            switch ($viewParams['listPrices']['error']) {
                case 'noDataInDB':
                    echo 'No data in the database!';
                    break;
                case 'wrongData':
                    echo 'Wrong data!';
                    break;
                case 'emptyform':
                    echo 'No data in the form!';
                    break;
                case 'dateToShort':
                    echo 'Incorrect date format!';
                    break;
                case 'dataExist':
                    echo 'Data already exists in the database!';
                    break;
                case 'dataimportedFromCsv':
                    echo 'Data inported from CSV';
                    break;
                case 'needToImport':
                    echo 'Need to import';
                    break;
                case 'imported':
                    echo 'Data imported from pse.pl';
                    break;
                default:
                    echo 'Unknown error...';
                    break;
            }
        ?>
    </div>
</section>