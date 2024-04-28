<?php

declare(strict_types=1);

namespace App\Handler\Home;

use App\Handler\AbstractBaseHandler;
use App\Traits\Aware\DatabaseAdapterAwareTrait;
use Exception;
use Laminas\Diactoros\Response\HtmlResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

use function filemtime;
use function getcwd;
use function preg_match;
use function str_starts_with;
use function unlink;

use const DIRECTORY_SEPARATOR;

class DefAppCleanupHandler extends AbstractBaseHandler
{
    use DatabaseAdapterAwareTrait;

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        // init
        $databaseAdapter = $this->getDatabaseAdapter();
        $myInitConfig = $this->getMyInitConfig();

        // view
        $viewData['cleanup_tage_termine'] = $myInitConfig['cleanup_tage_termine'];
        $viewData['cleanup_tage_history'] = $myInitConfig['cleanup_tage_history'];
        $viewData['cleanup_tage_termin_history'] = $myInitConfig['cleanup_tage_termin_history'];

        // -------------------------------------------------------------------------------------------------------------
        // Cleanup Termine

        // sql: Hole abgelaufene Termine
        $sql1 = '
                DELETE FROM 
                    tajo1_termin
                WHERE
                (
                    termin_datum_ende IS NULL 
                    AND termin_datum_start < DATE_SUB(CURDATE(), INTERVAL ' . $databaseAdapter->platform->quoteValue($myInitConfig['cleanup_tage_termine']) . ' DAY)
                )
                OR
                (
                    termin_datum_start < DATE_SUB(CURDATE(), INTERVAL ' . $databaseAdapter->platform->quoteValue($myInitConfig['cleanup_tage_termine']) . ' DAY)
                    AND termin_datum_ende < DATE_SUB(CURDATE(), INTERVAL ' . $databaseAdapter->platform->quoteValue($myInitConfig['cleanup_tage_termine']) . ' DAY)
                )';

        $statement1 = $databaseAdapter->query($sql1);
        $result1 = $statement1->execute();

        // view
        $viewData['cleanup_anzahl_termin'] = $result1->getAffectedRows();

        // -------------------------------------------------------------------------------------------------------------
        // Cleanup History

        // sql: Lösche History-Einträge
        $sql5 = '
                    DELETE FROM 
                        tajo1_history 
                    WHERE 
                        history_timestamp < DATE_SUB(NOW(), INTERVAL ' . $databaseAdapter->getPlatform()->quoteValue($myInitConfig['cleanup_tage_history']) . ' DAY)';

        $statement5 = $databaseAdapter->query($sql5);
        $result5 = $statement5->execute();

        // view
        $viewData['cleanup_anzahl_history'] = $result5->getAffectedRows();

        // -------------------------------------------------------------------------------------------------------------
        //  Cleanup Datum

        // sql: Lösche Datum-Einträge
        $sql6 = '
                    DELETE 
                        tajo1_datum
                    FROM 
                        tajo1_datum
                    LEFT JOIN 
                        tajo1_lnk_datum_termin ON tajo1_lnk_datum_termin.datum_id = tajo1_datum.datum_id 
                    WHERE 
                        tajo1_datum.datum_datum < DATE_SUB(NOW(), INTERVAL ' . $databaseAdapter->getPlatform()->quoteValue($myInitConfig['cleanup_tage_termine']) . ' DAY)
                        AND tajo1_lnk_datum_termin.datum_id IS NULL';

        $statement6 = $databaseAdapter->query($sql6);
        $result6 = $statement6->execute();

        // view
        $viewData['cleanup_anzahl_datum'] = $result6->getAffectedRows();

        // -------------------------------------------------------------------------------------------------------------
        //  Cleanup Termin-History

        // sql: Lösche Termin-History-Einträge
        $sql7 = '
                    DELETE FROM 
                        tajo1_terminHistory 
                    WHERE 
                        terminHistory_timestamp < DATE_SUB(NOW(), INTERVAL ' . $databaseAdapter->getPlatform()->quoteValue($myInitConfig['cleanup_tage_termin_history']) . ' DAY)';

        $statement7 = $databaseAdapter->query($sql7);
        $result7 = $statement7->execute();

        // view
        $viewData['cleanup_anzahl_termin_history'] = $result7->getAffectedRows();

        // -------------------------------------------------------------------------------------------------------------
        //  Cleanup Media from Filesystem

        $result = $this->cleanupFiles(getcwd() . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'media' . DIRECTORY_SEPARATOR);

        $viewData['cleanup_anzahl_media'] = $result[0];
        $viewData['cleanup_files_media'] = $result[1];

        // -------------------------------------------------------------------------------------------------------------
        // Cleanup Temp-Upload Directory

        $result = $this->cleanupFiles(getcwd() . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'temp' . DIRECTORY_SEPARATOR);

        $viewData['cleanup_anzahl_temp'] = $result[0];
        $viewData['cleanup_files_temp'] = $result[1];

        // -------------------------------------------------------------------------------------------------------------
        // return

        return new HtmlResponse($this->templateRenderer->render('app::def/home/def-app-cleanup', $viewData));
    }

    protected function cleanupFiles(string $path)
    {
        $cleanup_anzahl = 0;
        $cleanup_files = [];

        /** @var RecursiveDirectoryIterator&RecursiveIteratorIterator $iteratorIterator */
        $iteratorIterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path));

        while ($iteratorIterator->valid()) {

            if (
                false === $iteratorIterator->isDot()
                && $iteratorIterator->isFile()
                && '.gitkeep' !== $iteratorIterator->getFilename()
                && '.gitignore' !== $iteratorIterator->getFilename()
                && !$this->isMediaInDatabase($iteratorIterator->getFilename())
            ) {
                $cleanup_anzahl++;

                $cleanup_files[] = [$iteratorIterator->getFilename(), filemtime($iteratorIterator->getPathname())];

                unlink($iteratorIterator->getPathname());
            }

            $iteratorIterator->next();
        }

        return [$cleanup_anzahl, $cleanup_files];
    }

    protected function isMediaInDatabase(string $filename): bool
    {
        // init
        $isMediaInDatabase = false;
        $databaseAdapter = $this->getDatabaseAdapter();

        // process
        if (str_starts_with($filename, 'media_')) {

            $matches = [];

            preg_match('/[0-9]+/', $filename, $matches);

            $sql6 = '
                SELECT 
                    COUNT(*) AS `anzahl`
                FROM
                    `tajo1_media`
                WHERE
                    `media_id` = ' . $databaseAdapter->getPlatform()->quoteValue($matches[0]);

            $statement6 = $databaseAdapter->query($sql6);

            $result6 = $statement6->execute();

            $isMediaInDatabase = (bool) $result6->current()['anzahl'];
        }

        return $isMediaInDatabase;
    }
}
