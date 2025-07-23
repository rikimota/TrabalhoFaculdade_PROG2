# Sistema de Controle de Estoque (PHP & Bootstrap)

Este é um projeto simples de sistema de controle de estoque desenvolvido em PHP, com estilização utilizando Bootstrap. Ele foi criado para simular um controle de estoque básico para comércio, atendendo a requisitos comuns como sistema de autenticação, integração com banco de dados e funcionalidades CRUD completas.

**Status do Projeto:** Este projeto não está mais sendo atualizado.

## Funcionalidades

O sistema oferece as seguintes funcionalidades principais:

* **Autenticação de Usuários:** Sistema de login para acesso seguro.
* **Gerenciamento de Produtos:**
    * Listagem de produtos com informações de nome, descrição, preço, quantidade e categoria.
    * Adição de novos produtos.
    * Edição de produtos existentes.
    * Exclusão de produtos.
    * Busca de produtos por nome e filtro por baixo estoque (quantidade menor que 5).
* **Gerenciamento de Categorias:**
    * Listagem de categorias.
    * Adição de novas categorias.
    * Edição de categorias existentes.
    * Exclusão de categorias.
* **Gerenciamento de Vendas:**
    * Registro de novas vendas com múltiplos produtos.
    * Listagem de vendas registradas com resumo (data, usuário, quantidade de itens, total).
    * Visualização de detalhes de vendas, incluindo os itens vendidos e seus subtotais.
    * Relatório de vendas por período.
    * Exportação de relatórios de vendas para Excel.
* **Gerenciamento de Usuários (Apenas Administradores):**
    * Listagem de usuários com busca por nome ou e-mail.
    * Criação de novos usuários com diferentes níveis de acesso (usuário comum ou administrador).
    * Edição de informações de usuários.
    * Exclusão de usuários.
* **Dashboard:** Painel de controle com resumos visuais de produtos por categoria e status de estoque (baixo, médio, OK).

## Tecnologias Utilizadas

* **Backend:** PHP
* **Banco de Dados:** MySQL (via PDO)
* **Frontend:** HTML, CSS, JavaScript
* **Framework CSS:** Bootstrap 5.3
* **Gráficos:** Chart.js

## Estrutura do Projeto

O projeto segue uma estrutura de pastas organizada para separar as responsabilidades:

├── public/ &nbsp;&nbsp;&nbsp;&nbsp; # Arquivos públicos acessíveis via web (páginas, CSS, JS) \
│   ├── &nbsp;. . .  <br> 
├── src/ &nbsp;&nbsp;&nbsp;&nbsp; # Código fonte da aplicação \
│   ├── Database/ \
│   │   └── Conexao.php &nbsp;&nbsp;&nbsp;&nbsp; # Classe para conexão com o banco de dados \
│   ├── Model/ \
│   │   ├── Categoria.php &nbsp;&nbsp;&nbsp;&nbsp; # Modelo para a entidade Categoria \
│   │   ├── ItemVenda.php &nbsp;&nbsp;&nbsp;&nbsp; # Modelo para a entidade ItemVenda \
│   │   ├── Produto.php &nbsp;&nbsp;&nbsp;&nbsp; # Modelo para a entidade Produto \
│   │   ├── Usuario.php &nbsp;&nbsp;&nbsp;&nbsp; # Modelo para a entidade Usuário \
│   │   └── Venda.php &nbsp;&nbsp;&nbsp;&nbsp; # Modelo para a entidade Venda \
│   └── View/ \
│       ├── dashboard-conteudo.php &nbsp;&nbsp;&nbsp;&nbsp; # Conteúdo HTML do dashboard \
│       ├── formulario-produto.php &nbsp;&nbsp;&nbsp;&nbsp; # Formulário reutilizável para produtos \
│       └── produtos-conteudo.php &nbsp;&nbsp;&nbsp;&nbsp; # Conteúdo HTML da lista de produtos \
└── LICENSE &nbsp;&nbsp;&nbsp;&nbsp; # Licença do projeto (MIT License) \
└── script_bd.sql &nbsp;&nbsp;&nbsp;&nbsp; # Script para criação das tabelas do banco de dados \

## Como Configurar e Rodar o Projeto

Para rodar este projeto em sua máquina, siga os passos abaixo:

### Pré-requisitos

* Servidor Web (Apache, Nginx, etc.)
* PHP 7.4 ou superior
* MySQL/MariaDB
* Composer (opcional, para gerenciamento de dependências futuras)

### Passos de Instalação

1.  **Clone o Repositório:**
    ```bash
    git clone https://github.com/rikimota/TrabalhoFaculdade_PROG2.git
    ```

2.  **Configurar o Banco de Dados:**
    * Crie um banco de dados MySQL com o nome `estoque_db`.
    * Importe o schema do banco de dados (arquivo `script_bd.sql`).
    * Atualize as credenciais do banco de dados no arquivo `src/Database/Conexao.php` se necessário.

3.  **Configurar o Servidor Web:**
    * Configure seu servidor web (Apache ou Nginx) para que a raiz do documento aponte para a pasta `public/` do projeto. Isso garante que apenas os arquivos públicos sejam acessíveis diretamente.

4.  **Acessar o Sistema:**
    * Abra seu navegador e acesse a URL configurada para o projeto (ex: `http://localhost/`). Você será redirecionado para a página de login.
