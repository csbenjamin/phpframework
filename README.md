# PHP framework com injeção de dependencia

Este framework tem o objetivo de fazer algo semelhante ao que o angular faz, só que no lado do
servidor usando php.


## Como usar

Clone este repositório.

Nossa pasta principal de trabalho será a controllers. Nela definiremos toda a regra de negócio do
nosso sistema.

se queremos ter uma url /foo/bar, devemos criar uma pasta foo dentro de controllers e um arquivo 
bar.php dentro da pasta criada. No arquivo bar.php definiremos a class foo_bar que extenderá a class 
controller. Nela deveremos implementar no mínimo o método public execute(). Caso queiramos receber
algum parâmetro, por exemplo, algo como /foo/bar/:id, então o método execute deve ser declarado 
public execute($id). Se queremos que o id seja opicional basta fazermos public execute($id = "defaultValue").

Dentro do método "execute" faremos tudo o que precisamos. Se no nosso código precisamos de uma class externa,
Colocaremos esta class dentro da pasta components. O arquivo deverá ser algo como {nomedaclass}.class.php.
Agora na nossa nossa class foo_bar definimos o método __construct e como parametro colocamos $nomedaclass.
No corpo do __construct fazemos algo como $this->nomedaclass = $nomedaclass. Agora podemos usar a instancia
da class dentro do método "execute" atravez da referencia $this->nomedaclass.

Dentro do método execute temos disponível $this->response e $this->request. Dê uma olhada nos arquivos 
framework/lib/response.php e framework/lib/request.php para ver o que eles fazem. Nos exemplos podemos
ver algumas aplicações do objeto $this->response e $this->request.

Para fazer o test da class foo_bar, dê uma olhada no exemplo dentro no arquivo  test/controllers/helloTest.php

Dentro da pasta framework não mexeremos. Nela temos a base do nosso sistema. As pastas framework/components, 
framework/controllers, framework/libmock, framework/test e o arquivo framework/bootstrap.mock.php são apenas
para test do framework e será usado apenas por quem está desenvolvendo o framework. Pontanto não é obrigatório
te-las no seu sistema.

Temos alguns exemplos dentro da pasta components e controllers. Dê uma olhada nestes arquivos para entender melhor
como funciona o sistema.


## TODO

Traduzir este arquivo para inglês.