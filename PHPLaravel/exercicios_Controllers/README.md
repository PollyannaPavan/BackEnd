# LISTA DE EXERCÍCIOS DE FIXAÇÃO E PRÁTICA - Controllers e Routes no LARAVEL

## Parte A: Questões Teóricas de Fixação

### Semântica RESTful
1. Por que uma ação de exclusão de dados nunca deve ser implementada através de uma requisição HTTP do tipo GET? Explique o risco associado a robôs de busca.

> Porque o método GET deve ser utilizado apenas para consultar dados, não para excluí-los. Se a exclusão fosse feita por GET, robôs de busca poderiam acessar o link automaticamente e acabar excluindo informações sem autorização.

### Idempotência
2. O que significa o conceito de idempotência no protocolo HTTP e por que o método PUT é considerado idempotente enquanto o POST não é?

> Idempotência significa que executar uma mesma requisição várias vezes produz o mesmo resultado final. O PUT é idempotente porque atualizar um registro várias vezes com os mesmos dados não altera o resultado. Já o POST pode criar vários registros se for enviado repetidamente.

### Resource Controllers
3. Quais são as 7 ações canônicas geradas pelo comando `php artisan make:controller --resource` e qual é a responsabilidade de cada uma?

> As 7 ações são: index, que lista os registros; create, que exibe o formulário de cadastro; store, que salva um novo registro; show, que exibe os detalhes; edit, que exibe o formulário de edição; update, que atualiza um registro; e destroy, que exclui um registro.

### Injeção de Dependências
4. Como o Service Container do Laravel entrega automaticamente uma instância de Request ao método do controller sem que tenhamos que instanciá-la com new Request()?

> O Laravel utiliza o Service Container para identificar as dependências necessárias e entregar automaticamente uma instância de Request ao controller. Assim, não precisamos criar o objeto manualmente com `new Request()`.

### Method Spoofing
5. Por que os navegadores web não conseguem enviar requisições nativas de PUT e DELETE através de formulários HTML convencionais? Como o Laravel contorna essa limitação?

> Os formulários HTML convencionais aceitam apenas os métodos GET e POST. Para contornar isso, o Laravel utiliza a diretiva `@method('PUT')` ou `@method('DELETE')`, que informa ao framework qual método HTTP deve ser utilizado.

### Ataques CSRF
6. O que é um ataque do tipo Cross-Site Request Forgery e como a diretiva Blade @csrf impede que sites maliciosos forjem requisições em nome do usuário?

> CSRF é um ataque em que um site malicioso tenta realizar ações em nome de um usuário que já está autenticado em outro site. A diretiva `@csrf` gera um token de segurança que o Laravel verifica para confirmar se a requisição é legítima.

### Constraints de Rota
7. Para que servem métodos de restrição como whereNumber('id') ou whereAlphaNumeric('codigo') e qual código de status HTTP o Laravel retorna quando o formato é violado?

> Servem para limitar os valores que podem ser recebidos em uma rota, evitando formatos inválidos. Por exemplo, `whereNumber('id')` aceita apenas números. Quando o formato não corresponde à restrição, o Laravel retorna o erro HTTP 404 (Not Found).

### Rotas Nomeadas
8. Cite duas vantagens corporativas de utilizar a função auxiliar route('clientes.show', $id) nas views em vez de escrever strings estáticas como "/clientes/{$id}".

> Duas vantagens são facilitar a manutenção, pois podemos alterar o endereço da rota sem modificar todos os links das views, e evitar erros de digitação, deixando o código mais organizado e fácil de entender.

### Single Responsibility no Controller
9. Por que a validação de formulários com $request->validate() ou Form Requests deve ser mantida enxuta dentro do controller para respeitar o limite de 20 linhas por função?

> Porque o controller deve ser responsável por controlar o fluxo da aplicação, e não concentrar todas as regras de validação. Manter as funções pequenas facilita a leitura, manutenção e reutilização do código.

### A Ferramenta route:list
10. Como o comando php artisan route:list auxilia equipes multidisciplinares a documentar e auditar os endpoints disponíveis em uma API ou aplicação web?

> O comando `php artisan route:list` mostra todas as rotas cadastradas, seus métodos HTTP, endereços, nomes e controllers responsáveis. Isso ajuda a equipe a visualizar, documentar e verificar os endpoints disponíveis na aplicação.