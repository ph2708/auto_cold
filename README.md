# 🚗 Auto Cold - Sistema de Gestão para Auto Elétrica & Ar Condicionado

Sistema completo desenvolvido em **Laravel 12 (PHP 8.2+)** com banco de dados **MySQL 8.0** e armazenamento local permanente de fotos (`storage/app/public`), perfeito para deploy em hospedagens como **Hostinger** (cPanel / hPanel / VPS) ou servidores dedicados.

Focado em oficinas automotivas (**Auto Cold** / **Juliano Ribeiro**), com fluxo simplificado em 1 tela, rastreamento de compras externas (Mercado Livre, Shopee), ordens de serviço, upload de fotos do veículo antes/durante/depois, emissão de orçamentos e painel administrativo com personalização visual em tempo real.

---

## 🌟 Principais Recursos & Módulos

### 1. 🌐 Landing Page Pública & Branding Dinâmico (`/` e `/site`)
- Página de apresentação moderna e responsiva com foco em conversão para WhatsApp.
- Mostruário de serviços (Ar Condicionado, Alternadores, Motores de Partida, Injeção Eletrônica e Diagnóstico).
- Mapa interativo com localização da oficina (`R. Monte Alegre, 271 - Jardim Liberdade, Itumbiara - GO`).
- **Painel de Configurações Administrativas (`/settings`)**:
  - Upload de logomarca (clara e escura) com controle de altura do logo via slider.
  - Customização do nome do negócio, slogan, cores primária/secundária, WhatsApp e endereço sem tocar no código.

### 2. 📋 Nova Entrada & Abertura de OS em 1 Tela (`/service_orders/create`)
- **Sem listas suspensas travadas**: Digitação direta da placa do veículo e do nome do cliente.
- **Autopreenchimento inteligente**: Ao digitar a placa, localiza o histórico do carro e dados do cliente instantaneamente.
- **Botão 1-Clique "Cliente Avulso"**: Atendimento rápido de balcão sem burocracia.
- **Alternador de Modo**: Criação com 1 clique de **Orçamento Prévio** ou **Ordem de Serviço (OS)** em execução.

### 3. 📸 Fotos do Veículo & Laudo Técnico
- Upload de múltiplas fotos em alta resolução por etapa (**Antes da Manutenção**, **Diagnóstico na Bancada** e **Após Conclusão / Teste de Entrega**).
- Armazenamento em disco local (`storage/app/public/os_photos`).

### 4. 🛒 Cotação em Tempo Real vs Peças do Estoque Físico
- **Cotação Externa (Lojas / Mercado Livre)**: Permite inserir itens cotados em tempo real no orçamento sem exigir cadastro prévio e sem descontar do estoque físico.
- **Uso de Estoque Físico**: Baixa automática e atômica com Kardex quando uma peça do estoque da oficina é aplicada.
- **Impressão Pronta**: Layout limpo e profissional para impressão e geração de PDF de Orçamento e Ordem de Serviço com valores detalhados e termos de garantia.

### 5. 🚚 Rastreamento de Peças a Chegar (`/purchase_orders`)
- Controle de compras feitas na internet (Mercado Livre, Shopee, Distribuidoras).
- Apenas digite a peça e o código de rastreio para acompanhar prazos e chegada na oficina.
- Botão rápido de recebimento que incorpora o item ao estoque ou à OS correspondente.

### 6. 👥 Clientes, CPF Opcional & Veículos (`/customers`)
- Campo de CPF **opcional** com verificação em tempo real (AJAX) para alertar duplicidades sem travar novos cadastros.
- Cadastro e histórico unificado por cliente e por placa.

### 7. 📊 Painel Geral & Gestão Financeira (`/dashboard`)
- Faturamento bruto e recebíveis em aberto.
- Total investido em compras/encomendas no mês.
- DRE e margem de ganho real da oficina (Mão de Obra 100% líquida + margem sobre peças).

---

## 🚀 Como Fazer o Deploy (Hostinger, cPanel ou Hospedagem Compartilhada)

### 1. Criar o Banco de Dados MySQL & Importar o Dump
1. No painel da sua hospedagem (Hostinger hPanel, cPanel ou phpMyAdmin), crie o banco de dados MySQL e usuário com todas as permissões.
2. Acesse o **phpMyAdmin**, selecione o banco de dados e clique na aba **Importar**.
3. Selecione o arquivo [database/auto_cold_dump.sql](database/auto_cold_dump.sql) e clique em **Executar / Importar** (o banco já vem com todas as tabelas, permissões e dados iniciais).

### 2. Configurar o `.env`
No gerenciador de arquivos (File Manager) ou via SSH, crie/edite o arquivo `.env`:
```env
APP_NAME="Auto Cold"
APP_ENV=production
APP_KEY=base64:T2ZnXLrdHiGLfj/Wr2B3CbyA38prUFErQZ/iNLsSdts=
APP_DEBUG=false
APP_TIMEZONE=America/Sao_Paulo
APP_URL=https://seudominio.com.br

APP_LOCALE=pt_BR
APP_FALLBACK_LOCALE=pt_BR

LOG_CHANNEL=stack
LOG_STACK=single

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nome_do_banco_criado
DB_USERNAME=usuario_do_banco
DB_PASSWORD=sua_senha_segura

SESSION_DRIVER=file
CACHE_STORE=file
QUEUE_CONNECTION=sync
FILESYSTEM_DISK=public
```

### 3. Deploy via File Manager (Sem Terminal / Hospedagem Compartilhada)
1. O projeto já inclui o arquivo [index.php](index.php) e [.htaccess](.htaccess) na raiz, permitindo funcionamento imediato tanto em `/htdocs` quanto em `/public_html`.
2. Todos os uploads de logotipo e fotos são automaticamente espelhados para `storage/logos` e `storage/os_photos` na pasta pública acessível pelo navegador.

### 4. Deploy via SSH (Hostinger VPS / cPanel Terminal)
Caso tenha acesso ao terminal SSH:
```bash
composer install --optimize-autoloader --no-dev
php artisan storage:link
chmod -R 775 storage bootstrap/cache
```

---

## 🔑 Acesso Administrativo Inicial

| Papel / Perfil | Usuário (`username` ou `email`) | Senha | Descrição de Acesso |
| :--- | :--- | :--- | :--- |
| **Administrador** | `admin` ou `admin@autocold.com.br` | `admin123` | Acesso total a todas as telas, configurações, ordens de serviço, catálogo e relatórios |
| **Eletricista** | `carlos.eletrica` | `eletrica123` | Consulta catálogo, especificações elétricas e baixa de peças |
| **Estoquista** | `marcos.estoque` | `estoque123` | Entrada/saída de mercadorias, fornecedores e almoxarifado |

*(Recomenda-se alterar a senha do Administrador após o primeiro acesso na tela de Usuários).*

---

## 🧪 Testes Automatizados

Para rodar a suíte completa de testes automatizados com PHPUnit:
```bash
php artisan test
```
