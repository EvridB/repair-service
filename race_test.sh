#!/bin/bash
# Скрипт для проверки Race Condition
echo "Запуск параллельных запросов на одну заявку..."

# Пытаемся одновременно взять заявку №1 от имени двух мастеров
curl -X POST http://localhost:8000/requests/1/take &
curl -X POST http://localhost:8000/requests/1/take &

wait
echo -e "\nПроверка завершена. Один запрос должен вернуть 200, второй 409."
