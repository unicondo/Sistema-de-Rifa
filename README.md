
# Sistema Completo de Rifa Online

Este repositório contém o código completo de um sistema de rifa online, que inclui funcionalidades de registro de compras, gerenciamento de números, pesquisa e sorteio. Abaixo, você encontrará explicações detalhadas sobre o funcionamento do código, como implementar no servidor e como utilizar as funcionalidades de cada módulo.

## Sumário
1. [Introdução e Estrutura do Projeto](#introducao-e-estrutura-do-projeto)
2. [Código do Painel de Administração](#codigo-do-painel-de-administracao)
3. [Código do Sorteio](#codigo-do-sorteio)
4. [Código de Pesquisa](#codigo-de-pesquisa)
5. [Implementação no Servidor](#implementacao-no-servidor)
6. [Requisitos e Configurações](#requisitos-e-configuracoes)

---

## Introdução e Estrutura do Projeto

O sistema de rifa online permite que administradores registrem compras, gerenciem números comprados, pesquisem compradores e números, e realizem sorteios de forma automatizada. Ele é composto por três principais módulos de código: painel de administração, sorteio e pesquisa.

## Explicação dos Códigos

### Código do Painel de Administração

# Rifa Online - Sistema de Sorteio

Este projeto é um sistema de rifa online que permite aos usuários selecionar números, visualizar números comprados, calcular o preço total e iniciar a compra através do WhatsApp.

## Funcionalidades

- **Seleção de números**: Os usuários podem clicar nos números disponíveis para selecioná-los.
- **Visualização dos números comprados**: Números já adquiridos são desativados automaticamente.
- **Cálculo de preço**: O preço total é calculado com base nos números selecionados.
- **Integração com WhatsApp**: Os usuários podem iniciar a compra diretamente pelo WhatsApp com os números selecionados.

## Estrutura do Projeto

- **index.php**: Página principal da rifa.
- **Banco de Dados**: Conectado através de PHP (`mysqli`), com os números comprados armazenados em uma tabela chamada `rifa_compras`.

## Configuração

1. **Clonar o Repositório**:
   ```bash
   git clone https://github.com/seu-usuario/seu-repositorio.git
   cd seu-repositorio
   ```

2. **Configuração do Banco de Dados**:
   - Crie uma base de dados e uma tabela chamada `rifa_compras` com uma coluna `numeros` que armazene os números comprados como uma string separada por vírgulas.

3. **Configuração do Arquivo PHP**:
   - Substitua as seguintes variáveis de conexão com o banco de dados em `index.php`:
     ```php
     $conn = new mysqli("SERVIDOR", "USUÁRIO", "SENHA", "BANCO-DE-DADOS");
     ```

4. **Ajustes no JavaScript**:
   - Para alterar o preço dos números, edite a linha:
     ```javascript
     const pricePerNumber = 10;
     ```

5. **Contato pelo WhatsApp**:
   - Atualize o link de contato em `index.php`:
     ```html
     <a href="https://wa.me/55SEU-NÚMERO" class="btn btn-primary">Contato via WhatsApp</a>
     ```
   - Também modifique no trecho de JavaScript:
     ```javascript
     $('#buy-button').attr('href', `https://wa.me/55SEU-NÚMERO?text=Quero%20comprar%20os%20números:%20${selectedNumbers.join(', ')}%20-%20Total:%20R$%20${totalPrice.toFixed(2)}`);
     ```

## Customização

- **Quantidade de Números na Rifa**:
  - Alterar a quantidade de números disponíveis:
    ```php
    for ($i = 1; $i <= 50; $i++) {
    ```
  - Modifique `50` para o número desejado.

- **Imagens e Estilo**:
  - Substitua as imagens do carrossel pelo caminho desejado e personalize o CSS conforme necessário.

## Como Funciona

1. O usuário acessa a página e visualiza a grade de números.
2. Clicando em um número, ele é selecionado e mostrado na barra inferior junto com o preço total.
3. Ao clicar em "Comprar", o usuário é redirecionado para o WhatsApp com uma mensagem pré-preenchida contendo os números selecionados.

## Requisitos

- PHP 7.4 ou superior
- Servidor MySQL
- Bootstrap 5.3 para estilização
- jQuery 3.6.0 para manipulação de eventos

## Exemplo de Uso

![Exemplo de Interface](link-para-screenshot.png)

## Licença

Este projeto é licenciado sob a [MIT License](LICENSE).

---

Feito com ❤️ por Bruno Ferreira Alves. Para mais detalhes, visite [meu site](https://allvz.com.br/).


---


# Código de Sorteio - Explicação

Este código realiza o sorteio de participantes de uma rifa com base nos números comprados armazenados em um banco de dados.

## Funcionamento do Código

1. **Conexão com o Banco de Dados**:
   - O código estabelece uma conexão com o banco de dados MySQL usando o seguinte comando:
     ```php
     $conn = new mysqli("SERVIDOR", "USUÁRIO", "SENHA", "BANCO-DE-DADOS");
     ```
     Certifique-se de substituir as variáveis de conexão com os valores corretos.

2. **Verificação de Erros de Conexão**:
   - Se a conexão falhar, o código exibe uma mensagem de erro e interrompe a execução:
     ```php
     if ($conn->connect_error) {
         die("Erro na conexão com o banco de dados: " . $conn->connect_error);
     }
     ```

3. **Consulta ao Banco de Dados**:
   - O código faz uma consulta para obter o nome e os números comprados da tabela `rifa_compras`:
     ```php
     $result = $conn->query("SELECT nome, numeros FROM rifa_compras");
     ```

4. **Processamento dos Resultados**:
   - Os números comprados são processados e armazenados em um array `$compras` que contém pares de nome e número:
     ```php
     while ($row = $result->fetch_assoc()) {
         $numeros = explode(",", $row['numeros']);
         foreach ($numeros as $numero) {
             $compras[] = ['nome' => $row['nome'], 'numero' => trim($numero)];
         }
     }
     ```

5. **Sorteio Aleatório**:
   - O array `$compras` é embaralhado usando a função `shuffle()` para garantir que os números sejam sorteados de forma aleatória:
     ```php
     shuffle($compras);
     ```

6. **Seleção dos Ganhadores**:
   - Os três primeiros elementos do array embaralhado são selecionados como vencedores:
     ```php
     $primeiro = $compras[0];
     $segundo = $compras[1];
     $terceiro = $compras[2];
     ```

7. **Exibição dos Resultados**:
   - Os nomes e os números dos ganhadores são exibidos de forma segura, utilizando `htmlspecialchars()` para evitar problemas de injeção de código:
     ```php
     echo "<strong>1º Lugar:</strong> " . htmlspecialchars($primeiro['nome']) . " - Número: " . htmlspecialchars($primeiro['numero']) . "<br>";
     echo "<strong>2º Lugar:</strong> " . htmlspecialchars($segundo['nome']) . " - Número: " . htmlspecialchars($segundo['numero']) . "<br>";
     echo "<strong>3º Lugar:</strong> " . htmlspecialchars($terceiro['nome']) . " - Número: " . htmlspecialchars($terceiro['numero']) . "<br>";
     ```

8. **Fechamento da Conexão**:
   - A conexão com o banco de dados é encerrada após a execução do script:
     ```php
     $conn->close();
     ```

## Considerações de Segurança

- **Proteção Contra Injeção de Código**: O uso de `htmlspecialchars()` garante que os nomes e números sejam exibidos de forma segura.
- **Validação de Dados**: É importante garantir que os dados no banco de dados estejam limpos e sem duplicatas.

## Requisitos

- PHP 7.4 ou superior
- Servidor MySQL

## Customização

- **Quantidade de Ganhadores**: Para alterar o número de vencedores, basta modificar as linhas que definem os vencedores e exibir os resultados.
- **Critérios de Sorteio**: Se você quiser alterar a lógica do sorteio (por exemplo, sorteio ponderado), será necessário ajustar o código.

---

Feito com ❤️ por Bruno Ferreira Alves. Para mais detalhes, visite [meu site](https://allvz.com.br/).


---


# Código de Pesquisa - Explicação

Este código realiza a pesquisa de compradores e números em uma rifa a partir de um termo inserido pelo usuário. Ele exibe os resultados em formato de tabela caso existam correspondências.

## Funcionamento do Código

1. **Conexão com o Banco de Dados**:
   - O código estabelece uma conexão com o banco de dados MySQL usando o seguinte comando:
     ```php
     $conn = new mysqli("SERVIDOR", "USUÁRIO", "SENHA", "BANCO-DE-DADOS");
     ```
     Certifique-se de substituir `SERVIDOR`, `USUÁRIO`, `SENHA` e `BANCO-DE-DADOS` com os valores corretos para o seu ambiente.

2. **Verificação de Erros de Conexão**:
   - Se a conexão falhar, o código interrompe a execução e exibe uma mensagem de erro:
     ```php
     if ($conn->connect_error) {
         die("Erro na conexão com o banco de dados: " . $conn->connect_error);
     }
     ```

3. **Captura do Termo de Pesquisa**:
   - O termo de pesquisa é capturado da URL usando `$_GET['termo']` e tratado com `real_escape_string()` para evitar injeção de SQL:
     ```php
     $termo = isset($_GET['termo']) ? $conn->real_escape_string($_GET['termo']) : '';
     ```

4. **Consulta ao Banco de Dados**:
   - A consulta SQL busca registros na tabela `rifa_compras` que contenham o termo no nome ou nos números comprados:
     ```php
     $query = "SELECT nome, numeros FROM rifa_compras WHERE nome LIKE '%$termo%' OR numeros LIKE '%$termo%'";
     ```

5. **Exibição dos Resultados**:
   - Se a consulta retornar resultados, eles são exibidos em uma tabela com as colunas "Nome" e "Números Comprados":
     ```php
     if ($result->num_rows > 0) {
         echo "<table class='table table-striped'><thead><tr><th>Nome</th><th>Números Comprados</th></tr></thead><tbody>";
         while ($row = $result->fetch_assoc()) {
             echo "<tr><td>" . htmlspecialchars($row['nome']) . "</td><td>" . htmlspecialchars($row['numeros']) . "</td></tr>";
         }
         echo "</tbody></table>";
     } else {
         echo "<p class='text-center'>Nenhum resultado encontrado.</p>";
     }
     ```
   - Os valores são protegidos com `htmlspecialchars()` para evitar vulnerabilidades de injeção de código.

6. **Fechamento da Conexão**:
   - A conexão com o banco de dados é fechada após a execução da pesquisa:
     ```php
     $conn->close();
     ```

## Considerações de Segurança

- **Proteção Contra Injeção de SQL**: O uso de `real_escape_string()` garante que o termo de pesquisa seja tratado de forma segura.
- **Proteção Contra Injeção de Código**: `htmlspecialchars()` é usado para proteger os dados exibidos na página.

## Requisitos

- PHP 7.4 ou superior
- Servidor MySQL

## Customização

- **Filtros de Pesquisa**: Para adicionar mais filtros ou campos de pesquisa, modifique a consulta SQL e inclua mais colunas conforme necessário.
- **Estilo**: O estilo da tabela pode ser ajustado alterando as classes CSS.

## Exemplo de Uso

1. O usuário acessa a página com um termo de pesquisa na URL, por exemplo: `pagina.php?termo=123`.
2. O script exibe uma tabela com os resultados que correspondem ao termo.

---

Feito com ❤️ por Bruno Ferreira Alves. Para mais detalhes, visite [meu site](https://allvz.com.br/).


---

