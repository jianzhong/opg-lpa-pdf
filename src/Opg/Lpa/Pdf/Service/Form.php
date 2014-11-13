<?php
namespace Opg\Lpa\Pdf\Service;

use Opg\Lpa\DataModel\Lpa\Lpa;

abstract class Form
{

    /**
     *
     * @var LPA model object
     */
    protected $lpa;

    /**
     *
     * @var PDFTK pdf object
     */
    protected $pdf;

    /**
     *
     * @var PdfFormData object
     */
    protected $pdfFormData;

    /**
     * Each pdf form object must implement extract method for extracting form
     * field data from Lpa object
     */
    abstract protected function extract();

    public function __construct(Lpa $lpa)
    {
        $this->pdfFormData = new PdfFormData($lpa);
    }

    public function populate()
    {
        // generate a file path with lpa id and timestamp;
        $generatedpPdfFilePath = 'output.pdf';
        
        $this->extract();
        
        $this->attachAdditionalPages();
        
        $this->pdf->fillForm($this->pdfFormData->fieldValues)
            ->needAppearances()
            ->saveAs($generatedpPdfFilePath);
        
        return $generatedpPdfFilePath;
    }

    public function render()
    {}

    private function attachAdditionalPages()
    {}
} // class