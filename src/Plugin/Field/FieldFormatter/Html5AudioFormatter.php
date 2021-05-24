<?php

namespace Drupal\html5_audio\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Plugin implementation of the 'HTML5 Audio' formatter.
 *
 * @FieldFormatter(
 *   id = "html5_audio_formatter",
 *   label = @Translation("HTML5 Audio"),
 *   field_types = {
 *     "link"
 *   }
 * )
 */
class Html5AudioFormatter extends FormatterBase {

  /**
   * {@inheritdoc}
   */
  public static function defaultSettings() {
    return [
      'autoplayStatus' => '0',
    ] + parent::defaultSettings();
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {

    /*
    $elements['foo'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Foo'),
      '#default_value' => $this->getSetting('foo'),
    ];
    */

    $elements['autoplayStatus'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Enable Autoplay'),
      '#default_value' => $this->getSetting('autoplayStatus'),
    ];

    return $elements;
  }

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
    $summary = [];
    //$summary[] = $this->t('Foo: @foo', ['@foo' => $this->getSetting('foo')]);
    
    $settings = $this->getSettings();
    if ($settings['autoplayStatus']) {
      $summary[] = $this->t('Autoplay is enabled.');
    }
    else {
      $summary[] = $this->t('Autoplay is not enabled.');
    }
    //$summary[] = $this->t('autoplay: @autoplay', ['@autoplay' => $this->getSetting('autoplay')]);
    return $summary;
  }

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = [];

    // Render all field values as part of a single <audio> tag.
    $sources = [];
    foreach ($items as $item) {
      // Get the mime type.
      $mimetype = \Drupal::service('file.mime_type.guesser')->guess($item->uri);
      $sources[] = [
        'src' => $item->uri,
        'mimetype' => $mimetype,
      ];
   }

    // Configuration.
    $autoplay = '';
    if ($this->getSetting('autoplayStatus')) {
      $autoplay = 'autoplay';
    }

   // Put everything in an array for theming.
    $elements[] = [
      '#theme' => 'audio_tag',
      '#sources' => $sources,
      '#autoplay' => $autoplay,
    ];

   return $elements;

  }

}
