# iBanFirst
## Requirements
- php --version >= 7.1.33 (compatible with symfony 4.4)
- git
- composer
- yarn
- symfony


## Installation
1- Run commands : 
```shell script
git clone https://github.com/mery1988/ibanfirst.git
cd ibanfirst
composer install 
yarn install
yarn encore dev
```

2- Add access parameters login and password to connect to  the API under config/services.yaml : 

```yaml
    # access endPoint
    api.login : 
    api.password : 
```

3- Start the server
```shell script
symfony server:start
```
Now, navigate with the URL displayed in log messages in the console.

4- Unit tests:
Run this command to execute unit tests  

```shell script
./bin/phpunit
```

Namespace of Class to Test | Namespace Class test  
--- | --- 
src/Controller/Management/TransactionController.php | tests/Controller/Management/TransactionControllerTest.php
src/Helper/TransactionHelper.php | tests/Helper/TransactionHelperTest.php


