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

Com o Git, Docker e Docker Compose devidamente instalados, basta clonar o projeto e executar o ```docker-compose```:
```bash
 docker-compose up
```
Em outra aba no terminal rode o seguinte comando para rodas as migrações do banco:
```
 docker exec php-teste-vikings /bin/bash -c 'php artisan migrate:fresh --seed'
```

Após alguns minutos(depende da sua conexão) o front-end estará disponível em <localhost:8000> e o back-end em http://localhost:8082/cartorio.
