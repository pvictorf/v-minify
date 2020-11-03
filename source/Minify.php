<?php 

namespace Source;

use \Exception;
use \MatthiasMullie\Minify\CSS;
use \MatthiasMullie\Minify\JS;

class Minify {
   /**@var CSS|JS */
   private $minify;

   /**@var array */
   private $addedFiles;

   /**@var MinifyType */
   public const CSS = "\MatthiasMullie\Minify\CSS";

   /**@var MinifyType */
   public const JS = "\MatthiasMullie\Minify\JS";

      
   /**
    * Recebe o tipo de minificador (JS / CSS)
    * @param  string $minifyType
    * @return void
    */
   public function __construct(string $minifyType)
   {
      $this->minify = (new $minifyType());
      $this->addedFiles = [];
   }
   
   /**
    * setMinify
    * @param string $minifyType
    * @return void
    */
   public function setMinify(string $minifyType): void 
   {
      $this->minify = (new $minifyType());
   }
   
   /**
    * Adiciona um arquivo específico
    * @param  string $filePath
    * @return void
    */
   public function addFile(string $filePath): bool 
   {
      if(is_file($filePath) && file_exists($filePath)) {
         $this->minify->addFile($filePath);
         $this->addedFiles[] = $filePath;
         
      } else {
         throw new \Exception("[Error] $filePath is not a valid file or no exist");
         return false;
      }

      return true;
   }
   
   /**
    * Adiciona todos os arquivos de uma pasta específica
    * @param string $folderPath
    * @return bool
    */
   public function addFolder(string $folderPath): bool 
   {
      if(!is_dir($folderPath)) {
         return false;
      }

      $scannedFiles = scandir($folderPath);
   
      foreach($scannedFiles as $file) {    
         $filePath = (substr($folderPath , -1) === '/') ? "{$folderPath}{$file}" : "{$folderPath}/{$file}";

         if(is_file($filePath) && file_exists($filePath)) {
            $this->minify->addFile($filePath);
            $this->addedFiles[] = $filePath;
         }
      }

      return true;
   }
   
   /**
    * Minifica os arquivos adicionados juntando em um arquivo único
    * @param  string $outputDir
    * @param  string $outputFile
    * @param  bool $makeFolder
    * @return object
    */
   public function minify(string $outputDir = __DIR__ . "/../assets/output", string $outputFile = "output.js", bool $makeFolder = true): ?object 
   {
      if($makeFolder) {
         //[TODO] Buscar o diretorio do arquivo
         //Tentar criar a pasta com permissão de escrita
      }

      $output = (substr($outputDir , -1) === '/') ? "{$outputDir}{$outputFile}" : "{$outputDir}/{$outputFile}";

      try {
         $minifed = $this->minify->minify($output); 

         return (object) [
            "file" => $output,
            "minifed" => $minifed 
         ];
      } catch(\Exception $e) {
         throw new Exception("[Error] Could not minify... " . $e->getMessage());
         return null;
      }
      
   }

   public function getAddedFiles(): ?array 
   {
      return $this->addedFiles;
   }
}