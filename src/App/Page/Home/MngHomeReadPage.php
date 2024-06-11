<?php

declare(strict_types=1);

namespace App\Page\Home;

use App\Page\AbstractBasePage;
use App\Traits\Aware\DbRunnerAwareTrait;
use Laminas\Diactoros\Response\HtmlResponse;
use Laminas\Diactoros\Response\TextResponse;
use Laminas\Validator;
use Psr\Http\Message\ServerRequestInterface;

use function array_merge;
use function count;
use function gmdate;
use function in_array;
use function is_numeric;
use function parse_url;
use function phpinfo;
use function preg_split;
use function time;

use const INFO_GENERAL;
use const PREG_SPLIT_NO_EMPTY;

class MngHomeReadPage extends AbstractBasePage
{
    use DbRunnerAwareTrait;

    public function indexAction(ServerRequestInterface $request): HtmlResponse
    {
        $this->getUrlpoolService()->save();

        // view
        $viewData = [
            'myInitConfig' => $this->getMyInitConfig(),
        ];

        return new HtmlResponse(
            $this->templateRenderer->render('app::mng/home/mng-home-read-index', $viewData)
        );
    }

    public function phpinfoAction(ServerRequestInterface $request): never
    {
        //phpinfo(INFO_ALL);
        phpinfo(INFO_GENERAL);

        exit;
    }

    public function initconfigAction(ServerRequestInterface $request): HtmlResponse
    {
        // view
        $viewData = [
            'myInitConfig' => $this->getMyInitConfig(),
        ];

        return new HtmlResponse(
            $this->templateRenderer->render('app::mng/home/mng-home-read-initconfig', $viewData)
        );
    }

    public function tabellenAction(ServerRequestInterface $request): HtmlResponse
    {
        // param
        $q1 = (int) ($request->getQueryParams()['t'] ?? 1);

        // init
        $dbRunner = $this->getDbRunner();

        $tabellen = [
            1 => ['name' => 'tajo1_termin', 'order' => 'termin_id', 'header' => 'Termin', 'parent' => [0]],
            2 => ['name' => 'tajo1_media', 'order' => 'media_id', 'header' => 'Media', 'parent' => [0]],
            3 => ['name' => 'tajo1_user', 'order' => 'user_id', 'header' => 'User', 'parent' => [0]],
            4 => ['name' => 'tajo1_datum', 'order' => 'datum_id', 'header' => 'Datum', 'parent' => [0]],
            5 => ['name' => 'tajo1_history', 'order' => 'history_id', 'header' => 'History', 'parent' => [0]],
            6 => ['name' => 'tajo1_terminHistory', 'order' => 'terminHistory_id', 'header' => 'TerminHistory', 'parent' => [1]],
            7 => ['name' => 'tajo1_lnk_datum_termin', 'order' => 'lnk_id', 'header' => 'LnkDatumTermin', 'parent' => [1, 4]],
        ];

        $tabelle = $tabellen[$q1];

        $sqlData = "
            SELECT * FROM " . $dbRunner->getDb()->platform->quoteIdentifier($tabelle['name'])
            . " ORDER BY " . $dbRunner->getDb()->platform->quoteIdentifier($tabelle['order']) . " DESC";

        $data = $dbRunner->executeSelect($sqlData);

        // view
        $viewData = [
            'tabellen' => $tabellen,
            'data'     => $data,
        ];

        return new HtmlResponse(
            $this->templateRenderer->render('app::mng/home/mng-home-read-tabellen', $viewData)
        );
    }

    public function statsAction(ServerRequestInterface $request): HtmlResponse|TextResponse
    {
        // param
        $groupByIp = (int) $request->getQueryParams()['gip'];
        $excludeIp = (string) ($request->getQueryParams()['exip'] ?? '');

        if ($excludeIp && ! (new Validator\Ip())->isValid($excludeIp)) {
            return new TextResponse('Filter IP is invalid.');
        }

        // init
        $stats = []; // will contain: [$url => ['url', 'anzahl', ['ip']]]"

        $data = $this->getDbRunner()->executeSelect("SELECT * FROM `tajo1_history` ORDER BY `tajo1_history`.`history_id` DESC");

        // determine stats
        foreach ($data as $d) {
            if ($excludeIp && $d['history_ip'] === $excludeIp) {
                continue;
            }

            $url = parse_url($d['history_url']);

            if (isset($url['path'])) {
                $parts = preg_split('/\//', $url['path'], -1, PREG_SPLIT_NO_EMPTY);

                if (count($parts) >= 2) {
                    $url['path'] = '/' . $parts[0] . (! is_numeric($parts[1]) ? '/' . $parts[1] : '');
                }
            }

            $index  = isset($url['scheme']) ? $url['scheme'] . '://' : '';
            $index .= isset($url['host']) ? $url['host'] . ':' : '';
            $index .= $url['port'] ?? '';
            $index .= $url['path'] ?? '';

            if (! isset($stats[$index])) {
                $stats[$index] = ['url' => $index, 'anzahl' => 1, 'ip' => [$d['history_ip']]];
            } elseif (0 === $groupByIp || ! in_array($d['history_ip'], $stats[$index]['ip'])) {
                $stats[$index]['anzahl'] = ++$stats[$index]['anzahl'];
                $stats[$index]['ip'][]   = $d['history_ip'];
            }
        }

        return new HtmlResponse(
            $this->templateRenderer->render('app::mng/home/mng-home-read-stats', [
                'stats'        => $stats,
                'myInitConfig' => $this->getMyInitConfig(),
            ])
        );
    }

    public function testAction(ServerRequestInterface $request): TextResponse
    {
        $array1 = [
            'Cache-Control' => [0 => 'private, must-revalidate, max-age=900'],
            'Expires'       => [0 => 'Fri, 31 Mar 2023 07:11:01 GMT'],
            'Last-Modified' => [0 => 'Fri, 31 Mar 2023 06:56:01 GMT'],
            'content-type'  => [0 => 'text/html; charset=utf-8'],
        ];

        $array2 = [
            'Cache-Control' => 'private, must-revalidate, max-age=30',
            'Expires'       => gmdate('D, d M Y H:i:s', time() + 30) . ' GMT',
            'Last-Modified' => gmdate('D, d M Y H:i:s', time()) . ' GMT',
        ];

        $data = array_merge($array1, $array2);

        return new TextResponse($this->templateRenderer->render('app::mng/home/mng-home-read-test', [
            'data' => $data,
        ]));
    }
}
