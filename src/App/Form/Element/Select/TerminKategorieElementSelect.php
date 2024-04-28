<?php

declare(strict_types=1);

namespace App\Form\Element\Select;

use Laminas\Db\Adapter\Adapter as DbAdapter;
use Laminas\Form\Element;

class TerminKategorieElementSelect extends Element\Select
{
    protected DbAdapter $db;

    public function setDb(DbAdapter $db)
    {
        $this->db = $db;
    }

    public function init()
    {
        // if ($this->db) {
        //     $this->setValueOptionsFromDb();
        // }
    }

    public function setValueOptionsFromDb()
    {
        $sql = '
                SELECT DISTINCT 
                    termin_kategorie
                FROM
                    tajo1_termin
                ORDER BY
                    termin_kategorie';

        $statement = $this->db->query($sql);
        $result    = $statement->execute();
        $return    = [];

        foreach ($result as $r) {
            $return[$r['termin_kategorie']] = [
                'label' => $r['termin_kategorie'],
                'value' => $r['termin_kategorie'],
                //'attributes' => ['data-kalender' => 'calendar-1'],
            ];
        }

        $this->setValueOptions($return);
    }
}
