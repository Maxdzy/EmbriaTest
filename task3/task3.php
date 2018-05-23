<?php
/**
 * Class BiggFileRead
 * Класс, реализующий интерфейс SeekableIterator, для чтения текстового файла.
 */
class BiggFileRead implements SeekableIterator
{
    private $handle;
    private $position;
    private $bait;
    public function __construct($filePath)
    {
        $this->handle = @fopen($filePath, 'r') or die('Couldn\'t get handle');
        $this->position = 0;
        $this->bait = 2000;
    }
    /**
     * Возвращает текущий элемент
     */
    public function current()
    {
		$contents = fread($this->handle, $this->bait);
		//echo $contents;
		fclose($this->handle);
        return $contents ?: 'not found, file eof';
    }
    /**
     * Переходит к следующему элементу
     */
    public function next()
    {
        $this->position+=$this->bait;
        fseek($this->handle, $this->position);
    }
    /**
     * Переходит к следующему элементу
     */
    public function prev()
    {
        $this->position-=$this->bait;
        fseek($this->handle, $this->position);
    }
    /**
     * Возвращает ключ текущего элемента
     */
    public function key()
    {
        return (int)$this->position;
    }
    /**
     * Проверка корректности позиции
     */
    public function valid()
    {
        return !feof($this->handle);
    }
    /**
     * Возвращает итератор на первый элемент
     */
    public function rewind()
    {
        $this->position = 0;
        fseek($this->handle, $this->position);
    }
    /**
     * Осуществляет перемещение к заданной позиции в байтах
     * @param int $position
     */
    public function seek($position)
    {
        $this->position = $position;
        fseek($this->handle, $this->position);
    }
    
}

$fh="Jako_pomnishi_ego.txt";
$my_object = NEW BiggFileRead($fh);
//$my_object->seek(1550);
$my_object->next();
//$my_class->prev();
echo $my_object->current();

