<section>
    <div class="errorMessage">
        <?php
            switch ($viewParams['listPrices']['error']) {
                case 'noDataInDB':
                    echo 'Brak danych w Bazie Danych!';
                    break;
                case 'wrongData':
                    echo 'Niepoprawna data!';
                    break;
                case 'emptyform':
                    echo 'Brak danych w formularzu!';
                    break;
                case 'dateToShort':
                    echo 'Niepoprawny format daty';
                    break;
                case 'dataExist':
                    echo 'Dane istnieją juz bazie!';
                    break;
                case 'dataimportedFromCsv':
                    echo 'Dane zaimportowane z CSV';
                    break;
                case 'needToImport':
                    echo 'Trzeba importować';
                    break;
                case 'imported':
                    echo 'Dane poprawnie zaimportowane z pse.pl';
                    break;
                default:
                    echo 'Nieznany błąd...';
                    break;
                }
        ?>
    </div>
</section>