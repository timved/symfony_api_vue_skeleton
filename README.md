1. Генерим и сохраняем секрет для ключей <br>
`cd api` <br>
`php -r "echo 'JWT_PASSPHRASE=' . md5('enter some secret');" >> .env.local` <br>

2. Генерим ключи, с использованием секрета из конфига `.env.local` <br>
`mkdir -p config/jwt` <br>
`openssl genpkey -out config/jwt/private.pem -aes256 -algorithm rsa -pkeyopt rsa_keygen_bits:4096` <br>
`openssl pkey -in config/jwt/private.pem -out config/jwt/public.pem -pubout`
