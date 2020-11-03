<?php 

namespace Source;

interface MinifyInterface {
    
   public function addFile(string $filePath): bool;
    
   public function addFolder(string $folderPath): bool; 

   public function getAddedFiles(): ?array; 
   
   public function minify(string $outputDir, bool $makeFolder = true): ?string; 
    
}