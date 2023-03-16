### Aplicação montada para testes

### Rodando diretamente no container:

Rodar as linhas no terminal:

sudo docker pull golang

sudo docker run --name testegoroutine -it -v "$PWD:/public" golang bash

vá até a pasta public do seu container

no bash execute:

go run comprar-bolo.go