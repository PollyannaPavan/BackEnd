<?php
declare(strict_types=1);

// Classe que conecta ao banco
final class ConexaoBanco {

    // Guarda a conexão
    private static ?PDO $instancia = null;

    // Impede criar objetos diretamente
    private function __construct(){}

    // Impede copiar o objeto
    private function __clone(): void{}

    // Impede recriar o objeto por serialização
    public function __wakeup(): void{
        throw new \Exception("Desserialização não permitida para Singleton");
    }

    // Retorna a conexão com o banco
    public static function obterConexao(string $caminhoConfig): PDO {

        // Cria a conexão se ela ainda não existir
        if(self::$instancia === null){
            $config = self::carregarArquivoConfig($caminhoConfig);
            self::$instancia = self::estabelecerConexao($config);
        }

        return self::$instancia;
    }

    // Lê os dados do arquivo de configuração
    private static function carregarArquivoConfig(string $caminho): array{

        // Verifica se o arquivo existe
        if(!file_exists($caminho)){
            throw new \RuntimeException("Arquivo de configuração não encontrado em {$caminho}");
        }

        $dados = parse_ini_file($caminho, true);

        // Verifica se a seção database existe
        if($dados === false || !isset($dados["database"])){
            throw new \RangeException("Seção [database] ausente no arquivo de configuração");
        }

        return $dados["database"];
    }

    // Cria a conexão com o banco
    private static function estabelecerConexao(array $cfg): PDO{

        // Monta os dados da conexão
        $dsn = sprintf(
            "%s:host=%s;port=%s;dbname=%s",
            $cfg["db_driver"],
            $cfg["db_host"],
            $cfg["db_port"],
            $cfg["db_name"],
        );

        // Define as opções do PDO
        $opcoes = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
            PDO::ATTR_TIMEOUT            => 5
        ];

        return new PDO($dsn, $cfg["db_user"], $cfg["db_pass"], $opcoes);
    }
}
?>