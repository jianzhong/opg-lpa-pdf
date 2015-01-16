<?php
namespace Opg\Lpa\Pdf\Service\Forms;

use Opg\Lpa\DataModel\Lpa\Lpa;

class Cs3 extends AbstractForm
{
    
    public function __construct(Lpa $lpa)
    {
        parent::__construct($lpa);
    }
    
    public function generate()
    {
        $filePath = $this->registerTempFile('CS3');
    
        $cs3 = PdfProcessor::getPdftkInstance($this->pdfTemplatePath."/LPC_Continuation_Sheet_3.pdf");
    
        $cs3->fillForm(array(
                'donor-full-name' => $this->fullName($this->lpa->document->donor->name)
        ))
        ->flatten()
        ->saveAs($filePath);
        
        return $this->interFileStack;
    } // function generate()
    
    public function __destruct()
    {
        
    }
} // class Cs3