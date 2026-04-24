Невеликий проєкт для аналізу подій користувачів і пошуку підозрілих акаунтів за набором правил.

Скрипт:
читає події з `events.json` у форматі JSON 
накопичує статистику по кожному користувачу
застосовує правила виявлення підозрілої поведінки
записує результат у `suspicious.json`

## Встановлення
```bash
composer dump-autoload
```
## Запуск
```bash
php run.php events.json
```

Після запуску буде створено файл `suspicious.json`.

Для кожного користувача є накопичення:
кількість відправлених повідомлень
кількість отриманих повідомлень 
кількість отриманих блокувань
кількість отриманих скарг
кількість унікальних отримувачів

## Рули

### `ManyUniqueReceiversRule`

Спрацьовує, якщо:
користувач відправив щонайменше `20` повідомлень
написав щонайменше `15` унікальним отримувачам

### `HighBlockRateRule`

Спрацьовує, якщо:
користувач відправив щонайменше `10` повідомлень
частка блокувань `blocks_received / messages_sent >= 0.3`

### `HighComplaintRateRule`

Спрацьовує, якщо:
користувач відправив щонайменше `10` повідомлень
частка скарг `complaints_received / messages_sent >= 0.1`

## Основний алгоритм

1. `JsonLineEventReader` читає файл построково.
2. `EventFactory` створює потрібний тип події.
3. `EventProcessor` оновлює статистику користувачів.
4. `RulesEngine` запускає всі правила.
5. `SuspiciousJsonWriter` зберігає результат у JSON.

Як інтегрувати в прод

1. окремий PHP microservice для spam detection
2. події приходять через message broker
3. сервіс читає їх асинхронно через consumer
4. Redis зберігає короткоживучу статистику по юзеру
5. MySQL зберігає moderation cases / історію підозрілих акаунтів
6. підозрілих акаунтів сервіс кладе в чергу на модерацію
7. модераторський сервіс або внутрішній інструмент читає цю чергу


Які ще можна умови додати:
наприклад
HighNoReplyRateRule
Логіка:
користувач багато пише, але майже не отримує повідомлень у відповідь

Приблизна умова:
messagesSent >= 20
messagesReceived / messagesSent <= 0.05

або
багато повідомлень, багато унікальних отримувачів, мало вхідних повідомлень

наприклад:
messagesSent >= 30
uniqueReceivers >= 20
messagesReceived <= 2

також можнапридумати формулу по якій рахувати підозрілість
наприклад:
0.30 * block_rate + 0.40 * complaint_rate + 0.10 * unique_receivers_ratio + 0.10 * no_reply_rate
де
block_rate = blocks_received / messages_sent
complaint_rate = complaints_received / messages_sent
unique_receivers_ratio = unique_receivers / messages_sent
no_reply_rate = no_reply_received / messages_sent