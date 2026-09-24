# ÕpiEestis: запуск на другом ПК и загрузка в GitHub

**Важно:** это отдельный проект. Не загружай его в старый репозиторий Newsportal.

## 1. Распакуй полный ZIP

Распакуй папку `opi_eestis` в `C:\xampp\htdocs\`, чтобы файл `C:\xampp\htdocs\opi_eestis\index.php` существовал. Папка `.git` внутри проекта уже содержит **8 настоящих локальных коммитов этого нового репозитория**. Не запускай `git init` повторно и не создавай коммиты задним числом.

Открой в VS Code именно папку `opi_eestis`. В терминале выполни `git status` и `git log --oneline`. Если история видна - всё правильно.

Если Windows не распаковал скрытую папку `.git`, используй архив истории `.bundle`: в папке, где лежит файл `OpiEestis_git_ajalugu.bundle`, выполни `git clone OpiEestis_git_ajalugu.bundle opi_eestis`, затем перенеси полученную папку в `C:\xampp\htdocs\`. Не клонируй bundle поверх уже существующей одноимённой папки.

## 2. Создай НОВЫЙ пустой репозиторий на GitHub

GitHub → **New repository** → имя **`opi-eestis`** → выбери видимость → **Create repository**. Не ставь галочки «Add a README file», «Add .gitignore» и «Choose a license»: локальные файлы уже есть, нам нужен пустой удалённый репозиторий. Скопируй адрес репозитория.

В терминале VS Code внутри `C:\xampp\htdocs\opi_eestis` выполни:

```powershell
git status
git log --oneline
git remote -v
git remote add origin https://github.com/ТВОЙ_НИК/opi-eestis.git
git branch -M main
git push -u origin main
```

Заменяй `ТВОЙ_НИК` своим именем на GitHub. Если увидишь `remote origin already exists`, проверь его адрес и при необходимости исправь `git remote set-url origin https://github.com/ТВОЙ_НИК/opi-eestis.git`. На GitHub проверь вкладки **Code** и **Commits**: должны отображаться проект, документация и 8 коммитов.

**Авторизация:** для HTTPS GitHub может запросить вход через браузер / Git Credential Manager. Пароль от GitHub не нужно писать в файлы проекта.

## 3. Запусти готовый проект

В XAMPP включи Apache и MySQL. В `http://localhost/phpmyadmin/` открой **Import**, выбери `C:\xampp\htdocs\opi_eestis\database\opi_eestis.sql` и импортируй. Это **новая БД `opi_eestis`**, она не трогает старый Newsportal.

Открой `http://localhost/opi_eestis/`. Зарегистрируй пользователя через «Registreeru». Для администратора в терминале выполните:

```powershell
C:\xampp\php\php.exe tools\create_admin.php
```

Если у тебя ранее уже была старая база **ÕpiEestis**, а не новая пустая установка, сначала сохрани её резервную копию и **вместо полного повторного импорта** один раз выполни `database/upgrade_v2.sql` (добавляет таблицу сохранённых материалов). Новые демо-уроки импортируются с новой чистой установкой.

## 4. Проверка и следующие коммиты

```powershell
C:\xampp\php\php.exe tests\smoke.php
git status
```

Сценарии ручной проверки лежат в `docs/TESTIMINE.md`. После изменения файлов:

```powershell
git add .
git commit -m "Fix lesson bookmark UI after XAMPP test"
git push
```

В проекте лежат `README.md`, техническое задание, руководство пользователя, план тестирования, оценка 3 недостатков/3 улучшений и PDF-версии документов. **Покрытие >60% пока не доказано и не должно упоминаться как выполненное.** Чтобы довести проект до требования по коду, нужно добавить настоящие интеграционные тесты и снять отчёт PHPUnit + Xdebug/PCOV на твоём XAMPP.
