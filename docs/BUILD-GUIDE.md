# 🛠️ Instrukcje Budowania i Testowania - iHumbak Chat Plugin

## 📋 Spis Treści
1. [Przygotowanie środowiska](#przygotowanie-środowiska)
2. [Budowanie pluginu](#budowanie-pluginu)
3. [Testowanie lokalne](#testowanie-lokalne)
4. [Rozwiązywanie problemów](#rozwiązywanie-problemów)

---

## 🔧 Przygotowanie środowiska

### Wymagania
- **Bash** (dostępny domyślnie na macOS/Linux)
- **PHP 7.4+** (do walidacji składni)
- **ZIP** (do tworzenia archiwów)
- **Lokalna instalacja WordPress** (do testowania)

### Sprawdzenie wymagań
```bash
# Sprawdź wersję PHP
php -v

# Sprawdź czy zip jest zainstalowany
which zip

# Sprawdź czy bash jest dostępny
bash --version
```

---

## 📦 Budowanie Pluginu

### Metoda 1: Budowanie z instalacją (Zalecana)

**Krok 1:** Przejdź do katalogu pluginu
```bash
cd /Users/michalstaniecko/Projects/woocommerce-plugins/wp-ihumbak-chat/wp-ihumbak-chat-simple
```

**Krok 2:** Uruchom skrypt budowania z instalacją
```bash
./build.sh --install
```

**Krok 3:** Podaj ścieżkę do WordPress
```
Przykład: /Users/michalstaniecko/Local Sites/mysite/app/public/wp-content/plugins
```

**Krok 4:** Plugin zostanie:
- ✅ Zwalidowany (sprawdzenie składni PHP)
- ✅ Zbudowany (utworzenie struktury)
- ✅ Spakowany (plik ZIP w katalogu `dist/`)
- ✅ Zainstalowany (skopiowany do WordPress)

---

### Metoda 2: Tylko budowanie (bez instalacji)

**Budowanie ZIP:**
```bash
./build.sh
```

**Rezultat:**
- Zostanie utworzony plik: `dist/ihumbak-chat-1.0.0.zip`
- Możesz go zainstalować ręcznie przez WordPress Admin

---

### Metoda 3: Czyszczenie plików

**Usunięcie plików tymczasowych:**
```bash
./build.sh --clean
```

Usuwa:
- Katalog `build/`
- Katalog `dist/`
- Wszystkie pliki `.zip`

---

## 🧪 Testowanie Lokalne

### Metoda A: Symlink (Zalecana dla development)

**Zalety:**
- Zmiany w plikach od razu widoczne w WordPress
- Nie trzeba rebuilować za każdym razem
- Idealne do testowania i developmentu

**Krok 1:** Uruchom skrypt testowy
```bash
./test-local.sh
```

**Krok 2:** Podaj ścieżkę do WordPress plugins
```
Przykład: /Users/michalstaniecko/Local Sites/mysite/app/public/wp-content/plugins
```

**Krok 3:** Skrypt utworzy symlink

**Krok 4:** Aktywuj plugin w WordPress Admin

---

### Metoda B: Kopiowanie (Dla testów produkcyjnych)

**Ręczne kopiowanie:**
```bash
# Zbuduj plugin
./build.sh

# Skopiuj do WordPress
cp -r build/ihumbak-chat /path/to/wordpress/wp-content/plugins/
```

---

## 🎯 Typowe Scenariusze Użycia

### Scenariusz 1: Pierwsza instalacja i test
```bash
# 1. Przejdź do katalogu pluginu
cd /Users/michalstaniecko/Projects/woocommerce-plugins/wp-ihumbak-chat/wp-ihumbak-chat-simple

# 2. Utwórz symlink do WordPress
./test-local.sh
# Podaj ścieżkę: /Users/yourname/Local Sites/mysite/app/public/wp-content/plugins

# 3. Aktywuj w WordPress Admin → Plugins
# 4. Skonfiguruj w iHumbak Chat → Settings
```

---

### Scenariusz 2: Development z live reload
```bash
# 1. Użyj symlinka (jak w Scenariuszu 1)
./test-local.sh

# 2. Edytuj pliki w swoim edytorze
# 3. Odśwież stronę WordPress - zmiany są widoczne natychmiast

# Przykład: Zmień kolor widgetu
# Edytuj: public/css/ihumbak-widget.css
# Odśwież stronę - nowy kolor widoczny od razu!
```

---

### Scenariusz 3: Przygotowanie do dystrybucji
```bash
# 1. Zbuduj finalny ZIP
./build.sh

# 2. Plik będzie w dist/ihumbak-chat-1.0.0.zip
# 3. Możesz go udostępnić lub zainstalować na produkcji
```

---

### Scenariusz 4: Testy na czystej instalacji WordPress
```bash
# 1. Wyczyść poprzednie buildy
./build.sh --clean

# 2. Zbuduj i zainstaluj
./build.sh --install
# Podaj ścieżkę do czystej instalacji WordPress

# 3. Przetestuj od zera
```

---

## 📍 Typowe Ścieżki WordPress

### Local by Flywheel / LocalWP
```
/Users/TWOJA_NAZWA/Local Sites/NAZWA_STRONY/app/public/wp-content/plugins
```

### MAMP
```
/Applications/MAMP/htdocs/NAZWA_STRONY/wp-content/plugins
```

### Custom Sites folder
```
/Users/TWOJA_NAZWA/Sites/NAZWA_STRONY/wp-content/plugins
```

### Docker (depends on setup)
```
/var/www/html/wp-content/plugins
```

---

## 🔍 Co robi skrypt `build.sh`?

### Opcje dostępne:

```bash
./build.sh              # Zbuduj plugin ZIP
./build.sh --install    # Zbuduj i zainstaluj do WordPress
./build.sh --clean      # Usuń pliki buildu
./build.sh --help       # Pokaż pomoc
```

### Szczegółowy proces budowania:

1. **Walidacja PHP** ✅
   - Sprawdza składnię wszystkich plików `.php`
   - Zatrzymuje się jeśli znajdzie błędy

2. **Przygotowanie struktury** 📁
   - Tworzy katalog `build/ihumbak-chat/`
   - Kopiuje tylko niezbędne pliki

3. **Pakowanie** 📦
   - Tworzy `dist/ihumbak-chat-1.0.0.zip`
   - Wykluczy pliki deweloperskie (.git, docs, etc.)

4. **Instalacja** (opcjonalnie) 🚀
   - Kopiuje do WordPress
   - Nadpisuje starą wersję jeśli istnieje

---

## 🔍 Co robi skrypt `test-local.sh`?

### Proces:

1. **Sprawdzenie lokalizacji** 📍
   - Weryfikuje czy jesteś w katalogu pluginu

2. **Utworzenie symlinka** 🔗
   - Tworzy symboliczny link do WordPress
   - Usuwa stary jeśli istnieje

3. **Gotowe do testowania** ✅
   - Wszystkie zmiany w plikach są od razu widoczne

---

## 🐛 Rozwiązywanie Problemów

### Problem: "Permission denied" przy uruchamianiu skryptu
```bash
# Rozwiązanie: Ustaw uprawnienia wykonywania
chmod +x build.sh
chmod +x test-local.sh
```

---

### Problem: "PHP command not found"
```bash
# Rozwiązanie 1: Zainstaluj PHP
brew install php  # macOS z Homebrew

# Rozwiązanie 2: Użyj PHP z MAMP
export PATH="/Applications/MAMP/bin/php/php8.1.0/bin:$PATH"
```

---

### Problem: "zip command not found"
```bash
# macOS
brew install zip

# Linux (Debian/Ubuntu)
sudo apt-get install zip
```

---

### Problem: Plugin nie pojawia się w WordPress
```bash
# Sprawdź ścieżkę
ls /path/to/wordpress/wp-content/plugins/ihumbak-chat

# Sprawdź uprawnienia
chmod -R 755 /path/to/wordpress/wp-content/plugins/ihumbak-chat

# Odśwież stronę Plugins w WordPress Admin
```

---

### Problem: Zmiany nie są widoczne
```bash
# Jeśli używasz symlinka:
# 1. Sprawdź czy symlink istnieje
ls -la /path/to/wordpress/wp-content/plugins/ihumbak-chat

# 2. Wyczyść cache WordPress
# WP Admin → Settings → Permalinks → Save Changes

# 3. Wyczyść cache przeglądarki
# Chrome/Firefox: Cmd+Shift+R (macOS) lub Ctrl+Shift+R (Windows)
```

---

### Problem: Błędy składni PHP
```bash
# Sprawdź składnię pojedynczego pliku
php -l ihumbak-chat.php

# Sprawdź wszystkie pliki
find . -name "*.php" -exec php -l {} \;
```

---

## 📝 Checklist przed testowaniem

- [ ] PHP 7.4+ zainstalowane
- [ ] Lokalna instalacja WordPress działa
- [ ] Znasz ścieżkę do `wp-content/plugins`
- [ ] Skrypty mają uprawnienia wykonywania (`chmod +x`)
- [ ] Jesteś w katalogu pluginu

---

## 🚀 Quick Start (TL;DR)

```bash
# Przejdź do pluginu
cd /Users/michalstaniecko/Projects/woocommerce-plugins/wp-ihumbak-chat/wp-ihumbak-chat-simple

# Zainstaluj do WordPress
./build.sh --install
# Podaj ścieżkę do WordPress plugins

# Lub utwórz symlink (lepsze dla dev)
./test-local.sh
# Podaj ścieżkę do WordPress plugins

# Aktywuj w WordPress Admin → Plugins → iHumbak Chat
```

---

## 📚 Dodatkowe Zasoby

### Struktura buildu
```
build/
└── ihumbak-chat/          # Gotowy plugin
    ├── ihumbak-chat.php
    ├── includes/
    ├── admin/
    ├── public/
    └── ...

dist/
└── ihumbak-chat-1.0.0.zip # ZIP do dystrybucji
```

### Co NIE jest kopiowane do buildu
- Katalog `docs/`
- Katalog `.git/`
- Pliki `.DS_Store`
- `node_modules/`
- `vendor/`
- Skrypty buildujące (`build.sh`, `test-local.sh`)

---

## 🎯 Następne Kroki Po Instalacji

1. **Aktywuj plugin** w WordPress Admin
2. **Skonfiguruj ustawienia** w iHumbak Chat → Settings
3. **Dodaj email admina**
4. **(Opcjonalnie) Dodaj klucze reCAPTCHA**
5. **Testuj widget** na frontendzie
6. **Sprawdź panel admina** - wyślij testową wiadomość

---

## 💡 Porady Pro

### Tip 1: Alias w bash
Dodaj do `~/.bashrc` lub `~/.zshrc`:
```bash
alias ihumbak-build="cd ~/Projects/.../wp-ihumbak-chat-simple && ./build.sh"
alias ihumbak-test="cd ~/Projects/.../wp-ihumbak-chat-simple && ./test-local.sh"
```

### Tip 2: Watch dla auto-rebuild
```bash
# Używając fswatch (macOS)
fswatch -o . | xargs -n1 -I{} ./build.sh
```

### Tip 3: Testowanie w wielu instalacjach
```bash
# Utwórz symlinka w wielu miejscach
./test-local.sh  # Site 1
./test-local.sh  # Site 2
# etc.
```

---

**Ostatnia aktualizacja:** 15 listopada 2025  
**Wersja dokumentacji:** 1.0  
**Autor:** Michał Staniecko

Powodzenia w testowaniu! 🚀
