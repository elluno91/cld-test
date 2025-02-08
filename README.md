# CLD TEST

## Readme

Nomor 1 : 

### Requirement
- PHP 8.2
- MySQL Server (Bisa menggunakan XAMPP)
- Composer (Bisa install terlebih dahulu di composer.org)

REST API dibuat menggunakan framework laravel berbasis PHP menggunakan composer. 

Untuk menjalankan nya diperlukan langkah langkah berikut ini

1. Pull Source Code
2. Buka terminal, dan arahkan direktori terminal ke tempat source code yang sudah di clone
3. Ketik "composer install", maka secara otomatis composer akan menginstall dependency yang diperlukan, ketika sudah selesai, jangan tutup terminal terlebih dahulu.
4. Buat Database Mysql 
5. Copy Paste .env.example menjadi .env 
6. Edit File .env dan tambahkan baris dibawah sendiri JWT_SECRET=b872605b9656ef9edf22fc868903172b020dfdccc1bdb3301ac9404804cffe0a
7. Edit File .env dan ubah parameter DB_CONNECTION menjadi "mysql"
8. Edit File .env dan ubah parameter DB_HOST dengan ip server mysql server
9. Edit File .env dan ubah parameter DB_DATABASE dengan nama database Mysql yang sudah dibuat
10. Edit File .env dan ubah parameter DB_USERNAME dengan nama user Mysql
11. Edit File .env dan ubah parameter DB_PASSWORD dengan password user Mysql
12. Buka kembali terminal, ketik "php artisan migrate", maka secara otomatis script akan membuat database dan table.
13. Ketik perintah "php artisan db:seed" untuk menjalankan script insert data demo ke database 
14. Ketik php artisan serve, tunggu hingga muncul http://127.0.0.1:8000 maka script php sudah bisa di akses.

Untuk menjalankan unit test yang sudah dibuat dapat dilakukan dengan cara

1. Buka terminal, dan arahkan direktori terminal ke tempat source code yang sudah di clone
2. Ketik php artisan test
3. Maka akan muncul hasil test di terminal.

Berikut cara menggunakan endpoint beserta parameter yang dapat digunakan.

1. Akses http://127.0.0.1:8000/api/Authenticate dengan method *POST* untuk mendapatkan token (membutuhkan parameter app_id dan app_secret dengan value 123456)
2. Setelah mendapatkan token, gunakan token ini pada header requrest untuk mengakses endpoint lainnya.

End Point: 
1. http://127.0.0.1:8000/api/room (GET, Request parameter : date_check_in, date_check_out, pax_count, Response : room_id)
2. http://127.0.0.1:8000/api/create-reservation (POST, Request parameter : customer_name, room_id, date_check_in, date_check_out, pax_count, Response : booking_code)
3. http://127.0.0.1:8000/api/cancel-reservation (POST, Request Parameter : booking_code)
4. http://127.0.0.1:8000/api/reservation (GET, Optional Request Parameter : booking_code, date_check_in, date_check_out)

Nomor 2:

```
<?php
function is_anagram($string1,$string2){
  $status = "false";
  if($string1 != "" && $string2 != ""){
   $string1 = str_split($string1);
   $string2 = str_split($string2);
   sort($string1);
   sort($string2);
   if($string1 === $string2){
   $status = "true";
   } 
  }
  return $status;
}

echo is_anagram("listen","silent")."<br/>";
echo is_anagram("hello","world")."<br/>";
?>
```

Nomor 3 :
- https://dartpad.dev/?id=cb4e91aca7e7fcf51b7dfe832bfa744b
- https://gist.github.com/elluno91/cb4e91aca7e7fcf51b7dfe832bfa744b
