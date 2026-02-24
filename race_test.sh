#!/bin/bash

# Цвета для красоты вывода
GREEN='\033[00;32m'
RED='\033[00;31m'
RESTORE='\033[0m'

echo "--- Тестирование Race Condition (одновременный захват заявки) ---"

# 1. Сначала убедитесь, что в базе есть заявка с ID=1 и статусом 'assigned'
# Если ID другой, поменяйте его в URL ниже.

echo "Запуск двух параллельных запросов..."

# Запускаем два фоновых процесса curl. 
# Добавляем заголовок Accept: application/json, чтобы контроллер вернул JSON ответ.
curl -s -o /dev/null -w "Запрос 1: HTTP %{http_code}\n" \
    -H "Accept: application/json" \
    -X POST http://localhost:8000/requests/1/take &

curl -s -o /dev/null -w "Запрос 2: HTTP %{http_code}\n" \
    -H "Accept: application/json" \
    -X POST http://localhost:8000/requests/1/take &

# Ждем завершения обоих фоновых процессов
wait

echo "---------------------------------------------------------------"
echo -e "Результат: Один запрос должен быть ${GREEN}200${RESTORE}, а второй ${RED}409${RESTORE}."
echo "Если оба 200 — защита не сработала. Если оба 409 — заявка уже была в работе."
