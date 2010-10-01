<?php

/**
 * JobeetJob form.
 *
 * @package    jobeet
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class JobeetJobForm extends BaseJobeetJobForm {

    protected function removeFields() {
        unset(
                $this['created_at'], $this['updated_at'],
                $this['expires_at'], $this['is_activated'],
                $this['token']
        );
    }

    public function configure() {

        $this->removeFields();

        $this->widgetSchema['type'] = new sfWidgetFormChoice(array(
                    'choices' => Doctrine_Core::getTable('JobeetJob')->getTypes(),
                    'expanded' => true,
                ));

        $this->widgetSchema['logo'] = new sfWidgetFormInputFile(array(
                    'label' => 'Company Logo',
                ));

        $this->widgetSchema->setLabels(array(
            'category_id' => 'Category',
            'is_public' => 'Public?',
            'how_to_apply' => 'How to apply?',
        ));

        $this->widgetSchema->setHelp('is_public', 'Wether the job can also be published on affiliate websites or not');

        $this->validatorSchema['logo'] = new sfValidatorFile(array(
                    'required' => false,
                    'path' => sfConfig::get('sf_upload_dir') . '/jobs',
                    'mime_types' => 'web_images',
                ));

        // Here we limit the choices for the type field by getting the default values from the JobeetJobTable types property
        $this->validatorSchema['type'] = new sfValidatorChoice(array(
                    'choices' => array_keys(Doctrine_Core::getTable('JobeetJob')->getTypes()),
                ));

        // Add email validator with sfValidatorAnd, so we don't override the default validator instrospected from database schema.
        $this->validatorSchema['email'] = new sfValidatorAnd(array(
                    $this->validatorSchema['email'],
                    new sfValidatorEmail(),
                ));

        $this->widgetSchema->setNameFormat('job[%s]');
    }

}
