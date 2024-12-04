Sistema para cadastrar reserva em churrascaria. 
Baseado no sistema que trabalho durante 20 anos e sempre foi feito no papel e durante meus estudos desenvolvi para facilitar. 

# Sistema de Gestão de Reservas - PHP

Este repositório contém um sistema completo de gerenciamento de reservas, desenvolvido em PHP, com funcionalidades para controle de usuários, cadastro de clientes, calendário interativo, geração de relatórios e administração de valores de serviços.

---

## Funcionalidades Principais

### 1. Gerenciamento de Reservas
- Cadastro, edição, exclusão e visualização de reservas.
- Informações detalhadas como:
  - Nome do cliente.
  - Data e horário.
  - Número de pessoas.
  - Tipo de evento (aniversário, confraternização, casamento, etc.).
  - Forma de pagamento e observações.
- Confirmação de reservas com envio de mensagens via WhatsApp.

### 2. Calendário Interativo
- Exibição mensal de reservas.
- Destaque para os dias com reservas já cadastradas.
- Navegação entre meses com botões "Anterior" e "Próximo".
- Link direto para visualizar ou adicionar novas reservas no dia selecionado.

### 3. Relatórios
- Geração de relatórios detalhados:
  - Por dia, horário ou intervalo de datas.
  - Quantidade total de pessoas reservadas.
  - Relatórios separados por almoço e jantar.
- Exportação para impressão.

### 4. Administração
- Controle de usuários:
  - Cadastro de novos usuários com validação.
  - Exibição e gerenciamento dos dados dos usuários.
- Alteração de valores de serviços (rodízios, eventos especiais).
- Gerenciamento de reservas canceladas com motivo de cancelamento.

### 5. Segurança
- Autenticação por senha com hash MD5 (pode ser adaptada para SHA ou bcrypt).
- Controle de acesso por sessões.
- Proteção contra SQL Injection utilizando `PDO` e consultas preparadas.

---

## Tecnologias Utilizadas
- **Backend**: PHP (PDO para acesso ao banco de dados).
- **Banco de Dados**: MySQL.
- **Frontend**:
  - HTML5 e CSS3.
  - JavaScript (jQuery e integração com WhatsApp).
  - Bootstrap para estilização responsiva.
- **Servidor**: Apache (compatível com XAMPP, WAMP ou servidores em nuvem).

---

## Requisitos

### 1. Servidor
- PHP 7.4 ou superior.
- MySQL 5.7 ou superior.
- Servidor Apache configurado com `mod_rewrite`.

### 2. Instalação
1. Clone o repositório:
   ```bash
   git clone https://github.com/seu-usuario/sistema-reservas.git
   ```
2. Configure o banco de dados:
   1. Crie um banco de dados MySQL.
   2. Importe o arquivo `database.sql` incluído no projeto:
      ```bash
      mysql -u usuario -p senha < database.sql
      ```
3. Atualize as configurações no arquivo `config.php`:
   ```php
   define('DB_HOST', 'localhost');
   define('DB_NAME', 'nome_do_banco');
   define('DB_USER', 'usuario');
   define('DB_PASS', 'senha');
   ```

4. Instale e configure o servidor local (XAMPP ou WAMP).

---

## Uso

### 1. Inicialização
- Acesse o sistema no navegador:
  ```
  http://localhost/sistema-reservas
  ```

### 2. Login
- Utilize as credenciais padrão:
  - **Usuário**: admin
  - **Senha**: admin123 (recomenda-se alterar no primeiro acesso).

### 3. Funcionalidades Disponíveis
- **Adicionar uma nova reserva**:
  - Acesse "Nova Reserva" no menu.
  - Preencha os campos obrigatórios e clique em "Salvar".
- **Navegar no calendário**:
  - Clique em qualquer data para ver ou adicionar reservas.
- **Geração de relatórios**:
  - Acesse o menu "Relatórios" e filtre os dados por data ou horário.

---

## Estrutura do Projeto

```
/sistema-reservas
│
├── /assets              # Arquivos estáticos (CSS, JS, imagens)
├── /templates           # Templates HTML
├── /config.php          # Configurações do sistema
├── /database.sql        # Script para criação do banco de dados
├── /modules             # Funções e classes reutilizáveis
├── /routes              # Arquivos para rotas específicas
└── /index.php           # Entrada principal do sistema
```

---

## Customização

### Estilos
- Arquivos CSS personalizados podem ser encontrados em `/assets/css/style.css`.

### Mensagens de WhatsApp
- Altere o template das mensagens no arquivo `functions.php`, função `enviarMensagemWhatsApp()`.

---

## Contribuição

Contribuições são bem-vindas! Para contribuir:
1. Faça um fork do repositório.
2. Crie um branch para suas alterações:
   ```bash
   git checkout -b minha-feature
   ```
3. Envie suas alterações:
   ```bash
   git push origin minha-feature
   ```
4. Abra um Pull Request.

---

## Licença

Este projeto é distribuído sob a licença MIT. Consulte o arquivo `LICENSE` para mais detalhes.

---

## Contato

Em caso de dúvidas ou suporte, entre em contato:
- **E-mail**: rossettoti@gmail.com
- **WhatsApp**: +55 21 996169369
