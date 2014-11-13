<?php
namespace Opg\Lpa\Pdf\Service;

use Opg\Lpa\Pdf\Service\Form;

interface ExtractorInterface {

    public function extract( Form $form );
    
} // interface
