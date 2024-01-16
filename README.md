## Comandos úteis:

> Para limpar o cache de rota após alguma alteração:
> <br> `php artisan route:clear`

> Para listar as rotas:
> <br> `php artisan route:list`

> Para atualizar o arquivo de autoload do Composer. <br>
> Sempre que criar uma nova pasta dentro de 'Models' ou 'Controllers/API' talvez deva executar este comando:
> <br> `composer dump-autoload`

> Para limpar o cache:
> <br> `php artisan clear-compiled && php artisan config:clear && php artisan cache:clear && php artisan view:clear`

> Para (re)criar as tabelas da base de dados e popular com os seeds:
> <br> `php artisan migrate:fresh --seed`

> Para (re)criar o arquivo _ide_helper.php que auxilia no preenchimento automático da IDE durante o desenvolvimento:
> <br> `php artisan ide-helper:generate && php artisan ide-helper:models -N`

teste de CI

> Comando para problema de memory com o PHPStan:
> <br> `php ./vendor/bin/phpstan analyse app --memory-limit=1G`
