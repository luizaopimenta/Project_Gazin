# Projeto Gazin

## Teste Prático - Desafio FullStack

## 1 ª Etapa

 * Acesse a raiz do projeto pelo terminal:


cd Project_Gazin



## 2 ª Etapa

 * Levante os containers:


docker compose up -d --build



## 3 ª Etapa

 * Acesse o container do backend:


docker exec -it backend sh



## 4 ª Etapa

 * Rode as migrations para construir o banco:


php artisan migrate



## 5 ª Execute os Testes

 * Execute os testes para se certificar que tudo esta funcionando:


./vendor/bin/pest



## 6 ª Acesse a documentação

 * Você pode conferir as rotas através da documentação do sistema:


 [http://localhost:8000/api/documentation](http://localhost:8000/api/documentation)


## 7 ª Faça os seus acessos

 * Frontend:
  [http://localhost:3000](http://localhost:3000)
 

* Backend:
  [http://localhost:8000/api](http://localhost:8000/api)