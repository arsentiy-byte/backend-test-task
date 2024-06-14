# Линтеры

## Laravel Pint для форматирования и стандартизации кода

> Используется библиотека [PHP-CS-Fixer](https://github.com/PHP-CS-Fixer/PHP-CS-Fixer)
> Конфигурация в [.php-cs-fixer.dist.php](../.php-cs-fixer.dist.php)

Для проверки кода запускается команда:
```shell
vendor/bin/php-cs-fixer fix --dry-run --diff
```

Или

```shell
make fixer-test
```

Для форматирования кода:
```shell
vendor/bin/php-cs-fixer fix
```

Или

```shell
make fixer
```
