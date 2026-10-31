# Handoff: zmiany w motywie `delsenter` i wtyczce `ihumbak-chat` pod LSCache

Data: 2026-10-31. Status: **nic nie wdrożono**. Numery linii pochodzą z kopii na produkcji (`sites/delsenter.no/html/wp-content/`). Motyw i wtyczka mają osobne źródła poza tym repozytorium, więc linie w źródle mogą się różnić.

## Po co ta zmiana

Strony na delsenter.no są cache'owane w LSCache z TTL 7 dni, ale dziś praktycznie żyją krócej niż dobę, bo cache jest czyszczony szerzej niż pojedyncze produkty (główny podejrzany: nocny import, jeszcze niepotwierdzony). Planujemy ograniczyć to czyszczenie, żeby poprawić hit-rate.

Skutek uboczny: w HTML strony zostaje nonce zapisany w chwili jej wygenerowania. Nonce WordPressa żyje najwyżej 24 h, a OLS nie obsługuje ESI, więc nie da się go wstrzyknąć osobno. Po ograniczeniu czyszczenia goście będą dostawać strony z wygasłym nonce, a dwa miejsca w kodzie wtedy się zepsują.

**Te zmiany muszą być na produkcji, zanim ograniczymy czyszczenie cache.**

## Zmiana 1: motyw `delsenter`, odświeżanie nonce koszyka

### Problem

| Endpoint | Nonce | Skutek na starej stronie |
|---|---|---|
| `delsenter_add_to_cart` (`inc/cart.php:691`) | brak, celowo | działa |
| `delsenter_remove_from_cart` (`inc/cart.php:714`) | `check_ajax_referer` | 403 |
| `delsenter_restore_cart_item` (`inc/cart.php:748`) | `check_ajax_referer` | 403 |
| `delsenter_set_quantity` (`inc/cart.php:776`) | `check_ajax_referer` | 403 |

Scenariusz awarii:

1. Gość otwiera produkt z cache, wygenerowany np. 3 dni temu. `delsenterCart.nonce` (`inc/cart.php:852`) jest już nieważny.
2. Dodaje produkt. Żądanie przechodzi, bo dodawanie nie ma nonce, a strona się nie przeładowuje.
3. W szufladzie koszyka usuwa pozycję albo zmienia ilość. JS wysyła stary `config.nonce` (`assets/js/cart.js:576`, `:746`).
4. Serwer odpowiada 403, `post()` rzuca wyjątek (`cart.js`, `if (!response.ok)`), a klient widzi błąd i nie może zmienić koszyka bez przeładowania.

Kolejne strony tego gościa są już poprawne: przy niepustym koszyku LSCache nie cache'uje strony (`set_nocache_hard` w `thirdparty/woocommerce.cls.php:602`).

### Co zmienić

Każda odpowiedź koszyka ma nieść świeży nonce, a JS ma go przejmować.

`inc/cart.php`, w `delsenter_cart_send_response()`, w tablicy przekazywanej do `wp_send_json`:

```php
'nonce' => wp_create_nonce( 'delsenter-cart' ),
```

`assets/js/cart.js`, w `post()`, jako jedno wspólne miejsce dla wszystkich żądań:

```js
return response.json();
```

zastąpić przez:

```js
return response.json().then(function (result) {
        if (result && result.nonce) {
                config.nonce = result.nonce;
        }

        return result;
});
```

### Dlaczego tak

- Nonce dostaje się w odpowiedzi na `add_to_cart`, czyli akurat wtedy, gdy strona z cache zaczyna go potrzebować. Nie ma dodatkowego żądania PHP na każdej stronie.
- Nonce `delsenter-cart` jest ten sam przed sesją WooCommerce i po jej utworzeniu. Filtr `maybe_update_nonce_user_logged_out` podmienia użytkownika tylko dla akcji zaczynających się od `woocommerce` (`class-wc-session-handler.php:615`).
- Odpowiedzi to POST na `wc-ajax`, więc nie powinny trafiać do page cache. Sprawdzić to w teście akceptacyjnym.

### Czego nie zmieniać

- Nie dodawać nonce do `delsenter_add_to_cart`: to jedyna akcja wywoływana z każdej strony z cache.
- Nie zdejmować nonce z usuwania, przywracania i zmiany ilości. Decyzja z `README.md:2126` pozostaje w mocy.

### Znane ograniczenie

Zostaje rzadki przypadek: gość ma pozycje w koszyku, a mimo to dostaje stronę z cache, np. po wygaśnięciu cookie `_lscache_vary` (2 dni). Pierwsze usunięcie da wtedy błąd, a przeładowanie strony to naprawi. Świadomie nie dokładamy dla tego osobnego endpointu.

## Zmiana 2: wtyczka `ihumbak-chat`, usunięcie `X-WP-Nonce`

### Problem

Widżet wysyła nonce `wp_rest` zapisany w HTML strony (`includes/class-ihumbak-chat.php:103`, `public/js/ihumbak-widget.js:224`). WordPress sprawdza ten nagłówek przed wywołaniem endpointu, także dla gości. Zmierzone na produkcji 2026-10-31 żądaniem do `/wp-json/wp/v2/types/post`:

```
bez nagłówka                  → 200
X-WP-Nonce: deadbeef00        → 403 rest_cookie_invalid_nonce
```

Na stronie starszej niż ok. 24 h wiadomość z czatu zostanie odrzucona.

### Co zmienić

1. `public/js/ihumbak-widget.js:224`: usunąć linię `'X-WP-Nonce': settings.nonce` (to jedyne użycie `settings.nonce`).
2. `includes/class-ihumbak-chat.php:103`: usunąć klucz `nonce` z `wp_localize_script`.
3. Podnieść wersję do `1.6.1` w nagłówku wtyczki i w `IHUMBAK_CHAT_VERSION` (`ihumbak-chat.php:6` i `:23`), bo tą wersją oznaczane są zasoby. `package.json` zostaje bez zmian, bo `build.sh` bierze wersję z nagłówka wtyczki.
4. Usunąć wzmianki o weryfikacji nonce z `README.md:100`, `docs/SPECIFICATION.md:44`, `:109`, `:281` i `docs/PROJECT-SUMMARY.md:118`.
5. Dodać wpis `1.6.1` do `docs/CHANGELOG.md`.
6. Zbudować paczkę przez `build.sh`.

Punkty 1 i 2 trafiają do jednego wydania. Źródłem tej wtyczki jest repozytorium `wp-ihumbak-chat-simple`.

### Dlaczego to bezpieczne

- `send-message` ma `permission_callback => __return_true`, więc nonce nie daje mu żadnych uprawnień.
- reCAPTCHA po stronie serwera zostaje bez zmian (`class-ihumbak-rest-api.php:83-89`, klucz jest ustawiony na produkcji). Limitu żądań nie liczymy tu jako ochrony, bo można go obejść nagłówkami: osobne zgłoszenie [#1](https://github.com/michalstaniecko/wp-ihumbak-chat-simple/issues/1).
- Wiadomość od zalogowanego użytkownika będzie miała `post_author` równy `0`, bo REST bez nonce traktuje go jak gościa. Akceptujemy to: wtyczka nie odczytuje autora Wiadomości, a nadawcę identyfikuje po `ihumbak_email`.

### Kolejność wdrożenia

Stary `ihumbak-widget.js` z nowym HTML wysłałby `X-WP-Nonce: undefined` i dostał 403. Zamykają to wersja `1.6.1` w adresie zasobu i obowiązkowe pełne czyszczenie LSCache po wdrożeniu. Nowy JS ze starym HTML działa bez zmian.

### Testy akceptacyjne wtyczki

1. Gość wysyła Wiadomość: żądanie bez `X-WP-Nonce`, odpowiedź 200, wpis w panelu. Poczta na stage nie wychodzi, więc nie sprawdzamy jej tam.
2. To samo dla zalogowanego użytkownika.
3. Po wdrożeniu na produkcji: strona z nagłówkiem `x-litespeed-cache: hit` wysyła Wiadomość bez `X-WP-Nonce`, a odpowiedź to 200.

## Zmiana 3: `README.md` motywu, nieaktualny opis

`README.md:2069-2070` mówi, że ta instalacja nie używa full-page cache. To nieprawda: LSCache cache'uje strony publiczne.

Poprawić opis `wc-cart-fragments`: skrypt nadal nie jest potrzebny, bo LSCache nie cache'uje stron gościa z niepustym koszykiem, więc licznik i szuflada są renderowane na serwerze. Jeśli test 3 poniżej wykaże zły licznik, skrypt trzeba przywrócić, a opis dostosować do wyniku.

## Wdrożenie

1. Wdrożyć zmiany 1–3 zwykłą ścieżką, nie edytować ich tylko na serwerze, bo kolejne wdrożenie mogłoby je cofnąć.
2. **Po wdrożeniu raz wyczyścić cały LSCache.** Strony z cache wskazują stare, zoptymalizowane pliki JS, które inaczej mogłyby zostać do końca TTL. Dla koszyka kolejność jest bezpieczna w obie strony: stary JS ignoruje nowe pole `nonce`, a nowy działa z nonce zapisanym na stronie. Dla wtyczki patrz „Kolejność wdrożenia” w Zmianie 2.
3. Dać znać administratorowi serwera. Dopiero wtedy może ruszyć ograniczenie czyszczenia cache.

## Testy akceptacyjne

Stage (`stage.delsenter.no`) nie ma LSCache, więc testy 1–2 symulują stary nonce. Testy 3–4 wymagają produkcji.

1. **Koszyk ze starym nonce (stage).** Otwórz produkt, w konsoli ustaw `delsenterCart.nonce = 'deadbeef00'`, dodaj produkt, a potem w szufladzie zmień ilość i usuń pozycję. Oczekiwane: bez 403. Kontrola: bez dodawania, tylko z podmienionym nonce i pozycją już w koszyku, usunięcie ma dać 403.
2. **Widżet.** Patrz „Testy akceptacyjne wtyczki” w Zmianie 2.
3. **Koszyk z cache (produkcja, w nowej sesji przeglądarki).** Otwórz stronę z nagłówkiem `x-litespeed-cache: hit`, dodaj produkt i przejdź na inny produkt. Oczekiwane: licznik i szuflada pokazują pozycję, strona nie jest serwowana z publicznego cache. Następnie usuń pozycję i przejdź dalej: licznik ma wrócić do zera. Płatności nie wykonywać.
4. **Odpowiedzi koszyka (produkcja).** Odpowiedź `wc-ajax=delsenter_add_to_cart` zawiera pole `nonce` i nie ma `x-litespeed-cache: hit`. Po tygodniu od ograniczenia czyszczenia logi nie powinny pokazywać 403 na `delsenter_remove_from_cart`, `delsenter_set_quantity` ani `ihumbak-chat/v1/send-message`.
