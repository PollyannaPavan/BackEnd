# LISTA DE EXERCÍCIOS DE FIXAÇÃO E PRÁTICA - SESSÕES, COOKIES e AUTENTICAÇÃO DE USUÁRIOS


## Parte A: Exercícios Teóricos de Fixação

### Protocolo Stateless
1. O que significa afirmar que o protocolo HTTP é "stateless" e por que o mecanismo de cookies/sessões é necessário para aplicações modernas?

> 

### Cookies vs. Sessões
2. Qual é a diferença entre os dados armazenados em um cookie e os dados mantidos no array superglobal `$_SESSION`? Qual dos dois apresenta maior segurança para informações confidenciais?

> 


### A Flag HttpOnly
3.  Explique detalhadamente como a configuração `httponly => true` impede o sequestro de sessão (Session Hijacking) mesmo se a aplicação possuir uma vulnerabilidade de Cross-Site Scripting (XSS).

> 

### A Flag SameSite
4. Qual é a finalidade do atributo `SameSite=Lax` em cookies e contra qual tipo de ataque corporativo ele atua?


> 
### Ataque de Fixação de Sessão
5. O que é o ataque de Session Fixation e por que é mandatório executar `session_regenerate_id(true)` imediatamente após o login do usuário?
> 

### MD5 e Rainbow Tables
6. Por que é considerado negligência técnica armazenar senhas com algoritmos rápidos como `MD5` ou `SHA256`? Como as Rainbow Tables operam contra esses hashes?

> 
### O Papel do Salt
7.  O que é o Salt criptográfico e por que ele garante que dois usuários com a mesma senha possuam hashes completamente distintos no banco de dados?

> 
### Argon2id vs Bcrypt
8. Por que o algoritmo Argon2id é considerado superior ao Bcrypt na proteção contra ataques de força bruta realizados por placas de vídeo (GPUs) e circuitos ASIC?

> 

### Timing Attacks
9. Por que a verificação de senhas deve ser feita com `password_verify()` em vez do operador de comparação comum `===`?

> 