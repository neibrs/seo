<?php

namespace Drupal\seo_logo;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\File\FileSystemInterface;

class LogoManager implements LogoManagerInterface {

  /** @var \Drupal\seo_logo\Entity\LogoInterface */
  protected $logoStorage;

  public function __construct(EntityTypeManagerInterface $entity_type_manager) {
    $this->logoStorage = $entity_type_manager->getStorage('seo_logo');
  }

  public function loadTxtImages($file_uri) {
    $content = file_get_contents($file_uri);
    $items = array_unique(explode('-||-', str_replace("\n", "-||-", str_replace("\r\n","-||-", $content))));
    $items = array_filter($items);

    $files = [];

    foreach ($items as $item) {
      $file = $this->get_external_image($item, 'public://logos/');// . date('Ymd') . '/');
      $this->createLogoEntity($file);

      $files[] = $file->id();
    }
    return $files;
  }

  public function createLogoEntity($file) {
    $values = [
      'name' => $file->getFileName(),
      'file' => [
        $file->id(),
      ],
    ];
    $logo = $this->logoStorage->create($values);
    $logo->save();

    return $logo;
  }


  public function get_external_image($url, $path) {
    $external_image = file_get_contents($url);
    $parsed_url = parse_url($url);
    $name_dest = rand(1000,9999)."_". basename($parsed_url["path"]);

    try {
//      \Drupal::service('file_system')->prepareDirectory($path, FileSystemInterface::CREATE_DIRECTORY|FileSystemInterface::MODIFY_PERMISSIONS);
      $file = file_save_data($external_image, $path . $name_dest, FileSystemInterface::EXISTS_RENAME);
      $file->save();
      return $file;
    }
    catch (\Exception $e) {
      \Drupal::messenger()->addError('Public://logos目录无法读写.');
    }
  }
}
