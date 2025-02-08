
1. Встановіть choco:
 

Set-ExecutionPolicy Bypass -Scope Process -Force; [System.Net.ServicePointManager]::SecurityProtocol = [System.Net.ServicePointManager]::SecurityProtocol -bor 3072; iex ((New-Object System.Net.WebClient).DownloadString('https://community.chocolatey.org/install.ps1'))



2. Встановіть PHP, Symfony CLI, Composer, Postman:


choco install php --version=8.1.27


choco install symfony-cli -y

choco install composer -y

choco install postman -y

choco install openssl -y 


3. Клонуйте проект:

git clone https://github.com/ipz234mam/API

 зайдіть в папку: API


4. Встановіть залежності:

composer install


composer require "lexik/jwt-authentication-bundle"


5. Згенеруйте ключ:

php bin/console lexik:jwt:generate-keypair


6. Запустіть сервер:

symfony serve


7. API доступне за посиланням

http://127.0.0.1:8000/api/v1/Staff

#  Документація Postman
**https://documenter.getpostman.com/view/41956826/2sAYX9kepY**