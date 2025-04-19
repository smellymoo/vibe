# VIBE 🔥 
(Very Intelligent; Barely Executable)

For **YOLO-Driven Development**, meet **VIBE** - the scripting language where your code syntax is more of a suggestion than a requirement.
Write PHP-ish, Python-y, or just keyboard smash - Vibe will sort it out. Probably.

Make coding fun again. Specially useful for quick throw-away scripts to get sh*t done.

# Install

```bash
# install / re-install:
sudo ./install.sh

# to try out without installing:
1) run: sudo ./setup.sh
2) run vibe script with: php ./interpreter.php your_script.vibe

# to uninstall (coward!):
sudo ./install.sh --remove
```
# Usage

```
# run your vibe script:
vibe your_script.vibe
```

# Vibe script example:

```vibe
echo "ask a question:" + newline
msg = get user input

categories = [
    HAPPY => happy sentiment
    NEUTRAL => neutral sentiment
    SAD => negative sentiment
]

mood = categorise(msg, categories);

switch(mood) {
    case HAPPY: order_uber_eats();
    case NEUTRAL: print("https://www.youtube.com/watch?v=dQw4w9WgXcQ");
    case SAD: die("Ctrl+Z yourself");
}
```

