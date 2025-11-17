# 📦 Skrypty Budowania - Podsumowanie

## ✅ Utworzone Skrypty

### 1. `build.sh` - Główny skrypt budowania
**Plik:** `/wp-ihumbak-chat-simple/build.sh`  
**Uprawnienia:** Wykonywalne (`chmod +x`)

#### Funkcje:
- ✅ Walidacja składni PHP wszystkich plików
- ✅ Czyszczenie starych buildów
- ✅ Kopiowanie niezbędnych plików
- ✅ Tworzenie archiwum ZIP
- ✅ Automatyczna instalacja do WordPress
- ✅ Kolorowy output z informacjami o postępie

#### Użycie:
```bash
./build.sh              # Zbuduj plugin ZIP
./build.sh --install    # Zbuduj i zainstaluj do WordPress
./build.sh --clean      # Wyczyść katalogi build/ i dist/
./build.sh --help       # Pokaż pomoc
```

#### Co robi:
1. **Walidacja** - sprawdza składnię wszystkich plików `.php`
2. **Czyszczenie** - usuwa stare buildy
3. **Kopiowanie** - tworzy strukturę w `build/ihumbak-chat/`
4. **Pakowanie** - tworzy ZIP w `dist/ihumbak-chat-1.0.0.zip`
5. **Instalacja** - (opcjonalnie) kopiuje do WordPress

---

### 2. `test-local.sh` - Szybki setup dla testów
**Plik:** `/wp-ihumbak-chat-simple/test-local.sh`  
**Uprawnienia:** Wykonywalne (`chmod +x`)

#### Funkcje:
- ✅ Tworzy symlink do WordPress
- ✅ Sprawdza ścieżki
- ✅ Usuwa stare instalacje
- ✅ Live reload - zmiany widoczne od razu

#### Użycie:
```bash
./test-local.sh
# Podaj ścieżkę: /path/to/wordpress/wp-content/plugins
```

#### Zalety symlinka:
- 🚀 Zmiany w plikach widoczne natychmiast
- 💾 Nie trzeba rebuilować
- ⚡ Idealne do developmentu

---

## 📚 Dokumentacja

### 1. `BUILD-GUIDE.md` - Kompletny przewodnik
**Zawartość:**
- Szczegółowe instrukcje budowania
- Wszystkie scenariusze użycia
- Rozwiązywanie problemów
- Typowe ścieżki WordPress
- Tips & tricks

### 2. `QUICK-START.md` - Szybki start
**Zawartość:**
- 3 kroki do testowania
- Podstawowe komendy
- Link do pełnej dokumentacji

### 3. `.wp-paths.example` - Przykładowa konfiguracja
**Zawartość:**
- Przykładowe ścieżki WordPress
- Instrukcje użycia
- Różne środowiska (LocalWP, MAMP, etc.)

---

## 🎯 Przykłady Użycia

### Scenariusz 1: Pierwsze Testowanie
```bash
# Przejdź do katalogu pluginu
cd /Users/michalstaniecko/Projects/woocommerce-plugins/wp-ihumbak-chat/wp-ihumbak-chat-simple

# Utwórz symlink do WordPress
./test-local.sh
# Podaj ścieżkę: /Users/michalstaniecko/Local Sites/mysite/app/public/wp-content/plugins

# Aktywuj w WordPress Admin → Plugins → iHumbak Chat
```

### Scenariusz 2: Development z Live Reload
```bash
# Symlink jest już utworzony (jak w Scenariuszu 1)

# Edytuj pliki w swoim edytorze (VSCode, PhpStorm, etc.)
# np. public/css/ihumbak-widget.css

# Odśwież stronę WordPress
# Zmiany widoczne natychmiast! 🎉
```

### Scenariusz 3: Budowanie ZIP do dystrybucji
```bash
# Zbuduj finalną wersję
./build.sh

# Plik ZIP będzie w:
# dist/ihumbak-chat-1.0.0.zip

# Możesz go:
# - Zainstalować przez WordPress Admin
# - Udostępnić innym
# - Wrzucić na produkcję
```

### Scenariusz 4: Instalacja do wielu WordPress
```bash
# Build i instalacja do pierwszej strony
./build.sh --install
# Podaj: /path/to/site1/wp-content/plugins

# Powtórz dla drugiej strony
./build.sh --install  
# Podaj: /path/to/site2/wp-content/plugins
```

### Scenariusz 5: Czyszczenie i rebuild
```bash
# Wyczyść wszystko
./build.sh --clean

# Zbuduj od nowa
./build.sh --install
```

---

## 📁 Struktura Po Buildzie

```
wp-ihumbak-chat-simple/
├── build/                          # Katalog tymczasowy (git ignored)
│   └── ihumbak-chat/              # Gotowy plugin
│       ├── ihumbak-chat.php
│       ├── README.md
│       ├── LICENSE
│       ├── includes/
│       ├── admin/
│       ├── public/
│       ├── assets/
│       └── languages/
│
├── dist/                           # Katalog dystrybucji (git ignored)
│   └── ihumbak-chat-1.0.0.zip     # ZIP gotowy do instalacji
│
├── build.sh                        # Skrypt budowania
├── test-local.sh                   # Skrypt testowy
├── BUILD-GUIDE.md                  # Pełna dokumentacja
├── QUICK-START.md                  # Szybki start
└── .wp-paths.example               # Przykładowa konfiguracja
```

---

## ⚙️ Konfiguracja Skryptów

### Zmienne w `build.sh`

```bash
PLUGIN_SLUG="ihumbak-chat"          # Nazwa pluginu
PLUGIN_VERSION="1.0.0"              # Wersja
BUILD_DIR="build"                   # Katalog buildu
DIST_DIR="dist"                     # Katalog dystrybucji
```

### Co Jest Kopiowane do Buildu

✅ **Kopiowane:**
- `ihumbak-chat.php` (plik główny)
- `README.md`
- `LICENSE`
- `CHANGELOG.md`
- `includes/` (wszystkie klasy)
- `admin/` (panel admina)
- `public/` (frontend)
- `assets/` (ikony)
- `languages/` (tłumaczenia)

❌ **NIE kopiowane:**
- `docs/` (dokumentacja techniczna)
- `.git/` (repozytorium)
- `node_modules/`
- `vendor/`
- `.env`
- Pliki buildu
- Skrypty (`build.sh`, `test-local.sh`)

---

## 🔍 Walidacja i Bezpieczeństwo

### Automatyczna Walidacja PHP
```bash
# Skrypt automatycznie sprawdza składnię
./build.sh  # Zatrzyma się jeśli znajdzie błędy
```

### Ręczna Walidacja
```bash
# Sprawdź jeden plik
php -l ihumbak-chat.php

# Sprawdź wszystkie pliki
find . -name "*.php" -not -path "./vendor/*" -exec php -l {} \;
```

---

## 🚨 Rozwiązywanie Problemów

### Problem: Skrypt nie działa
```bash
# Sprawdź uprawnienia
ls -la build.sh

# Jeśli nie ma 'x', ustaw uprawnienia
chmod +x build.sh
chmod +x test-local.sh
```

### Problem: PHP nie znaleziony
```bash
# Sprawdź czy PHP jest zainstalowane
which php

# Jeśli nie, zainstaluj
brew install php  # macOS
```

### Problem: ZIP nie znaleziony
```bash
# Sprawdź czy zip jest zainstalowany
which zip

# Jeśli nie, zainstaluj
brew install zip  # macOS
```

### Problem: Plugin nie widoczny w WordPress
```bash
# Sprawdź czy skopiował się poprawnie
ls -la /path/to/wordpress/wp-content/plugins/ihumbak-chat

# Sprawdź uprawnienia
chmod -R 755 /path/to/wordpress/wp-content/plugins/ihumbak-chat
```

---

## 💡 Pro Tips

### Tip 1: Alias Bash
Dodaj do `~/.zshrc` lub `~/.bashrc`:

```bash
# Alias dla szybkiego budowania
alias ihumbak-build="cd ~/Projects/.../wp-ihumbak-chat-simple && ./build.sh"
alias ihumbak-test="cd ~/Projects/.../wp-ihumbak-chat-simple && ./test-local.sh"
alias ihumbak-clean="cd ~/Projects/.../wp-ihumbak-chat-simple && ./build.sh --clean"

# Użycie
ihumbak-build --install
```

### Tip 2: Automatyczny rebuild przy zmianie
```bash
# Używając fswatch (macOS)
brew install fswatch

# Auto-rebuild
cd wp-ihumbak-chat-simple
fswatch -o . | xargs -n1 -I{} ./build.sh
```

### Tip 3: Szybkie przełączanie między wersjami
```bash
# Stwórz brancha dla nowych feature'ów
git checkout -b feature/new-widget

# Build i test
./test-local.sh

# Wróć do głównej wersji
git checkout main
./test-local.sh
```

---

## 📊 Porównanie Metod

| Metoda | Build | Symlink | Manual Copy |
|--------|-------|---------|-------------|
| Szybkość | ⭐⭐⭐ | ⭐⭐⭐⭐⭐ | ⭐⭐ |
| Live Reload | ❌ | ✅ | ❌ |
| Łatwość | ⭐⭐⭐⭐ | ⭐⭐⭐⭐⭐ | ⭐⭐⭐ |
| Dla Development | ⭐⭐⭐ | ⭐⭐⭐⭐⭐ | ⭐⭐ |
| Dla Produkcji | ⭐⭐⭐⭐⭐ | ⭐ | ⭐⭐⭐⭐ |

### Zalecenia:
- **Development:** Użyj `test-local.sh` (symlink)
- **Testing:** Użyj `build.sh --install`
- **Produkcja:** Użyj `build.sh` (tylko ZIP)

---

## ✅ Checklist Przed Pierwszym Użyciem

- [ ] PHP 7.4+ zainstalowane
- [ ] ZIP utility zainstalowane
- [ ] Bash dostępny
- [ ] Lokalne WordPress działa
- [ ] Znasz ścieżkę do `wp-content/plugins`
- [ ] Skrypty mają uprawnienia wykonywania
- [ ] Jesteś w katalogu pluginu

---

## 🎓 Następne Kroki

1. ✅ **Testuj plugin lokalnie**
   ```bash
   ./test-local.sh
   ```

2. ✅ **Konfiguruj ustawienia**
   - WordPress Admin → iHumbak Chat → Settings

3. ✅ **Testuj funkcjonalność**
   - Wyślij testową wiadomość
   - Sprawdź panel admina
   - Sprawdź email notification

4. ✅ **Customizuj**
   - Zmień kolory w CSS
   - Dostosuj teksty
   - Dodaj reCAPTCHA

5. ✅ **Przygotuj do produkcji**
   ```bash
   ./build.sh
   ```

---

## 📞 Pomoc i Wsparcie

**Dokumentacja:**
- `BUILD-GUIDE.md` - Szczegółowy przewodnik
- `QUICK-START.md` - Szybki start
- `README.md` - Dokumentacja użytkownika
- `INSTALLATION.md` - Instrukcje instalacji

**Pliki konfiguracyjne:**
- `.wp-paths.example` - Przykładowe ścieżki

**Skrypty:**
- `build.sh --help` - Pomoc dla skryptu budowania
- `test-local.sh` - Interaktywny setup

---

## 📅 Changelog Skryptów

### Wersja 1.0.0 (2025-11-15)
- ✅ Utworzono `build.sh` z pełną funkcjonalnością
- ✅ Utworzono `test-local.sh` dla szybkich testów
- ✅ Dodano walidację PHP
- ✅ Dodano kolorowy output
- ✅ Dodano instalację do WordPress
- ✅ Dodano czyszczenie katalogów
- ✅ Pełna dokumentacja

---

**Autor:** Michał Staniecko  
**Data:** 15 listopada 2025  
**Wersja:** 1.0.0

**Status:** ✅ Gotowe do użycia!

---

Powodzenia w testowaniu pluginu! 🚀
