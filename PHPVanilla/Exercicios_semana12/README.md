# LISTA DE EXERCÍCIOS DE FIXAÇÃO E PRÁTICA - MVC

## Parte A: Questões Teóricas de Fixação

### O Colapso do Código Espaguete
1. Explique por que manter consultas SQL, regras de validação e marcação HTML no mesmo arquivo inviabiliza a manutenção e a escalabilidade de um sistema corporativo?

> Porque o código fica desorganizado e difícil de manter. Qualquer alteração no banco de dados, nas regras ou no visual pode afetar outras partes do sistema, dificultando as correções e futuras melhorias.

### A Tríade MVC
2. Qual é a função específica de cada um dos três componentes do padrão MVC (Model, View, Controller)? Qual deles deve conter as regras de negócio?

> Model: responsável pelos dados e pelas regras de negócio. View: responsável pela parte visual do sistema, ou seja, o que aparece para o usuário. Controller: recebe as requisições, chama o Model e envia os dados para a View. As regras de negócio devem ficar no Model.

### O Papel do Composer
3. Diferencie o papel do arquivo `composer.json` do arquivo `composer.lock`. Por que a pasta `vendor/` nunca deve ser versionada no Git?

> O `composer.json` informa quais bibliotecas o projeto utiliza. Já o `composer.lock` registra as versões exatas dessas bibliotecas. A pasta `vendor/` não deve ser enviada para o Git porque contém as dependências instaladas, que podem ser baixadas novamente usando o Composer.

### A Especificação PSR-4
4. Como o Autoloader PSR-4 consegue localizar o arquivo `src/Controllers/ProdutoController.php` a partir do namespace `App\Controllers\ProdutoController`?

> O PSR-4 utiliza o namespace para localizar o arquivo correspondente. O namespace `App\Controllers` é relacionado à pasta `src/Controllers`, e o nome `ProdutoController` indica o arquivo `ProdutoController.php`.

### Front Controller
5. Qual é a principal vantagem de segurança ao centralizar todas as requisições em `public/index.php` e isolar os arquivos-fonte do projeto fora da raiz pública da web?

> A principal vantagem é impedir que usuários acessem diretamente arquivos internos do sistema, como configurações e códigos-fonte. Assim, todas as requisições passam por um único ponto de entrada.

### Roteador HTTP
6. Como o objeto Roteador diferencia uma requisição `GET /produtos` de uma requisição `POST /produtos`?

> Ele verifica o método HTTP e o endereço da requisição. Mesmo que o endereço seja igual, GET e POST são tratados como rotas diferentes.

### Injeção de Dados na View
7. Como a função nativa `extract()` atua no método `renderizar()` do Controller para disponibilizar variáveis para as Views?

> A função `extract()` transforma as chaves de um array em variáveis PHP. Assim, os dados enviados pelo Controller podem ser utilizados diretamente dentro da View.

### Segurança contra XSS nas Views
8. Por que todas as saídas dinâmicas exibidas em tabelas e formulários devem ser envolvidas com `htmlspecialchars()`?

> Porque essa função transforma caracteres especiais em entidades HTML, impedindo que códigos maliciosos sejam executados na página. Isso ajuda a proteger o sistema contra ataques XSS.

### Single Responsibility no Controller
9. Por que um Controller nunca deve conter comandos SQL com strings concatenadas ou conexões diretas via `new PDO()`?

> Porque o Controller deve ser responsável por receber as requisições e controlar o fluxo do sistema. As consultas e conexões com o banco devem ficar em classes próprias, facilitando a organização, a manutenção e a segurança do código.

### Transição para o Framework Laravel
10. Identifique quais conceitos aprendidos neste MVC artesanal são exatamente os mesmos utilizados pelo Laravel 11.

> Os conceitos são a separação entre Model, View e Controller, o uso de rotas HTTP, o Front Controller, o Composer, o Autoload PSR-4, a passagem de dados para as Views e a organização do código em responsabilidades diferentes. O Laravel utiliza esses mesmos conceitos, mas oferece ferramentas prontas para facilitar o desenvolvimento.