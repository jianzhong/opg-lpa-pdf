<?php
namespace Opg\Lpa\Pdf\Service;

use Opg\Lpa\Pdf\Service\Form;

class Donor implements ExtractorInterface
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
            $this->pdfFormData->fieldValues = array(
                'donor-title' => $this->pdfFormData->lpa->donor->title,
                'donor-first-names' => $this->pdfFormData->lpa->donor->firstNames
            );
        } elseif ($form instanceof Lp3) {}
    } // function
} // class