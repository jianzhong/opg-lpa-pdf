<?php
namespace Opg\Lpa\Pdf\Service;

use Opg\Lpa\DataModel\Lpa\Lpa;

class PdfFormData
{

    /**
     *
     * @var LPA model object
     */
    public $lpa;
    
    /**
     * 
     * @var Form fields and values
     */
    public $fieldValues = array();
    
    public function __construct(Lpa $lpa)
    {
        $this->lpa = $lpa;
    }
} // class