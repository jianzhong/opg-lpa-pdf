<?php
namespace Opg\Lpa\Pdf\Service\Forms;

use Opg\Lpa\DataModel\Lpa\Lpa;
use Opg\Lpa\Pdf\Config\Config;
use Opg\Lpa\Pdf\Logger\Logger;
use Opg\Lpa\Pdf\Service\PdftkInstance;

class Cs4 extends AbstractForm
{
    private $companyNumber;
    
    public function __construct(Lpa $lpa, $companyNumber)
    {
        parent::__construct($lpa);
        $this->companyNumber = $companyNumber;
    }
    
    public function generate()
    {
        Logger::getInstance()->info(
            'Generating Cs4',
            [
                'lpaId' => $this->lpa->id
            ]
        );
        
        $filePath = $this->registerTempFile('CS4');
        
        $cs2 = PdftkInstance::getInstance($this->pdfTemplatePath.'/LPC_Continuation_Sheet_4.pdf');
        
        $cs2->fillForm(
            array(
                    'cs4-trust-corporation-company-registration-number' => $this->companyNumber,
                    'cs4-footer-right'    => Config::getInstance()['footer']['cs4'],
            ))
        ->flatten()
        ->saveAs($filePath);
        
        return $this->interFileStack;
    } // function generate()
    
    public function __destruct()
    {
        
    }
} // class Cs4