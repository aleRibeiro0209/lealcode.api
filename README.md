# LealCode.API

## Descrição

**LealCode.API** é uma API RESTful desenvolvida em PHP com a arquitetura MVC (Model-View-Controller). Esta API foi projetada para gerenciar o estoque de uma rede de agências de carro. Este projeto é parte de uma atividade da disciplina de Engenharia de Software II, cujo objetivo é proporcionar uma experiência prática no uso da metodologia Scrum.

## Funcionalidades

- **Gerenciamento de Veículos**: Cadastro, atualização, consulta e remoção de veículos no estoque.
- **Gerenciamento de Carrocerias**: Cadastro, atualização, consulta e remoção de tipos de carrocerias.
- **Controle de Estoque**: Monitoramento de entrada e saída de veículos.
- **Relatórios**: Geração de relatórios de veículos vendidos e disponíveis em estoque.

## Estrutura do Projeto

A estrutura do projeto é baseada na arquitetura MVC, organizada da seguinte forma:

- **App/Core**:
  - `Controller.php`: Classe base para todos os controladores, fornecendo métodos comuns.
  - `Model.php`: Classe base para todos os modelos, com métodos para interação com o banco de dados.
  - `Routes.php`: Define as rotas da aplicação, ligando URLs a métodos nos controladores.

- **App/Controllers**:
  - `Veiculos.php`: Controlador para gerenciar as operações relacionadas aos veículos.
  - `Carrocerias.php`: Controlador para gerenciar as operações relacionadas às carrocerias.
  - `Cargos.php`: Controlador para gerenciar as operações relacionadas aos cargos.
  - `Funcionarios.php`: Controlador para gerenciar as operações relacionadas aos funcionários.
  - `Logins.php`: Controlador responsável pelas operações de autenticação e login.

- **App/Models**:
  - `Veiculo.php`: Modelo que representa a tabela de veículos no banco de dados.
  - `Carroceria.php`: Modelo que representa a tabela de carrocerias no banco de dados.
  - `Cargo.php`: Modelo que representa a tabela de cargos no banco de dados.
  - `Funcionario.php`: Modelo que representa a tabela de funcionários no banco de dados.

- **App/Middlewares**:
  - `AccessControl.php`: Middleware para verificar as permissões de acesso dos usuários às rotas protegidas.

- **Public**:
  - `index.php`: O ponto de entrada da aplicação que inicializa as rotas e direciona as requisições.

## Tecnologias Utilizadas

- **Linguagem**: PHP
- **Banco de Dados**: MySQL
- **Arquitetura**: RESTful API com MVC
- **Gerenciamento de Dependências**: Composer

## Instalação

1. Clone o repositório:
    ```bash
    git clone https://github.com/aleRibeiro0209/lealcode.api.git
    ```

2. Acesse o diretório do projeto:
    ```bash
    cd lealcode.api
    ```

3. Instale as dependências via Composer:
    ```bash
    composer install
    ```

4. Configure o banco de dados no arquivo `.env`:
    ```
    DB_HOST=localhost
    DB_USER=usuario
    DB_PASS=senha
    DB_NAME=bdLealCar
    JWT_SECRET=chaveSecreta
    ```

5. Inicie o servidor:
    ```bash
    php -S localhost:8000 -t public
    ```

## Segurança

Todas as rotas da API, exceto as de logins e cadastros, estão protegidas por autenticação baseada em token JWT. O algoritmo utilizado para a assinatura dos tokens é o HS512, garantindo uma camada adicional de segurança. Para acessar qualquer outra rota, o cliente deve fornecer um token JWT válido no cabeçalho da requisição:

- **Cabeçalho de Autenticação**:
    ```http
    Authorization: Bearer {seu_token_jwt}
    ```

## Rotas Protegidas da API

### Veículos

- **GET** `/veiculos` - Lista todos os veículos no estoque.
- **GET** `/veiculos/{id}` - Retorna os detalhes de um veículo específico.
- **POST** `/veiculos` - Adiciona um novo veículo ao estoque.
- **PUT** `/veiculos/{id}` - Atualiza as informações de um veículo existente.
- **DELETE** `/veiculos/{id}` - Remove um veículo do estoque.

### Carrocerias

- **GET** `/carrocerias` - Lista todos os tipos de carrocerias.
- **GET** `/carrocerias/{id}` - Retorna os detalhes de uma carroceria específica.
- **POST** `/carrocerias` - Adiciona uma nova carroceria.
- **PUT** `/carrocerias/{id}` - Atualiza as informações de uma carroceria existente.
- **DELETE** `/carrocerias/{id}` - Remove uma carroceria.

### Funcionários

- **GET** `/funcionarios` - Lista todos os funcionários.
- **GET** `/funcionarios/{id}` - Retorna os detalhes de um funcionário específico.
- **POST** `/funcionarios` - Adiciona um novo funcionário.
- **PUT** `/funcionarios/{id}` - Atualiza as informações de um funcionário existente.
- **DELETE** `/funcionarios/{id}` - Remove um funcionário.

### Cargos

- **GET** `/cargos` - Lista todos os cargos.
- **GET** `/cargos/{id}` - Retorna os detalhes de um cargo específico.
- **POST** `/cargos` - Adiciona um novo cargo.
- **PUT** `/cargos/{id}` - Atualiza as informações de um cargo existente.
- **DELETE** `/cargos/{id}` - Remove um cargo.

## Rotas não Protegidas da API

### Logins

- **POST** `/logins` - Autentica um usuário e gera um token JWT.

### Cadastros

- **POST** `/cadastros` - Cria um novo funcionário.

## Metodologia Scrum

Este projeto segue a metodologia Scrum, com iterações de desenvolvimento organizadas em Sprints de duas semanas. As atividades são gerenciadas através de reuniões diárias (Daily Standups), revisões de Sprint e retrospectivas. O objetivo é entregar incrementos funcionais da aplicação ao final de cada Sprint, garantindo um ciclo contínuo de melhoria e adaptação às necessidades do cliente.

## Contribuição

Se deseja contribuir para este projeto:

1. Faça um fork do repositório.
2. Crie uma nova branch com sua funcionalidade: `git checkout -b minha-funcionalidade`.
3. Faça o commit das suas alterações: `git commit -m 'Adiciona minha nova funcionalidade'`.
4. Faça o push para a branch: `git push origin minha-funcionalidade`.
5. Abra um Pull Request.

## Licença

Este projeto é licenciado sob a [MIT License](LICENSE).
