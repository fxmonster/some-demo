<?php

namespace App\TagHandlers\Contracts;

use App\Models\Bet;
use App\Models\Casino\Game;
use App\Models\Casino\GameTransaction;

/**
 * Класс обработчик Тега
 * Должен содержать конфигурацию (config), знать какому пользователю назначен конкретный экземпляр класса
 * В конструкторе абстрактного Тега получаем пользователя и конфигурацию во внутренние структуры
 */
interface TagHandler
{
    /**
     * Запускает обработчик тега со своей внутренней логикой
     */
    public function handle(): void;

    /**
     * Запускает проверку применимости тега для ставки.
     * Возвращает тип бонусного счета, применимый для ставки
     */
    public function check(Bet|GameTransaction|Game $bid): ?int;

    /**
     * Начисление бонусов, если все условия выполнены и вознаграждение еще не начислено
     *
     * @throws \App\Exceptions\MoneyService\UserMoneyChangeException
     * @throws \Throwable
     */
    public function charge(): void;


}
