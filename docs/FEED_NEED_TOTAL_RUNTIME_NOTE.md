# Feed need_total runtime note

## Поведение
- `itFeed` теперь поддерживает опцию `need_total`.
- По умолчанию `need_total=true`.
- Если `need_total=false`, SQL-feed не выполняет отдельный `COUNT(*)` и использует только количество уже загруженных строк текущей выборки.

## Для проекта Colibri
Во всех активных проектных инициализациях `new itFeed([...])` установлено `need_total => false`.

## Важно
Это ускоряет загрузку feed-блоков, но там, где код вызывает `count_all()`, значение больше не является полным total-count. При `need_total=false` это count текущего загруженного batch/result set.
