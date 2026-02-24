#!/bin/bash
# Скрипт для проверки Race Condition (два мастера пытаются взять одну заявку)
URL="http://localhost:8000/api/requests/1/take"
TOKEN_MASTER_1="Bearer master_1_token"
TOKEN_MASTER_2="Bearer master_2_token"

echo "Запуск параллельных запросов..."

curl -X POST $URL -H "Authorization: $TOKEN_MASTER_1" -s -o /dev/null -w "Мастер 1: %{http_code}\n" &
curl -X POST $URL -H "Authorization: $TOKEN_MASTER_2" -s -o /dev/null -w "Мастер 2: %{http_code}\n" &

wait
echo "Проверка завершена. Один должен быть 200, второй 409."
