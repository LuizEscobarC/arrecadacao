# ARRECADAÇÃO SISTEMA 📝

Obs: esse sistema só está público para portifólio e para apresentação para os avaliadores da Web.art. 
Espero que entendam meu readme.
Adendo: o erro ao acessar o link se refere ao https://   -  Não adicionei SLL ainda. É só entrar com http://

# A aplicação está on-line no link: 
```
http://ihsistemas.com/
```
#Credenciais de acesso:
Login: admin@admin.com
Senha: 12345678

## Caso você queira analisar o projeto em sua maquina, siga este passo a passo: 😁

<br>

### Clone o repositório😎

```
git@github.com:LuizEscobarC/arrecadacao.git
```

### Acesse o diretorio🤓

```
cd c:\xampp\htdocs\{folder}
```
### Instale as dependências�
```
c:\xampp\htdocs\{folder}\arrecadacao\composer.json
```
### Inicie a aplicação�
```
npm run serve
```
### A aplicação, por padrão, fica na porta:🤗

```
apache : 80, 443
MariaDB: 3306
```

### Restaure o Banco de dados

Importe todo o banco no seu gerenciador de banco de dados...
```
c:\xampp\htdocs\{folder}\arrecadacao\arrecadacao-dump-model.txt
```

### Configure o banco no arquivo de caminho:
```
c:\xampp\htdocs\{folder}\arrecadacao\source\Boot\Config.php
```
![image](https://user-images.githubusercontent.com/54407649/161636869-559a9faa-98fb-445f-9941-ca26d1f55b27.png)


### Configure a url base localhost no arquivo de caminho:
```
c:\xampp\htdocs\{folder}\arrecadacao\source\Boot\Config.php
```
![image](https://user-images.githubusercontent.com/54407649/161637076-249a1467-c7a6-4017-a5de-20883712780b.png)



## Imagems do projeto 💻

#### Dashboard coma  apresentação de um grafico realtime, onde se há um lançamento, no mesmo segundo se movimenta.
<img style="width: 600px; height: 300px" src="https://user-images.githubusercontent.com/54407649/161637293-7018eba8-b4d2-4c73-8d8f-14bf22d5f7d2.png">

##  No sistema a cadastros e listagens de INPUTS CORE do sistema, ou seja para rodar as features avançadas eles são uma dependencia como :

### usuário, horário, listas do horário a pagar (vinculado com as loja), lojas, centro de custos (padrões de saída ex: luz, aluguel, imposto de renda).

#### Desses básico temos o crud, telas de CREATE, UPDATE, DELETE E READ + tela de LISTAGEM de todas as linhas do BANCO DE DADOS.

#### Vou dar aqui uma das telas como exemplo:

<img style="width: 600px; height: 300px" src="https://user-images.githubusercontent.com/54407649/161638316-7a14bae0-9c25-4114-811a-b230ab506830.png">

## Tela de lançamento de fluxo de caixa: 
´´´
Receitas/despesas
´´´
### create

O horário é gerado com JS dinamicamente apartir do dia e do horário na tabela de horários.

<img style="width: 600px; height: 300px" src="https://user-images.githubusercontent.com/54407649/161638819-f7c83f2d-08db-41c0-b3da-7404742252e4.png">

### index/listagem

Com filtros avançados, totalização de valores (levando em conta negativos e positivos).

<img style="width: 600px; height: 300px" src="https://user-images.githubusercontent.com/54407649/161639070-3b600eed-79b1-4dcb-8cee-fa555f964809.png">

### update/ delete

<img style="width: 600px; height: 300px" src="https://user-images.githubusercontent.com/54407649/161639190-7d4bc8d7-14aa-49b3-9818-f2d1252fd1e7.png">

## Movimentação

```
Esse modulo práticamente automatiza todos os outros, ou seja, é o core da aplicação, ele faz calculos de diferença de lojas, reatualiza os valores de cada loja (loja com saldo negativo ou positivo + o saldo ), ele faz varias validacoes em relação a pagamentos, taxas de comissão. Campos dinamicos condicionais caso algum valor seja negativo ou caso exista valor para abate da loja e entre outras regras de negócio... (São muitas) Vou deixar algumas aqui em baixo:
```

Significado de cada valor. E explicação do cliente acima da regra de negócio:
![image](https://user-images.githubusercontent.com/54407649/161639617-b38c3e6e-6686-4c7a-84b5-aa35fb0d4b2a.png)

Simulação com planilha. Com a ausencia da regra do "premio" que é outra regra de negócio dinamica em JS que já está implementada caso o campo seja alimentado.
![image](https://user-images.githubusercontent.com/54407649/161639653-be51193e-affc-433c-87e4-6577f862e2c3.png)

simulação de regra negócio geral (calculos)
![image](https://user-images.githubusercontent.com/54407649/161639699-ba304925-7783-4f2b-af56-a9d99badc5aa.png)

## Telas:

### Create

Obs: Os campos de horario, e data são gerados automaticamente seguindo uma regra de negocio. Por padrão o dia de hoje e gerando os options do select da tabela do dia de hoje.

Adendo 1: ao escolher a loja, apresentara o saldo da loja, negativo ou não. com isso Só apresentará os valores da lista se houver listas cadastradas no horário e expecifico e na loja especifica, se não tiver. os calculos são feitos com 0 e apresenta a mensagem azul.

adendo 2:  os calculos são feitos em realtime com JS seguindo a regra de negócioq e calculos estabelecidos ali em cima e outros que não consegui anotar mas está implementado e redondo.

<img style="width: 600px; height: 300px" src="https://user-images.githubusercontent.com/54407649/161640266-5e9d6e39-b957-4810-9a72-2c84f5d841b3.png">

Os campos com risco são gerador dinamicamente via JS

<img style="width: 600px; height: 300px" src="https://user-images.githubusercontent.com/54407649/161640618-b3148919-c23e-41e8-a8e4-7d16a39d8707.png">

Caso haja premio:
![image](https://user-images.githubusercontent.com/54407649/161640722-60ce9f1b-2844-41f4-b3fe-c3b3573852ad.png)

Caso aceite o abate no valor da loja, assim o escritório pagará (só apreseta essa opção se a loja estiver negativada com o escritório):
![image](https://user-images.githubusercontent.com/54407649/161641046-348d278d-272b-469b-bc3b-c3bdf56cbc36.png)

Ao final apresenta o valor de abate na loja, caso ela seja negativa e queira pagar o premio e abater doque deve para o escritório: 

<img style="width: 600px; height: 300px" src="https://user-images.githubusercontent.com/54407649/161641680-b9aac4fa-0874-4db8-b2b1-5d12040bfb7c.png">

## Ao finalizar a movimentação:

Cria varior lançamentos automáticos dependendo de várias condições no fluxo de caixa como entrada ou saída (receita ou despesa).

Algumas delas:

```
Pagamento Despesa loja
pagamento premio loja
receita do acerto de loja
pagamento premio escritório
pagamento despesa escritório
entre outras...
```

## Não é possível apagar e nem editar

## TELA DE INDEX/LISTAGEM

<img style="width: 600px; height: 300px" src="https://user-images.githubusercontent.com/54407649/161641841-4eba2750-0b47-431e-8825-d0b65d7ad2bf.png">

TOTALIZAÇÕES DE VALORES: 
![image](https://user-images.githubusercontent.com/54407649/161641920-ce49b0d4-7a8c-46b5-9292-155d85ab7d49.png)

## VIZUALIZAR

Obs: esse é o relatório de movimento. nele só podemos ver e apagar, nunca editar.

<img style="width: 600px; height: 300px" src="https://user-images.githubusercontent.com/54407649/161642416-60e9f967-b1e9-41ab-9bd1-a4c183036fdd.png">

## Alguns dos lançamentos de fluxo de caixa automáticos desses exemplos de movimentação.

<img style="width: 600px; height: 300px" src="https://user-images.githubusercontent.com/54407649/161642732-faa967c3-efee-4289-9892ff791383dc53.png">




# API REST / RESTful

ESTÁ EM DESENVOLVIMENTO, LOGO SÓ EXISTEM ENDPOINT PARA O CRUD ("listar", criar, ler, atualizar e apagar) DE USUÁRIOS E LOJAS.

### Headers obrigatórios: 

```
headers:
email: admin@admin.com
password: 12345678 
```

endpoints: 

Encontrados no arquivo: index.php dentro da pasta API : ``` c:\xampp\htdocs\arrecadacao{ou nome que você deu a pasta que clonou}\api\index.php ```

![image](https://user-images.githubusercontent.com/54407649/161643189-b0ceb702-9c42-4975-86fd-dbd063bed362.png)


### No meu caso e nas minhas configs os endpoints são:  

```
localhost/arrecadacao/api/users  GET (index)
localhost/arrecadacao/api/users  POST (create)
localhost/arrecadacao/api/users/12  GET (read)
localhost/arrecadacao/api/users/12  PUT (update)
localhost/arrecadacao/api/users/12  DELETE (DELETE)

localhost/arrecadacao/api/stores  GET (index)
localhost/arrecadacao/api/stores  POST (create)
localhost/arrecadacao/api/stores/12  GET (read)
localhost/arrecadacao/api/stores/12  PUT (update)
localhost/arrecadacao/api/stores/12  DELETE (DELETE)

```

```
Os controladores ficam no caminho:  c:\xampp\htdocs\arrecadacao{ou nome que você deu a pasta que clonou}\source\App\Api
```
![image](https://user-images.githubusercontent.com/54407649/161643688-45cd8832-0c7f-4e5c-a175-34b5858a5414.png)



## Tecnologias utilizadas🦉

<ul>
    <li>PHP</li>
    <li>Design Patterns</li>
    <li>MVC sem framework</li>
    <li>MariaDB</li>
    <li>Javascript</li>
    <li>Jquery</li>
    <li>HTML5</li>
    <li>CSS3</li> 
</ul>

## Autor😃

### Luiz Paulo Escobal da Costa
### E-mail: luiz_escobar11@hotmail.com
### Linkedin: https://www.linkedin.com/in/luiz-paulo-escobal-da-costa-54b3501b2/
