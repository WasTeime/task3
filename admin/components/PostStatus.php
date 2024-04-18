<?php

namespace admin\components;

class PostStatus
{
    private $statuses = [
        0 => 'brandnew',
        10 => 'published',
        20 => 'rejected',
    ];
    private $en_ru = ['brandnew' => 'новый', 'published' => 'опубликовано', 'rejected' => 'отменён'];

    //параметр translate указывает нужно ли переводить названия статусов на русский
    //параметр forDropdown указывает нужны ли статусы для вывода в dropdownList
    public function getStatuses(bool $translate = false)
    {
        return $translate ? $this->getStatusesTranslate() : $this->statuses;
    }

    //если статус не найден возвращается -1, иначе значение статуса и имя
    public function getStatusByCode(int $code, bool $translate = false) : array|int
    {
        if (array_key_exists($code, $this->statuses)) {
            return [$code => $translate ? $this->en_ru[$this->statuses[$code]] : $this->statuses[$code]];
        }
        return -1;
    }

    public function getStatusByName(string $name, bool $translate = false) : array|int
    {
        foreach ($this->statuses as $code => $statusName) {
            if ($statusName == $name) {
                return [$code => $translate ? $this->en_ru[$statusName] : $statusName];
            }
        }

        return -1;
    }

    //если статус и код не существует в массиве, тогда возвращаем true и добавляем статус, если существует возвращаем false
    public function addStatus(string $name, int $statusCode)
    {
        if (!in_array($name, $this->statuses) && !array_key_exists($statusCode, $this->statuses)) {
            $this->statuses[$statusCode] = $name;
            return true;
        }
        return false;
    }

    //список статусов только с названиями на русском
    private function getStatusesTranslate()
    {
        $data = [];
        foreach ($this->statuses as $code => $name) {
            $data[$code] = $this->en_ru[$name];
        }
        return $data;
    }
}