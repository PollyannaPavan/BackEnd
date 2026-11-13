# LISTA DE EXERCÍCIOS DE FIXAÇÃO E PRÁTICA - INTRODUÇÃO AO FRAMEWORK LARAVEL 11.X E ARTISAN CLI

## Parte A: Questões Teóricas de Fixação

### Evolução Arquitetural
1. Quais foram os principais aprendizados técnicos obtidos na construção do MVC artesanal da Semana 12 que permitiram que você compreendesse o Laravel 11 com facilidade?

> Os principais aprendizados foram a separação entre Model, View e Controller, a organização dos arquivos e a divisão de responsabilidades. Isso facilitou entender como o Laravel organiza e gerencia uma aplicação.

### A Nova Arquitetura do Laravel 11
2. O que mudou na estrutura de inicialização do Laravel 11 em relação às versões anteriores (Laravel 9 e 10)? Onde fica o novo ponto central de configuração?

> No Laravel 11, a estrutura ficou mais simples, com menos arquivos de configuração. O principal ponto de inicialização e configuração passou a ser o arquivo `bootstrap/app.php`.

### Padrão 12-Factor App
3. Por que é uma violação grave de segurança gravar senhas de banco de dados diretamente dentro do arquivo config/database.php em vez de utilizar o arquivo .env?

> Porque deixar senhas diretamente no código pode expor informações importantes da aplicação. O arquivo `.env` permite guardar essas informações separadamente e não deve ser enviado para o Git.

### O Papel da APP_KEY
4. Qual é o risco técnico de alterar a APP_KEY de uma aplicação que já está em produção há meses com usuários reais cadastrados?

> Alterar a `APP_KEY` pode fazer com que dados criptografados anteriormente não possam mais ser lidos. Também pode invalidar sessões e cookies dos usuários, fazendo com que precisem entrar novamente no sistema.

### Isolamento de Diretórios
5. Por que um servidor web de produção (Nginx/Apache) deve apontar rigorosamente para a pasta public/ e nunca para a raiz do projeto Laravel?

> Porque a pasta `public/` contém os arquivos que podem ser acessados pelo navegador. Apontar para a raiz do projeto pode expor arquivos importantes, como configurações, senhas e códigos internos da aplicação.

### Artisan CLI
6. Qual é a diferença prática entre executar um script PHP procedural com php arquivo.php e executar comandos através do php artisan?

> O comando `php arquivo.php` executa um arquivo PHP diretamente. Já o `php artisan` utiliza as ferramentas do Laravel para realizar tarefas como criar controllers, migrations, models e executar comandos de manutenção.

### O Arquivo .env.example
7. Por que manter o arquivo .env.example sempre atualizado é indispensável para o sucesso de equipes de desenvolvimento que utilizam Git?

> Porque ele serve como modelo de configuração para outros desenvolvedores. Assim, todos conseguem configurar o projeto com as mesmas variáveis necessárias, sem precisar compartilhar senhas ou informações privadas.

### Motor de Templates Blade
8. Qual é a diferença entre a diretiva Blade {{ $variavel }} e a diretiva {!! $variavel !!} em termos de proteção contra ataques Cross-Site Scripting (XSS)?

> A diretiva `{{ $variavel }}` exibe o conteúdo escapando caracteres especiais, ajudando a evitar ataques XSS. Já `{!! $variavel !!}` exibe o conteúdo sem essa proteção, podendo executar códigos HTML ou JavaScript maliciosos.

### Responsabilidade do Controller
9. Em uma aplicação profissional com Laravel, por que um método de Controller deve ser curto (≤ 20 linhas) e não deve conter consultas SQL cruas ou regras de negócio extensas?

> Porque o Controller deve apenas organizar o fluxo da aplicação e chamar as partes responsáveis por cada tarefa. Manter os métodos curtos facilita a leitura, os testes e a manutenção do código.

### A Ferramenta Tinker
10. Para que serve o comando php artisan tinker e em quais situações ele economiza tempo do desenvolvedor durante testes de laboratório?

> O comando `php artisan tinker` permite executar comandos PHP diretamente no ambiente do Laravel. Ele ajuda a testar Models, consultar dados, criar registros e verificar funcionalidades sem precisar criar uma página ou arquivo PHP para cada teste.