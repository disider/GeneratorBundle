# GeneratorBundle

## Create related entity files
  1. Extract common structs and components
  -  From an input *entity.orm.xml*:
    1. generate Entity.php
    -  generate EntityForm.php
    -  generate EntityController.php
    -  generate templates twig (new, edit, index, etc)
    -  generate features
    -  generate feature contexts
    -  generate fixtures
    -  add entity form processor to services
    -  add entity repositoty to services
    -  add entity actions in menu
    -  add entity translations
    -  add configuration parameters in DependencyInjection (like page_size, etc)

## Inital symfony setup
  1. create error pages
  -  vagrant files
  -  deploy files
  -  add FOSUserBundle
  -  add MenuBuilder
