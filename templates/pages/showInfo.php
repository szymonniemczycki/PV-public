<section>
    <div class="infoMessage">
        <?php
        //possible messagse to display
        switch ($msg) {
            case 'noDataInDB':
                echo 'No data in the database!';
                break;
            case 'wrongData':
                echo 'Incorrect date!';
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
            case 'wrongHash':
                echo 'Incorrect login data!';
                break;
            case 'noHash':
                echo 'Enter login data!';
                break;
            case 'appProblem':
                echo 'Any problem with App';
                break;
            default:
                echo 'Unknown error...';
                break;
        }
        ?>
    </div>
</section>