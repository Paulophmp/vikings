# Equipe - Vikings

## Tecnologias utilizadas

- Docker
- Docker-compose
- PHP 7.34 (php-fpm)
  - Laravel
  - Eloquent ORM
- Nginx
- Javascript
  - VueJS + VueX + Vuetify + Axios
- MySQL

### Intalação

Com o git, docker e docker-compose devidamente instalados, basta clonar o projeto e executar o ```docker-compose```:
```bash
 docker-compose up
```

Logo após dar permissão na pasta storage:
```bash
 chmod -R 775 storage
```

Em outra aba no terminal rode o seguinte comando para rodas as migrações do banco:
```
 docker exec php-teste-vikings /bin/bash -c 'php artisan migrate:fresh --seed'
```

Após alguns minutos(depende da conexão) a aplicação estará disponível em:

 - Frontend => http://localhost:8080
 - Backend => http://localhost:8082
 
 ### Documentação - RN
  [Documentação do projeto](README_DOC.md)

 ### Arquivos necessários
 * [Arquivo XML](https://github.com/p21sistemas/vikings/blob/master/Cart%C3%B3rios-CNJ.xml) - Arquivo XML para importação
