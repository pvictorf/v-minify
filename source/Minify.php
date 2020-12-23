<?php 

namespace Source;

use \Exception;
use \MatthiasMullie\Minify\CSS;
use \MatthiasMullie\Minify\JS;

class Minify implements MinifyInterface {
   /**@var CSS|JS */
   private $minify;

   /**@var array */
   private $files;

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
      $this->files = [];
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
        
         if($this->validateFile($filePath)) {
            $this->files[] = $filePath;
            $this->addToMinify();
         }
         
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

         if($this->validateFile($filePath)) {
            $this->files[] = $filePath;
            $this->addToMinify();
         }
      }

      return true;
   }

   private function validateFile(string $filePath): bool {
      return (is_file($filePath) && file_exists($filePath) && !in_array($filePath, $this->files));
   }

   /**
    * 
    * @return void
    */
   private function addToMinify(): void {
      foreach ($this->files as $file) {
         $this->minify->addFile($file);
      } 
   }
   
   /**
    * Minifica os arquivos adicionados juntando em um arquivo único
    * @param  string $outputDir
    * @param  string $outputFile
    * @param  bool $makeFolder
    * @return object
    */
   public function minify(string $outputDir, string $outputFile, bool $forceMakeFolder = true): ?string 
   {
      $output = (substr($outputDir , -1) === '/') ? "{$outputDir}{$outputFile}" : "{$outputDir}/{$outputFile}";

      if(!is_dir($outputDir) && $forceMakeFolder) {
         mkdir($outputDir);
      }

      try {
         $this->minify->minify($output); 
         return $output;

      } catch(\Exception $e) {
         throw new Exception("[Error] Could not minify... " . $e->getMessage());
         return null;
      }
      
   }

   public function getFiles(): ?array 
   {
      return $this->files;
   }
}
