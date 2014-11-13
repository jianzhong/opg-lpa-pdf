<?php
namespace Opg\Lpa\Pdf\Service;

use Opg\Lpa\DataModel\Lpa\Lpa;
use mikehaertl\pdftk\pdf as Pdf;

class Lp3 extends Form
{

    public function __construct(Lpa $lpa)
    {
        parent::__construct($lpa);
        $this->pdf = new Pdf("assets/v2/LP3.pdf");
    }

    protected function extract()
    {
        (new Donor($this->pdfFormData))->extract($this);
        (new Attorneys($this->pdfFormData))->extract($this);
    }
}