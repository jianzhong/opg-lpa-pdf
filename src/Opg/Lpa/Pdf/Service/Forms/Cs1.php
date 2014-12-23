<?php
namespace Opg\Lpa\Pdf\Service\Forms;

use Opg\Lpa\DataModel\Lpa\Lpa;
use Opg\Lpa\DataModel\Lpa\Elements\Dob;
use Opg\Lpa\DataModel\Lpa\Elements\EmailAddress;

class Cs1 extends AbstractForm
{
    private $roleType;
    
    static $SETTINGS = array(
        'max-slots-on-standard-form' => array(
            'primaryAttorney'       => Lp1::MAX_ATTORNEYS_ON_STANDARD_FORM,
            'replacementAttorney'   => Lp1::MAX_REPLACEMENT_ATTORNEYS_ON_STANDARD_FORM,
            'peopleToNotify'        => Lp1::MAX_PEOPLE_TO_NOTIFY_ON_STANDARD_FORM
        ),
        'max-slots-on-cs1-form'     => 2,
        'roleGroup'                 => array(
            'primaryAttorney'       => 'primaryAttorneys',
            'replacementAttorney'   => 'replacementAttorneys',
            'peopleToNotify'        => 'peopleToNotify'
        )
    );
    
    public function __construct(Lpa $lpa, $roleType)
    {
        parent::__construct($lpa);
        $this->roleType = $roleType;
    }
    
    /**
     * Calculate how many CS1 pages need to be generated to fit all content in for a field.
     * 
     * @param string $this->roleType - primaryAttorneys, replacementAttorneys or peopleToNotify.
     * @return array - CS1 pdf paths
     */
    public function generate()
    {
        /**
         * Number of same roles filled on standard form.
         */ 
        $baseIndexForThisRole = self::$SETTINGS['max-slots-on-standard-form'][$this->roleType];
        
        /**
         * Number of persons can be put on to CS1
         */
        $maxNumPersonOnCS1 = self::$SETTINGS['max-slots-on-cs1-form'];
        
        $total = count($this->lpa->document->{self::$SETTINGS['roleGroup'][$this->roleType]});
        
        $totalAdditionalPersonsOfSameRole = $total - $baseIndexForThisRole;
        
        $noOfAdditionalPages = ceil($totalAdditionalPersonsOfSameRole/2);
        
        $roleGroup = self::$SETTINGS['roleGroup'][$this->roleType];
        
        $totalMappedAdditionalPeople = 0;
        for($i=0; $i<$noOfAdditionalPages; $i++) {
            
            $filePath = $this->registerTempFile('CS1');
            
            $cs1 = PdfProcessor::getPdftkInstance($this->basePdfTemplatePath."/LPC_Continuation_Sheet_1.pdf");
            
            $formData = array();
            for($j=0; $j<$maxNumPersonOnCS1; $j++) {
                
                $roleIndex = $i*$maxNumPersonOnCS1 + $j + $baseIndexForThisRole;
                
                $formData['cs1-'.$j.'-is-'.$this->roleType] = self::CHECK_BOX_ON;
                if(array_key_exists('lpa-document-'.$roleGroup.'-'.$roleIndex.'-name-first', $this->flattenLpa)) {
                    $formData['cs1-'.$j.'-name-title']       = $this->flattenLpa['lpa-document-'.$roleGroup.'-'.$roleIndex.'-name-title'];
                    $formData['cs1-'.$j.'-name-first']       = $this->flattenLpa['lpa-document-'.$roleGroup.'-'.$roleIndex.'-name-first'];
                    $formData['cs1-'.$j.'-name-last']        = $this->flattenLpa['lpa-document-'.$roleGroup.'-'.$roleIndex.'-name-last'];
                }
                else {
                    $formData['cs1-'.$j.'-name-last']        = $this->flattenLpa['lpa-document-'.$roleGroup.'-'.$roleIndex.'-name'];
                }
                
                $formData['cs1-'.$j.'-address-address1'] = $this->flattenLpa['lpa-document-'.$roleGroup.'-'.$roleIndex.'-address-address1'];
                $formData['cs1-'.$j.'-address-address2'] = $this->flattenLpa['lpa-document-'.$roleGroup.'-'.$roleIndex.'-address-address2'];
                $formData['cs1-'.$j.'-address-address3'] = $this->flattenLpa['lpa-document-'.$roleGroup.'-'.$roleIndex.'-address-address3'];
                $formData['cs1-'.$j.'-address-postode']  = $this->flattenLpa['lpa-document-'.$roleGroup.'-'.$roleIndex.'-address-postcode'];
                
                if(property_exists($this->lpa->document->{$roleGroup}[$roleIndex], 'dob') && property_exists($this->lpa->document->{$roleGroup}[$roleIndex]->dob, 'date')) {
                    $formData['cs1-'.$j.'-dob-date-day']   = $this->lpa->document->{$roleGroup}[$roleIndex]->dob->date->format('d');
                    $formData['cs1-'.$j.'-dob-date-month'] = $this->lpa->document->{$roleGroup}[$roleIndex]->dob->date->format('m');
                    $formData['cs1-'.$j.'-dob-date-year']  = $this->lpa->document->{$roleGroup}[$roleIndex]->dob->date->format('Y');
                }
                
                if(property_exists($this->lpa->document->{$roleGroup}[$roleIndex], 'email') &&  ($this->lpa->document->{$roleGroup}[$roleIndex]->email instanceof EmailAddress)) {
                    $formData['cs1-'.$j.'-email-address']  = $this->flattenLpa['lpa-document-'.$roleGroup.'-'.$roleIndex.'-email-address'];
                }
                
                if(++$totalMappedAdditionalPeople >= $totalAdditionalPersonsOfSameRole) {
                    break;
                }
                
            } // end for loop for 2 persons per page
            
            $formData['donor-full-name'] = $this->fullName($this->lpa->document->donor->name);
            
            $cs1->fillForm($formData)
                ->flatten()
                ->saveAs($filePath);
            
        } // loop each CS page
        
        
        // draw strokes if there's any blank slot in the last CS1 pdf
        if($totalAdditionalPersonsOfSameRole % self::$SETTINGS['max-slots-on-cs1-form']) {
            $this->stroke($filePath, array(array('cs1')));
        }
        
        return $this->intermediateFilePaths;
    } // function generate()
    
    public function __destruct()
    {
        
    }
} // class Cs1