<?php

/**
 * Class Task3
 * Класс, реализующий интерфейс SeekableIterator, для чтения текстового файла.
 */
class Task3 implements SeekableIterator
{

    private $handle;
    private $position;

    public function __construct($filePath)
    {
        $this->handle = @fopen($filePath, 'r') or die('Couldn\'t get handle');
        $this->position = 1;
    }

    /**
     * Возвращает текущий элемент
     */
    public function current()
    {
        $line = 1;
        fseek($this->handle, 0);

        while (($current = fgets($this->handle)) && ($line < $this->position)) {
            $line++;
        }

        return $current ?: 'not found, file eof';
    }

    /**
     * Переходит к следующему элементу
     */
    public function next()
    {
        $this->position++;
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
        $this->position = 1;
    }

    /**
     * Осуществляет перемещение к заданной позиции
     * @param int $position
     */
    public function seek($position)
    {
        $this->position = $position;
    }
}

