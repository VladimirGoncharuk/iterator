<?php
class deletTegIterator implements Iterator
{
    const ROW_SIZE = 4096;
    protected $filePointer = null;
    protected $currentElement = null;
    protected $rowCounter = null;
  
 
    public function __construct($file)
    {
        try {
            $this->filePointer = fopen($file, 'rb');
          
        } catch (\Exception $e) {
            throw new \Exception('The file "' . $file . '" cannot be read.');
        }
    }
 
    public function rewind(): void
    {
        $this->rowCounter = 0;
        rewind($this->filePointer);
    }
 
    public function current()
    {
     $tag = fgets($this->filePointer, self::ROW_SIZE);
     
     $this->currentElement = (str_contains($tag, 'description')||str_contains($tag, 'keywords'))?strip_tags($tag):((str_contains($tag, '<title>'))? preg_replace('/<(title).*?>(.*?)<\/\1>/ism', '', $tag):$tag);
    
        $this->rowCounter++;
 
        return $this->currentElement;
    }
   
    public function key(): int
    {
        return $this->rowCounter;
    }
 
    public function next(): bool
    {
        if (is_resource($this->filePointer)) {
            return !feof($this->filePointer);
        }
 
        return false;
    }
 
    public function valid(): bool
    {
        if (!$this->next()) {
            if (is_resource($this->filePointer)) {
                fclose($this->filePointer);
            }
 
            return false;
        }
 
        return true;
    }
}
 
/**
 * Клиентский код.
 */
$del = new deletTegIterator(__DIR__ . '/iteratorAction.html');
 
foreach ($del as $key => $row) {
    print_r($row);
}
