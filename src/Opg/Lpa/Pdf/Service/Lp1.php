<?php
namespace Opg\Lpa\Pdf\Service;

abstract class Lp1 extends Form
{

    protected function extract ()
    {
        (new Donor($this->pdfFormData))->populate($this);
        (new Attorneys($this->pdfFormData))->populate($this);
    }
} // class