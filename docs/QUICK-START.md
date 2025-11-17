# 🚀 Quick Start - Testowanie Lokalne

## Najszybsza metoda (3 kroki)

### 1️⃣ Przejdź do katalogu pluginu
```bash
cd /Users/michalstaniecko/Projects/woocommerce-plugins/wp-ihumbak-chat/wp-ihumbak-chat-simple
```

### 2️⃣ Uruchom skrypt testowy
```bash
./test-local.sh
```

### 3️⃣ Podaj ścieżkę do WordPress
Przykład dla LocalWP:
```
/Users/michalstaniecko/Local Sites/NAZWA_STRONY/app/public/wp-content/plugins
```

### ✅ Gotowe!
- Idź do WordPress Admin → Plugins
- Aktywuj **iHumbak Chat**
- Konfiguruj w **iHumbak Chat → Settings**

---

## Alternatywnie: Build i instalacja

```bash
./build.sh --install
```

---

## Więcej informacji

📖 Szczegółowa dokumentacja: [BUILD-GUIDE.md](BUILD-GUIDE.md)

## Dostępne komendy

```bash
./build.sh              # Zbuduj ZIP
./build.sh --install    # Zbuduj i zainstaluj
./build.sh --clean      # Wyczyść pliki
./test-local.sh         # Symlink do WordPress
```

---

**Potrzebujesz pomocy?** Zobacz [BUILD-GUIDE.md](BUILD-GUIDE.md)
