<?php 

namespace Source;

interface MinifyInterface {
    
   public function addFile(string $filePath): bool;
    
   public function addFolder(string $folderPath): bool; 

   public function getAddedFiles(): ?array; 
   
   public function minify(string $outputDir, string $outputFile, bool $makeFolder = true): ?string; 
    
} 