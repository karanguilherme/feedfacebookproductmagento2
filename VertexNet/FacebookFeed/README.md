# Facebook Shooping Feed para  Magento 2

__Installation__
 
  1. Criar o Diretorio conforme informo abaixo
    `$ mkdir -p VertexNet/FacebookFeed` em  app/code 

  2. Faça o Upload de Todos os Arquivos para a pasta VertexNet/FacebookFeed 
 

  3. Execute o Comando do Magento 2 em seu SSH

    $ bin/magento setup:upgrade

  4. Limpe o Cache e generated

    $ bin/magento cache:clean


Vá para Loja > Atributo > Produtos 

crie o atributo facebook_condition com o tipo dropdown e adicione os tipos, new, used, refurbished.

Após isso adicione este atributo ao grupo de produtos em Loja > Atributo > Conjunto de Atributos


Faça o Login, vá até Loja > Configurações > Vertex Net > Facebook Feed

Para acessar o feed para facebook segue exemplo, onde www.website.com é o endereço de sua loja virtual : www.website.com/vertexnetfacebookfeed/
    
