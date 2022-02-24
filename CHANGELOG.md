# Changelog 

## Unreleased

## v2.0.0 - 2022-02-23

- bump module and respective composer.json to version to 2.0.0
- more comments have been added to explain my reasoning and thoughts
- removal of the Helper and needed methods placed in the Plugin Invoice class
- bug fix where the Block class name resolution wasn't in string literal using the ::class keyword, but it had a missing backslash 

## v1.0.1 - 2022-02-23

- pushed critical changes
- fixed CS obvious issue with class CreateCmsBlock() with non PascalCase

## v1.0.0 - 2022-02-22

- TASK-001 - Create composer needed files
- TASK-002 - I'm going to try this using an after plugin interceptor for the core method Magento\Sales\Model\Order\Pdf\Invoice::getPdf()
- TASK-003 - Create CMS Block static content through data patch
- TASK-004 - Refactor module namespace
