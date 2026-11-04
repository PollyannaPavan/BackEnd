# LISTA DE EXERCÍCIOS DE FIXAÇÃO E PRÁTICA - SESSÕES, COOKIES e AUTENTICAÇÃO DE USUÁRIOS

## Parte A: Exercícios Teóricos de Fixação

### Protocolo Stateless
1. O que significa afirmar que o protocolo HTTP é "stateless" e por que o mecanismo de cookies/sessões é necessário para aplicações modernas?

> O HTTP é considerado stateless porque não mantém automaticamente informações sobre as requisições anteriores. Os cookies e as sessões permitem manter informações entre requisições, como o login do usuário e os itens de um carrinho de compras.

### Cookies vs. Sessões
2. Qual é a diferença entre os dados armazenados em um cookie e os dados mantidos no array superglobal `$_SESSION`? Qual dos dois apresenta maior segurança para informações confidenciais?

> Os cookies armazenam dados no navegador do usuário, enquanto as sessões armazenam dados no servidor e utilizam um identificador para reconhecer o usuário. As sessões geralmente são mais seguras para informações confidenciais, pois os dados não ficam diretamente armazenados no navegador.

### A Flag HttpOnly
3. Explique detalhadamente como a configuração `httponly => true` impede o sequestro de sessão (Session Hijacking) mesmo se a aplicação possuir uma vulnerabilidade de Cross-Site Scripting (XSS).

> A configuração `httponly => true` impede que o JavaScript acesse diretamente o cookie de sessão. Assim, mesmo que exista uma vulnerabilidade XSS, um script malicioso não consegue ler facilmente o identificador da sessão. Porém, essa configuração não impede todos os ataques XSS, pois o script ainda pode realizar ações em nome do usuário.

### A Flag SameSite
4. Qual é a finalidade do atributo `SameSite=Lax` em cookies e contra qual tipo de ataque ele atua?

> O atributo `SameSite=Lax` limita o envio de cookies em requisições iniciadas por outros sites, ajudando a proteger contra ataques CSRF (Cross-Site Request Forgery), nos quais um atacante tenta fazer o navegador realizar ações sem a intenção do usuário. Ele ainda permite o envio de cookies em algumas navegações, como ao acessar um site por meio de um link.

### Ataque de Fixação de Sessão
5. O que é o ataque de Session Fixation e por que é mandatório executar `session_regenerate_id(true)` imediatamente após o login do usuário?

> O Session Fixation acontece quando um atacante tenta fazer a vítima utilizar um identificador de sessão conhecido por ele. Após o login, `session_regenerate_id(true)` gera um novo identificador e solicita a exclusão dos dados da sessão antiga, dificultando que o atacante reutilize o identificador anterior. Essa medida ajuda a proteger a conta do usuário.

### MD5 e Rainbow Tables
6. Por que é considerado negligência técnica armazenar senhas com algoritmos rápidos como `MD5` ou `SHA256`? Como as Rainbow Tables operam contra esses hashes?

> MD5 e SHA256 são algoritmos rápidos, permitindo testar muitas senhas em pouco tempo durante ataques. As Rainbow Tables utilizam cálculos pré-computados para relacionar hashes a possíveis senhas, facilitando a descoberta de senhas fracas. Por isso, é recomendado utilizar algoritmos próprios para senhas, como Argon2id ou bcrypt.

### O Papel do Salt
7. O que é o Salt criptográfico e por que ele garante que dois usuários com a mesma senha possuam hashes completamente distintos no banco de dados?

> O salt é um valor aleatório adicionado ao processo de geração do hash da senha. Como cada usuário recebe um salt diferente, a mesma senha normalmente gera hashes diferentes. Isso dificulta a identificação de senhas iguais e o uso de tabelas pré-computadas.

### Argon2id vs Bcrypt
8. Por que o algoritmo Argon2id é considerado superior ao Bcrypt na proteção contra ataques de força bruta realizados por placas de vídeo (GPUs) e circuitos ASIC?

> O Argon2id permite configurar o uso de memória e o tempo de processamento, tornando os ataques de força bruta mais caros, principalmente em GPUs e ASICs. O bcrypt também é seguro quando configurado corretamente, mas o Argon2id oferece maior controle sobre o uso de memória, dificultando ataques em larga escala.

### Timing Attacks
9. Por que a verificação de senhas deve ser feita com `password_verify()` em vez do operador de comparação comum `===`?

> A função `password_verify()` verifica se a senha informada corresponde ao hash armazenado, sendo própria para hashes gerados por `password_hash()`. Ela realiza a verificação de forma segura, enquanto o operador `===` apenas compara valores e não substitui o processo adequado de verificação de senhas.