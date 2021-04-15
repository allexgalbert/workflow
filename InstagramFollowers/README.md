# Работа с инстаграм

- С чужого инстаграм-аккаунта собираем его фоловеров, подписываемся на фоловеров, лайкаем несколько фоток у каждого фоловера
- Со своего инстаграм-аккаунта собираем подписчиков и подписки, проходим по списку подписок, отписываемся от аккаунтов кто не подписался в ответ

```sql
CREATE TABLE `insta` (
`id` INT(11) NOT NULL AUTO_INCREMENT,
`url` VARCHAR(255) NULL DEFAULT NULL COMMENT 'профиль',
PRIMARY KEY (`id`),
UNIQUE INDEX `url_UNIQUE` (`url`)
)
COMMENT='акки от которых отписались'
COLLATE='utf8_general_ci'
ENGINE=InnoDB;