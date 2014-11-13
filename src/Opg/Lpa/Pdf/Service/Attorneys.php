<?php
namespace Opg\Lpa\Pdf\Service;

use Opg\Lpa\Pdf\Service\Form;
use Opg\Lpa\DataModel\Lpa\Document\Attorneys;

class Attorneys implements ExtractorInterface
{

    /**
     *
     * @var PdfFormData object
     */
    private $pdfFormData;

    public function __construct(PdfFormData $pdfFormData)
    {
        $this->pdfFormData = $pdfFormData;
    }

    public function extract(Form $form)
    {
        if ($form instanceof Lp1) {
            foreach ($this->pdfFormData->lpa->document->primaryAttorneys as $attorney) {
                if($attorney instanceof Attorneys\Human) {
                    $this->pdfFormData->fieldValues = array(
                        'attorney-1-title' => $attorney->title,
                        'attorney-1-first-names' => $attorney->name->title
                    );
                }
                elseif($attorney instanceof Attorneys\TrustCorporation) {
                    
                }
            }
        } else 
            if ($form instanceof Lp3) {}
    } // function
}