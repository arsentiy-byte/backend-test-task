# Тестовые данные

> В проекте присутствует тестовые данные (см. в [DataFixtures](../src/DataFixtures))

Для загрузки данных используйте след. команду:

```shell
php bin/console --env=test doctrine:fixtures:load --purge-with-truncate --no-interaction
```

- Тестовый пользователь ([UserFixture](../src/DataFixtures/UserFixture.php))

```json
{
  "email": "test@example.com",
  "roles": ["ROlE_USER"],
  "password": "test"
}
```

- Товары ([ProductFixture](../src/DataFixtures/ProductFixture.php))

```json
{
  "products": [
    {
      "title": "Iphone",
      "price": 100.0
    },
    {
      "title": "Наушники",
      "price": 20.0
    },
    {
      "title": "Чехол",
      "price": 10.0
    }
  ]
}
```

- Налоги ([TaxFixture](../src/DataFixtures/TaxFixture.php))

```json
{
  "taxes": [
    {
      "code": "DE795581124",
      "country": "Германия",
      "value": 19
    },
    {
      "code": "IT87059898280",
      "country": "Италия",
      "value": 22
    },
    {
      "code": "FRYY610521164",
      "country": "Франция",
      "value": 20
    },
    {
      "code": "GR216746731",
      "country": "Греция",
      "value": 24
    }
  ]
}
```

- Купоны ([VoucherFixture](../src/DataFixtures/VoucherFixture.php))

```json
{
  "vouchers": [
    {
      "code": "P10",
      "discountType": "percentage",
      "discount": 10
    },
    {
      "code": "P100",
      "discountType": "percentage",
      "discount": 100
    },
    {
      "code": "F50",
      "discountType": "fixed",
      "discount": 50
    }
  ]
}
```
