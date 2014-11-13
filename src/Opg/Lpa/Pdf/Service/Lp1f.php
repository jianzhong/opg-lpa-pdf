<?php
namespace Opg\Lpa\Pdf\Service;

use Opg\Lpa\DataModel\Lpa\Lpa;
use mikehaertl\pdftk\pdf as Pdf;

class Lp1f extends Lp1
{

    public function __construct(Lpa $lpa)
    {
        parent::__construct($lpa);
        $this->pdf = new Pdf("assets/v2/LP1F.pdf");
    }
} // class