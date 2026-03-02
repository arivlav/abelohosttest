<?php

declare(strict_types=1);

require __DIR__ . '/../../vendor/autoload.php';
require __DIR__ . '/../helpers.php';

App\Common\Config::load();

use App\Database\Seeder;

$categories = [
    ['name' => 'Category1', 'description' => 'Bla-bla-bla'],
    ['name' => 'Category2', 'description' => 'Bla-bla-bla2'],
    ['name' => 'Category3', 'description' => 'Bla-bla-bla3'],
    ['name' => 'Category4', 'description' => 'Bla-bla-bla4'],
    ['name' => 'Category5', 'description' => 'Bla-bla-bla5'],
];

$articles = [
    [
        'name' => 'Первая статья',
        'description' => 'bla bla bla',
        'content' => 'Текст...',
        'category' => 'Category1', // или category_id => 1
        'views' => 10,
        'published_at' => new DateTimeImmutable(),
    ],
    [
        'name' => 'Статья 2: Обзор возможностей',
        'description' => 'bla bla bla',
        'content' => 'Короткий обзор возможностей проекта и структуры кода.',
        'category' => 'Category2',
        'views' => 25,
        'published_at' => new DateTimeImmutable('-19 days'),
    ],
    [
        'name' => 'Статья 3: Быстрый старт',
        'description' => 'bla bla bla',
        'content' => 'Как развернуть проект, поднять контейнеры и открыть в браузере.',
        'category' => 'Category3',
        'views' => 42,
        'published_at' => new DateTimeImmutable('-18 days'),
    ],
    [
        'name' => 'Статья 4: Роутинг',
        'description' => 'bla bla bla',
        'content' => 'Разбираем таблицу маршрутов и диспетчеризацию запросов.',
        'category' => 'Category4',
        'views' => 17,
        'published_at' => new DateTimeImmutable('-17 days'),
    ],
    [
        'name' => 'Статья 5: Контроллеры и шаблоны',
        'description' => 'bla bla bla',
        'content' => 'Как контроллер передает данные в Smarty-шаблон и возвращает HTML.',
        'category' => 'Category5',
        'views' => 66,
        'published_at' => new DateTimeImmutable('-16 days'),
    ],
    [
        'name' => 'Статья 6: Работа с базой данных',
        'description' => 'bla bla bla',
        'content' => 'Prepared statements, биндинг параметров и аккуратная работа с PDO.',
        'category' => 'Category1',
        'views' => 31,
        'published_at' => new DateTimeImmutable('-15 days'),
    ],
    [
        'name' => 'Статья 7: Индексы и производительность',
        'description' => 'bla bla bla',
        'content' => 'Зачем нужны индексы по views и published_at и как это ускоряет выборки.',
        'category' => 'Category2',
        'views' => 89,
        'published_at' => new DateTimeImmutable('-14 days'),
    ],
    [
        'name' => 'Статья 8: Мягкое удаление',
        'description' => 'bla bla bla',
        'content' => 'deleted_at, почему это удобно и как жить без физического удаления.',
        'category' => 'Category3',
        'views' => 12,
        'published_at' => new DateTimeImmutable('-13 days'),
    ],
    [
        'name' => 'Статья 9: Категории',
        'description' => 'bla bla bla',
        'content' => 'Создаем категории, привязываем статьи и следим за целостностью данных.',
        'category' => 'Category4',
        'views' => 58,
        'published_at' => new DateTimeImmutable('-12 days'),
    ],
    [
        'name' => 'Статья 10: Внешние ключи',
        'description' => 'bla bla bla',
        'content' => 'Как внешний ключ защищает от “битых” ссылок на категории.',
        'category' => 'Category5',
        'views' => 21,
        'published_at' => new DateTimeImmutable('-11 days'),
    ],
    [
        'name' => 'Статья 11: Docker Compose',
        'description' => 'bla bla bla',
        'content' => 'Пара полезных советов по работе с docker-compose и инициализацией MySQL.',
        'category' => 'Category1',
        'views' => 77,
        'published_at' => new DateTimeImmutable('-10 days'),
    ],
    [
        'name' => 'Статья 12: Конфигурация приложения',
        'description' => 'bla bla bla',
        'content' => 'Где лежат настройки и как Config::get() достает значения.',
        'category' => 'Category2',
        'views' => 36,
        'published_at' => new DateTimeImmutable('-9 days'),
    ],
    [
        'name' => 'Статья 13: Структура проекта',
        'description' => 'bla bla bla',
        'content' => 'Папки src/Common, src/Http и их ответственность.',
        'category' => 'Category3',
        'views' => 14,
        'published_at' => new DateTimeImmutable('-8 days'),
    ],
    [
        'name' => 'Статья 14: Валидация входящих данных',
        'description' => 'bla bla bla',
        'content' => 'Несколько подходов к валидации параметров до обращения к БД.',
        'category' => 'Category4',
        'views' => 9,
        'published_at' => new DateTimeImmutable('-7 days'),
    ],
    [
        'name' => 'Статья 15: Пагинация статей',
        'description' => 'bla bla bla',
        'content' => 'Как выбирать статьи постранично и сортировать по дате публикации.',
        'category' => 'Category5',
        'views' => 53,
        'published_at' => new DateTimeImmutable('-6 days'),
    ],
    [
        'name' => 'Статья 16: Топ по просмотрам',
        'description' => 'bla bla bla',
        'content' => 'Выборка самых популярных статей и почему индекс по views важен.',
        'category' => 'Category1',
        'views' => 120,
        'published_at' => new DateTimeImmutable('-5 days'),
    ],
    [
        'name' => 'Статья 17: Работа с датами',
        'description' => 'bla bla bla',
        'content' => 'published_at: когда ставить, как форматировать и как фильтровать.',
        'category' => 'Category2',
        'views' => 28,
        'published_at' => new DateTimeImmutable('-4 days'),
    ],
    [
        'name' => 'Статья 18: Ошибки и исключения',
        'description' => 'bla bla bla',
        'content' => 'Как обрабатывать ошибки БД и рендера шаблонов без “белого экрана”.',
        'category' => 'Category3',
        'views' => 19,
        'published_at' => new DateTimeImmutable('-3 days'),
    ],
    [
        'name' => 'Статья 19: Тестовые данные',
        'description' => 'bla bla bla',
        'content' => 'Почему сидеры полезны и как быстро наполнять БД для разработки.',
        'category' => 'Category4',
        'views' => 64,
        'published_at' => new DateTimeImmutable('-2 days'),
    ],
    [
        'name' => 'Статья 20: Итоги',
        'description' => 'bla bla bla',
        'content' => 'Подводим итоги: категории, статьи, шаблоны, сидер и дальнейшие шаги.',
        'category' => 'Category5',
        'views' => 7,
        'published_at' => new DateTimeImmutable('-1 day'),
    ],
    [
        'name' => 'Статья 21: Планы на улучшения',
        'description' => 'bla bla bla',
        'content' => 'Идеи: CRUD, админка, загрузка изображений и поиск по статьям.',
        'category' => 'Category1',
        'views' => 5,
        'published_at' => new DateTimeImmutable('now'),
    ],
];

$result = Seeder::run($categories, $articles);

print_r($result);