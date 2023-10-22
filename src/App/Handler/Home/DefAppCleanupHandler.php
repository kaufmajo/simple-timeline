<?php

// phpcs:disable WebimpressCodingStandard.NamingConventions.ValidVariableName


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

    /**
     * @throws Exception
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        // init
        $databaseAdapter      = $this->getDatabaseAdapter();
        $myInitConfig         = $this->getMyInitConfig();
        $cleanup_anzahl_media = 0;
        $cleanup_anzahl_cache = 0;
        $cleanup_anzahl_temp  = 0;
        $cleanup_files_cache  = [];
        $cleanup_files_media  = [];
        $cleanup_files_temp   = [];

        // view
        $viewData['cleanup_tage_termine']        = $myInitConfig['cleanup_tage_termine'];
        $viewData['cleanup_tage_history']        = $myInitConfig['cleanup_tage_history'];
        $viewData['cleanup_tage_termin_history'] = $myInitConfig['cleanup_tage_termin_history'];

        // -------------------------------------------------------------------------------------------------------------
        // Cleanup Termine

        // sql: Hole abgelaufene Termine
        $sql1 = '-- noinspection SqlDialectInspectionForFile
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
        $result1    = $statement1->execute();

        // view
        $viewData['cleanup_anzahl_termin'] = $result1->getAffectedRows();

        // -------------------------------------------------------------------------------------------------------------
        // Cleanup History

        // sql: Lösche History-Einträge
        $sql5 = '-- noinspection SqlDialectInspectionForFile
                    DELETE FROM 
                        tajo1_history 
                    WHERE 
                        history_timestamp < DATE_SUB(NOW(), INTERVAL ' . $databaseAdapter->getPlatform()->quoteValue($myInitConfig['cleanup_tage_history']) . ' DAY)';

        $statement5 = $databaseAdapter->query($sql5);
        $result5    = $statement5->execute();

        // view
        $viewData['cleanup_anzahl_history'] = $result5->getAffectedRows();

        // -------------------------------------------------------------------------------------------------------------
        //  Cleanup Datum

        // sql: Lösche Datum-Einträge
        $sql6 = '-- noinspection SqlDialectInspectionForFile
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
        $result6    = $statement6->execute();

        // view
        $viewData['cleanup_anzahl_datum'] = $result6->getAffectedRows();

        // -------------------------------------------------------------------------------------------------------------
        //  Cleanup Termin-History

        // sql: Lösche Termin-History-Einträge
        $sql7 = '-- noinspection SqlDialectInspectionForFile
                    DELETE FROM 
                        tajo1_terminHistory 
                    WHERE 
                        terminHistory_timestamp < DATE_SUB(NOW(), INTERVAL ' . $databaseAdapter->getPlatform()->quoteValue($myInitConfig['cleanup_tage_termin_history']) . ' DAY)';

        $statement7 = $databaseAdapter->query($sql7);
        $result7    = $statement7->execute();

        // view
        $viewData['cleanup_anzahl_termin_history'] = $result7->getAffectedRows();

        // -------------------------------------------------------------------------------------------------------------
        //  Cleanup Media from Filesystem

        $iteratorIterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator(getcwd() . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'media' . DIRECTORY_SEPARATOR)
        );

        while ($iteratorIterator->valid()) {
            if (false === $iteratorIterator->isDot() && $iteratorIterator->isFile()) {
                $filename = $iteratorIterator->getFilename();

                $isMediaInDatabase = false;

                if (str_starts_with($iteratorIterator->getFilename(), 'media_')) {
                    $matches = [];

                    preg_match('/[0-9]+/', $filename, $matches);

                    $sql6 = '-- noinspection SqlDialectInspectionForFile
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

                if (! str_starts_with($iteratorIterator->getFilename(), 'media_') || ! $isMediaInDatabase) {
                    $cleanup_anzahl_media++;

                    $cleanup_files_media[] = [
                        $iteratorIterator->getFilename(),
                        filemtime($iteratorIterator->getPathname()),
                    ];

                    unlink($iteratorIterator->getPathname());
                }
            }

            $iteratorIterator->next();
        }

        // view
        $viewData['cleanup_anzahl_media'] = $cleanup_anzahl_media;
        $viewData['cleanup_files_media']  = $cleanup_files_media;

        // -------------------------------------------------------------------------------------------------------------
        //  Cleanup Cache from Filesystem

        $iteratorIterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator(getcwd() . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR . 'server' . DIRECTORY_SEPARATOR)
        );

        while ($iteratorIterator->valid()) {
            if (false === $iteratorIterator->isDot() && $iteratorIterator->isFile()) {
                $cleanup_anzahl_cache++;

                $cleanup_files_cache[] = [
                    $iteratorIterator->getFilename(),
                    filemtime($iteratorIterator->getPathname()),
                ];

                unlink($iteratorIterator->getPathname());
            }

            $iteratorIterator->next();
        }

        // view
        $viewData['cleanup_anzahl_cache'] = $cleanup_anzahl_cache;
        $viewData['cleanup_files_cache']  = $cleanup_files_cache;

        // -------------------------------------------------------------------------------------------------------------
        // Cleanup Temp-Upload Directory

        $iteratorIterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator(getcwd() . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'temp' . DIRECTORY_SEPARATOR)
        );

        while ($iteratorIterator->valid()) {
            if (false === $iteratorIterator->isDot() && $iteratorIterator->isFile()) {
                $cleanup_anzahl_temp++;

                $cleanup_files_temp[] = [
                    $iteratorIterator->getFilename(),
                    filemtime($iteratorIterator->getPathname()),
                ];

                unlink($iteratorIterator->getPathname());
            }

            $iteratorIterator->next();
        }

        // view
        $viewData['cleanup_anzahl_temp'] = $cleanup_anzahl_temp;
        $viewData['cleanup_files_temp']  = $cleanup_files_temp;

        // -------------------------------------------------------------------------------------------------------------
        // return

        return new HtmlResponse($this->templateRenderer->render('app::def/home/def-app-cleanup', $viewData));
    }
}
