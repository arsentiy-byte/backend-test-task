# Линтеры

## PHP-CS-Fixer для форматирования и стандартизации кода

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

## Анализатор кода
> Используется библиотека [PHPStan](https://phpstan.org/)
> Конфигурация в [phpstan.dist.neon](../phpstan.dist.neon)

Для запуска анализатора используется команда:
```shell
vendor/bin/phpstan analyse -c phpstan.dist.neon
```

Или

```shell
make phpstan
```
