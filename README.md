# symfony-clients
Zadanie testowe
## Przygotowanie do instalacji
1. Przed instalacją w pliku .env należy ustawić dostęp do bazy danych:
    DATABASE_URL="mysql://uzytkownik:haslo@adres_serwera:3306/nazwa_bazy"
2. W pliku .env należy również podać email oraz hasło użytkownika:
    ADMIN_EMAIL=admin@admin.pl
    ADMIN_PASSWORD=ASDF@!D1@d

## Instalacja
Instalacja wykorzystuje doctrine fixtures bundle.
Po ustawieniu dostępu do bazy danych (linia 27) oraz danych administratora (linia 22, 23) należy w katalogu projektu wywołać komendę:
    php bin/console install
Pojawi się ostrzeżenie o wyczyszczeniu bazu i pytanie o potwierdzenie, które należy potwierdzić wpisując **yes**

## Logowanie i edycja danych
Po udanej instalacji można zalogować się w panelu danymi podanymi wcześniej w pliku .env.